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
 * @package   koperedashboard_reportcard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_reportcard\service\report_service;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/reportcard:view", $context);

$courseid = optional_param("courseid", 0, PARAM_INT);
$useridparam = optional_param("userid", null, PARAM_INT);
$userid = $useridparam ?? $USER->id;

if ($userid != $USER->id) {
    require_capability("koperedashboard/users:manage", $context);
}

$pageparams = [];
if (!empty($courseid)) {
    $pageparams["courseid"] = $courseid;
}
if ($useridparam !== null) {
    $pageparams["userid"] = $useridparam;
}
$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/reportcard/my.php", $pageparams));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("menu_title", "koperedashboard_reportcard"));

$templatecontext = report_service::export_selector($userid, $courseid ?: null);
$templatecontext["userid"] = $useridparam;
if (!empty($courseid)) {
    $course = report_service::get_user_course($userid, $courseid);
    $templatecontext += report_service::export_course_report($userid, $course);
    $templatecontext["selectedcourseid"] = $courseid;

    $student = core_user::get_user($userid, "id, firstname, lastname, picture, imagealt, email", MUST_EXIST);
    $studentpicture = new user_picture($student);
    $studentpicture->size = 80;

    $templatecontext["student"] = [
        "id" => $student->id,
        "name" => fullname($student),
        "email" => $student->email,
        "picture" => $studentpicture->get_url($PAGE)->out(false),
        "profileurl" => (new moodle_url("/user/profile.php", ["id" => $student->id]))->out(false),
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_reportcard/my", $templatecontext);
layout::page_render($context, $content, true);
