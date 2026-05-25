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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\service\issue_service;
use local_kopere_dashboard\myoverview_helper;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/attest:view", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/attest/my.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("student_title", "koperedashboard_attest"));
$PAGE->requires->js_call_amd("koperedashboard_attest/my", "init", []);

$now = time();
$enrolledcourses = enrol_get_my_courses("id, fullname, shortname", "fullname ASC");
$enrolledcourses = myoverview_helper::remove_hidden_courses($enrolledcourses, $USER->id);
$templates = $DB->get_records("koperedashboard_attest_tpl", ["active" => 1], "name ASC, id ASC");

$courses = [];
$availableitems = [];
foreach ($enrolledcourses as $course) {
    $courses[] = [
        "id" => $course->id,
        "fullname" => format_string($course->fullname),
    ];

    foreach ($templates as $tpl) {
        if (!issue_service::template_is_allowed_for_course($tpl, $course->id)) {
            continue;
        }

        $latestissue = issue_service::get_latest_issue($tpl->id, $course->id, $USER->id);
        $latestvalidissue = issue_service::get_latest_valid_issue($tpl->id, $course->id, $USER->id, $now);

        $hasvalidissue = !empty($latestvalidissue);
        $canrecreate = $hasvalidissue && issue_service::can_recreate_issue($latestvalidissue, $now);
        $cangenerate = !$hasvalidissue || $canrecreate;

        $statuslabel = get_string("status_notgenerated", "koperedashboard_attest");
        if ($hasvalidissue) {
            $statuslabel = get_string("status_valid", "koperedashboard_attest");
            if ($canrecreate) {
                $statuslabel = get_string("status_valid_expiring", "koperedashboard_attest");
            }
        } else if ($latestissue) {
            $statuslabel = get_string("status_expired", "koperedashboard_attest");
        }

        $availableitems[] = [
            "courseid" => $course->id,
            "coursename" => format_string($course->fullname),
            "tplname" => format_string($tpl->name),
            "statuslabel" => $statuslabel,
            "haslastissue" => !empty($latestissue),
            "lastcreated" => $latestissue ? userdate::format($latestissue->timecreated) : "-",
            "lastvaliduntil" => $latestissue ? userdate::format($latestissue->validuntil) : "-",
            "hasopenvalidurl" => $hasvalidissue,
            "openvalidurl" => $hasvalidissue ? new moodle_url("/local/kopere_dashboard/plugins/attest/pdf.php", [
                "issueid" => $latestvalidissue->id,
            ]) : null,
            "cangenerate" => $cangenerate,
            "generateurl" => $cangenerate ? new moodle_url("/local/kopere_dashboard/plugins/attest/pdf.php", [
                "tplid" => $tpl->id,
                "courseid" => $course->id,
                "sesskey" => sesskey(),
                "recreate" => $canrecreate ? 1 : 0,
            ]) : null,
            "generatebuttonlabel" => $canrecreate
                ? get_string("recreate_valid", "koperedashboard_attest")
                : get_string("generate_new", "koperedashboard_attest"),
            "disabledreason" => get_string("already_has_valid", "koperedashboard_attest"),
            "disabledgenerate" => !$cangenerate,
        ];
    }
}

$issuedrows = [];
foreach (issue_service::get_user_issues($USER->id) as $issue) {
    $isvalid = issue_service::is_issue_valid($issue, $now);
    $isexpiringsoon = $isvalid && issue_service::is_issue_expiring_soon($issue, $now);
    $hasrenderableissue = !empty($issue->tplname) && !empty($issue->coursefullname);

    $statuslabel = get_string("status_expired", "koperedashboard_attest");
    $statuslabelclass = "danger";
    if ($isvalid) {
        $statuslabel = $isexpiringsoon
            ? get_string("status_valid_expiring", "koperedashboard_attest")
            : get_string("status_valid", "koperedashboard_attest");

        $statuslabelclass = $isexpiringsoon ? "danger" : "success";
    }

    $recreateurl = null;
    if ($hasrenderableissue && $isexpiringsoon) {
        $recreateurl = new moodle_url("/local/kopere_dashboard/plugins/attest/pdf.php", [
            "tplid" => $issue->tplid,
            "courseid" => $issue->courseid,
            "sesskey" => sesskey(),
            "recreate" => 1,
        ]);
    }

    $issuedrows[] = [
        "coursename" => !empty($issue->coursefullname)
            ? format_string($issue->coursefullname)
            : get_string("course_removed", "koperedashboard_attest"),
        "tplname" => !empty($issue->tplname)
            ? format_string($issue->tplname)
            : get_string("template_removed", "koperedashboard_attest"),
        "created" => userdate::format($issue->timecreated),
        "validuntil" => userdate::format($issue->validuntil),
        "statuslabel" => $statuslabel,
        "statuslabelclass" => $statuslabelclass,
        "hasopenurl" => $hasrenderableissue,
        "openurl" => $hasrenderableissue ? new moodle_url("/local/kopere_dashboard/plugins/attest/pdf.php", [
            "issueid" => $issue->id,
        ]) : null,
        "showrecreate" => !empty($recreateurl),
        "recreateurl" => $recreateurl,
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_attest/my", [
    "hasissues" => !empty($issuedrows),
    "issues" => $issuedrows,
    "hascourses" => !empty($courses),
    "courses" => $courses,
    "hasavailableitems" => !empty($availableitems),
    "availableitems" => $availableitems,
]);
layout::page_render($context, $content, true);
