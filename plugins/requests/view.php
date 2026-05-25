<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * view.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_requests\audit\logger;
use koperedashboard_requests\form\reply_form;
use koperedashboard_requests\service\permission;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");
require_once("{$CFG->dirroot}/lib/filelib.php");

$id = required_param("id", PARAM_INT);

$context = context_system::instance();
require_login();

$request = $DB->get_record("koperedashboard_req_request", ["id" => $id], "*", MUST_EXIST);

if (!permission::can_view_request($request)) {
    throw new required_capability_exception($context, "koperedashboard/requests:create", "nopermissions", "");
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $id]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("view_request", "koperedashboard_requests"));

$canrespond = permission::can_respond_request($request);
$isowner = ($request->userid == $USER->id);
$canmanage = has_capability("koperedashboard/requests:manage", $context);

$draftitemid = file_get_submitted_draft_itemid("attachments_filemanager");
file_prepare_draft_area(
    $draftitemid,
    $context->id,
    "koperedashboard_requests",
    "messageattachments",
    0,
    ["subdirs" => 0, "maxfiles" => 10]
);

$action = optional_param("action", "", PARAM_ALPHA);
if ($action == "close" && confirm_sesskey()) {
    if ($isowner || $canrespond || $canmanage) {
        $request->status = "closed";
        $request->timemodified = time();
        $DB->update_record("koperedashboard_req_request", $request);
        logger::request_closed($request->id);
    }
    redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $id]));
}
if ($action == "reopen" && confirm_sesskey()) {
    if ($isowner || $canrespond || $canmanage) {
        $request->status = "open";
        $request->timemodified = time();
        $DB->update_record("koperedashboard_req_request", $request);
        logger::request_reopened($request->id);
    }
    redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $id]));
}

$form = new reply_form(null, []);
$form->set_data(
    (object) [
        "id" => $id,
        "attachments_filemanager" => $draftitemid,
    ]
);

if ($data = $form->get_data()) {
    if (!$canrespond && !$isowner) {
        throw new required_capability_exception($context, "koperedashboard/requests:respond", "nopermissions", "");
    }

    if ($request->status == "closed") {
        redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $id]));
    }

    $isstaff = $canrespond ? 1 : 0;

    $koperedashboardreqmessage = (object) [
        "requestid" => $request->id,
        "userid" => $USER->id,
        "message" => $data->reply_editor["text"],
        "messageformat" => $data->reply_editor["format"],
        "isstaff" => $isstaff,
        "timecreated" => time(),
    ];
    $messageid = $DB->insert_record("koperedashboard_req_message", $koperedashboardreqmessage);

    file_save_draft_area_files(
        $draftitemid,
        $context->id,
        "koperedashboard_requests",
        "messageattachments",
        $messageid,
        ["subdirs" => 0, "maxfiles" => 10]
    );

    if ($isstaff) {
        $request->status = "answered";
        $request->timemodified = time();
        $DB->update_record("koperedashboard_req_request", $request);
    } else {
        $request->status = "open";
        $request->timemodified = time();
        $DB->update_record("koperedashboard_req_request", $request);
    }

    logger::request_replied($request->id, (bool) $isstaff);

    redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $id]));
}

$cat = $DB->get_record("koperedashboard_req_category", ["id" => $request->categoryid], "id,name", MUST_EXIST);

$messages = $DB->get_records("koperedashboard_req_message", ["requestid" => $request->id], "timecreated ASC");

$rows = [];
foreach ($messages as $m) {
    $u = core_user::get_user($m->userid, "*", MUST_EXIST);

    $fs = get_file_storage();
    $messagefiles = [];
    $messageareafiles = $fs->get_area_files(
        $context->id,
        "koperedashboard_requests",
        "messageattachments",
        $m->id,
        "filename",
        false
    );
    foreach ($messageareafiles as $f) {
        $url = moodle_url::make_pluginfile_url(
            $context->id,
            "koperedashboard_requests",
            "messageattachments",
            $m->id,
            $f->get_filepath(),
            $f->get_filename()
        );

        $messagefiles[] = [
            "name" => $f->get_filename(),
            "url" => $url,
            "size" => display_size($f->get_filesize()),
        ];
    }

    $rows[] = [
        "author" => fullname($u),
        "rolelabel" => !empty($m->isstaff) ? get_string("staff_role_label", "koperedashboard_requests") : "",
        "time" => userdate::format($m->timecreated),
        "messagehtml" => format_text($m->message, $m->messageformat, ["context" => $context, "overflowdiv" => true]),
        "isstaff" => !empty($m->isstaff),
        "files" => $messagefiles,
    ];
}

$files = [];
$fs = get_file_storage();
$areafiles = $fs->get_area_files($context->id, "koperedashboard_requests", "attachments", $request->id, "filename", false);
foreach ($areafiles as $f) {
    $url = moodle_url::make_pluginfile_url(
        $context->id,
        "koperedashboard_requests",
        "attachments",
        $request->id,
        $f->get_filepath(),
        $f->get_filename()
    );

    $files[] = [
        "name" => $f->get_filename(),
        "url" => $url,
        "size" => display_size($f->get_filesize()),
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_requests/view", [
    "id" => $request->id,
    "category" => format_string($cat->name),
    "subject" => format_string($request->subject),
    "status" => $request->status,
    "created" => userdate::format($request->timecreated),
    "files" => $files,
    "messages" => $rows,
    "canreply" => ($request->status != "closed") && ($canrespond || $isowner),
    "formhtml" => $form->render(),
    "sesskey" => sesskey(),
    "closeurl" => new moodle_url(
        "/local/kopere_dashboard/plugins/requests/view.php", ["id" => $request->id, "action" => "close", "sesskey" => sesskey()]
    ),
    "reopenurl" => new moodle_url(
        "/local/kopere_dashboard/plugins/requests/view.php", ["id" => $request->id, "action" => "reopen", "sesskey" => sesskey()]
    ),
    "isclosed" => ($request->status == "closed"),
]);
layout::page_render($context, $content, true);
