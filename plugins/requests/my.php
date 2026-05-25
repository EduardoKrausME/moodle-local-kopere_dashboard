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
 * my.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/requests:create", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/requests/my.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("my_title", "koperedashboard_requests"));

$newurl = new moodle_url("/local/kopere_dashboard/plugins/requests/new.php");

$requests = $DB->get_records("koperedashboard_req_request", ["userid" => $USER->id], "timecreated DESC",
    "id,categoryid,courseid,subject,status,timecreated,timemodified");

$rows = [];
foreach ($requests as $r) {
    $cat = $DB->get_record("koperedashboard_req_category", ["id" => $r->categoryid], "id,name", MUST_EXIST);

    $rows[] = [
        "id" => $r->id,
        "category" => format_string($cat->name),
        "subject" => format_string($r->subject),
        "status" => $r->status,
        "created" => userdate::format($r->timecreated),
        "url" => new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $r->id]),
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_requests/my", [
    "newurl" => $newurl,
    "rows" => $rows,
]);
layout::page_render($context, $content, true);
