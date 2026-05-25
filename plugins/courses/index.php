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
 * index.php
 *
 * @package   koperedashboard_courses
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_courses\service\course_service;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/courses:manage", $context);

$q = optional_param("q", "", PARAM_RAW_TRIMMED);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/courses/", ["q" => $q]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("page_title", "koperedashboard_courses"));

$PAGE->requires->strings_for_js(["search_help", "no_results"], "koperedashboard_courses");
$PAGE->requires->js_call_amd("koperedashboard_courses/search", "init", []);

$results = course_service::search_courses($q, 30, 0);

$templatecontext = [
    "q" => $q,
    "placeholder" => get_string("search_placeholder", "koperedashboard_courses"),
    "help" => get_string("search_help", "koperedashboard_courses"),
    "results" => [],
    "hasresults" => !empty($results),
    "emptytitle" => get_string("empty_query_title", "koperedashboard_courses"),
    "noresults" => get_string("no_results", "koperedashboard_courses"),
];

foreach ($results as $course) {
    $templatecontext["results"][] = [
        "id" => $course->id,
        "fullname" => format_string($course->fullname),
        "shortname" => $course->shortname,
        "category" => format_string($course->categoryname),
        "visible" => !empty($course->visible),
        "startdate" => !empty($course->startdate) ? userdate::format($course->startdate) : "-",
        "enddate" => !empty($course->enddate) ? userdate::format($course->enddate) : "-",
        "modified" => !empty($course->timemodified) ? userdate::format($course->timemodified) : "-",
        "viewurl" => new moodle_url("/local/kopere_dashboard/plugins/courses/view.php", ["id" => $course->id]),
        "courseurl" => new moodle_url("/course/view.php", ["id" => $course->id]),
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_courses/index", $templatecontext);
layout::page_render($context, $content, true);
