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
 * ajax.php
 *
 * @package   koperedashboard_courses
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define("AJAX_SCRIPT", true);

use koperedashboard_courses\service\course_service;

require_once(__DIR__ . "/../../../../config.php");

require_login();
require_sesskey();

$context = context_system::instance();
require_capability("koperedashboard/courses:manage", $context);

header("Content-Type: application/json; charset=utf-8");

$q = required_param("q", PARAM_RAW_TRIMMED);

if (strlen(trim($q)) < 2) {
    echo json_encode(["courses" => []]);
    die();
}

$records = course_service::search_courses($q, 10, 0);

$courses = [];
foreach ($records as $course) {
    $courses[] = [
        "id" => $course->id,
        "fullname" => format_string($course->fullname),
        "shortname" => $course->shortname,
        "category" => format_string($course->categoryname),
        "url" => new moodle_url("/local/kopere_dashboard/plugins/courses/view.php", ["id" => $course->id]),
    ];
}

echo json_encode(["courses" => $courses]);
