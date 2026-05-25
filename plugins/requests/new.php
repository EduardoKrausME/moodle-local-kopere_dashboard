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
 * new.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_requests\audit\logger;
use koperedashboard_requests\form\request_form;
use local_kopere_dashboard\myoverview_helper;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");
require_once("{$CFG->dirroot}/lib/filelib.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/requests:create", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/requests/new.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("new_title", "koperedashboard_requests"));

$cats = $DB->get_records("koperedashboard_req_category", ["active" => 1], "name ASC", "id,name");
$catoptions = [];
foreach ($cats as $c) {
    $catoptions[$c->id] = format_string($c->name);
}

$courses = [0 => "-"];
$usercourses = enrol_get_users_courses($USER->id, true, "id, fullname", "fullname ASC");
$usercourses = myoverview_helper::remove_hidden_courses($usercourses, $USER->id);
foreach ($usercourses as $c) {
    $courses[$c->id] = format_string($c->fullname);
}

$draftitemid = file_get_submitted_draft_itemid("attachments_filemanager");
file_prepare_draft_area($draftitemid, $context->id, "koperedashboard_requests", "attachments", 0, ["subdirs" => 0, "maxfiles" => 10]
);

$form = new request_form(null, [
    "categories" => $catoptions,
    "courses" => $courses,
]);

$form->set_data(
    (object) [
        "attachments_filemanager" => $draftitemid,
    ]
);

if ($form->is_cancelled()) {
    redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/my.php"));
}

if ($data = $form->get_data()) {
    $now = time();

    $request = (object) [
        "categoryid" => $data->categoryid,
        "userid" => $USER->id,
        "courseid" => !empty($data->courseid) ? $data->courseid : null,
        "subject" => $data->subject,
        "status" => "open",
        "timecreated" => $now,
        "timemodified" => $now,
    ];

    $requestid = $DB->insert_record("koperedashboard_req_request", $request);

    $msgtext = $data->message_editor["text"];
    $msgformat = $data->message_editor["format"];

    $koperedashboardreqmessage = (object) [
        "requestid" => $requestid,
        "userid" => $USER->id,
        "message" => $msgtext,
        "messageformat" => $msgformat,
        "isstaff" => 0,
        "timecreated" => $now,
    ];
    $DB->insert_record("koperedashboard_req_message", $koperedashboardreqmessage);

    // IMPORTANT: Itemid MUST be the request ID.
    file_save_draft_area_files(
        $draftitemid,
        $context->id,
        "koperedashboard_requests",
        "attachments",
        $requestid,
        ["subdirs" => 0, "maxfiles" => 10]
    );

    logger::request_created($requestid);

    redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $requestid]));
}

$content = $OUTPUT->render_from_template("koperedashboard_requests/new", [
    "formhtml" => $form->render(),
]);
layout::page_render($context, $content, true);
