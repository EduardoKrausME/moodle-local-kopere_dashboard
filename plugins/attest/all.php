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
 * Lists generated attestations for a specific template.
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\service\issue_service;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/attest:manage", $context);

$tplid = required_param("tplid", PARAM_INT);
$courseid = optional_param("courseid", 0, PARAM_INT);
$userid = optional_param("userid", 0, PARAM_INT);
$page = optional_param("page", 0, PARAM_INT);
$perpage = 50;

$template = $DB->get_record("koperedashboard_attest_tpl", ["id" => $tplid], "*", MUST_EXIST);

$urlparams = ["tplid" => $tplid];
if ($courseid) {
    $urlparams["courseid"] = $courseid;
}
if ($userid) {
    $urlparams["userid"] = $userid;
}

$pageurl = new moodle_url("/local/kopere_dashboard/plugins/attest/all.php", $urlparams);
$PAGE->set_url($pageurl);
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("all_issues_title", "koperedashboard_attest") . ": " . format_string($template->name));

$courseoptions = [];
$coursesql = "SELECT DISTINCT c.id, c.fullname
                FROM {koperedashboard_attest_issue} ai
                JOIN {course} c ON c.id = ai.courseid
               WHERE ai.tplid = :tplid
            ORDER BY c.fullname ASC";
foreach ($DB->get_records_sql($coursesql, ["tplid" => $tplid]) as $course) {
    $courseoptions[] = [
        "id" => $course->id,
        "name" => format_string($course->fullname),
        "selected" => $courseid === (int) $course->id,
    ];
}

$useroptions = [];
$usersql = "SELECT DISTINCT u.id,
                            u.firstname,
                            u.lastname,
                            u.firstnamephonetic,
                            u.lastnamephonetic,
                            u.middlename,
                            u.alternatename,
                            u.email
               FROM {koperedashboard_attest_issue} ai
               JOIN {user} u ON u.id = ai.userid
              WHERE ai.tplid = :tplid
                AND u.deleted = 0
           ORDER BY u.firstname ASC, u.lastname ASC, u.id ASC";
foreach ($DB->get_records_sql($usersql, ["tplid" => $tplid]) as $student) {
    $studentname = fullname($student);
    if (!empty($student->email)) {
        $studentname .= " ({$student->email})";
    }

    $useroptions[] = [
        "id" => $student->id,
        "name" => $studentname,
        "selected" => $userid === (int) $student->id,
    ];
}

$where = ["ai.tplid = :tplid"];
$params = ["tplid" => $tplid];
if ($courseid) {
    $where[] = "ai.courseid = :courseid";
    $params["courseid"] = $courseid;
}
if ($userid) {
    $where[] = "ai.userid = :userid";
    $params["userid"] = $userid;
}
$whereclause = "WHERE " . implode(" AND ", $where);

$total = $DB->count_records_sql(
    "SELECT COUNT(1)
       FROM {koperedashboard_attest_issue} ai
       {$whereclause}",
    $params
);

$sql = "SELECT ai.*,
               tpl.id AS currenttplid,
               tpl.name AS tplname,
               c.id AS currentcourseid,
               c.fullname AS coursefullname,
               u.id AS currentuserid,
               u.deleted AS userdeleted,
               u.firstname,
               u.lastname,
               u.firstnamephonetic,
               u.lastnamephonetic,
               u.middlename,
               u.alternatename,
               u.email
          FROM {koperedashboard_attest_issue} ai
     LEFT JOIN {koperedashboard_attest_tpl} tpl ON tpl.id = ai.tplid
     LEFT JOIN {course} c ON c.id = ai.courseid
     LEFT JOIN {user} u ON u.id = ai.userid
          {$whereclause}
      ORDER BY ai.timecreated DESC, ai.id DESC";
$issues = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

$now = time();
$rows = [];
foreach ($issues as $issue) {
    $isvalid = issue_service::is_issue_valid($issue, $now);
    $isexpiringsoon = $isvalid && issue_service::is_issue_expiring_soon($issue, $now);

    $statuslabel = get_string("status_expired", "koperedashboard_attest");
    $statuslabelclass = "danger";
    if ($isvalid) {
        $statuslabel = $isexpiringsoon
            ? get_string("status_valid_expiring", "koperedashboard_attest")
            : get_string("status_valid", "koperedashboard_attest");
        $statuslabelclass = $isexpiringsoon ? "warning" : "success";
    }

    $hasstudent = !empty($issue->currentuserid) && empty($issue->userdeleted);
    $studentname = $hasstudent
        ? fullname($issue)
        : get_string("student_removed", "koperedashboard_attest", $issue->userid);

    $canopen = !empty($issue->currenttplid) && !empty($issue->currentcourseid) && $hasstudent;

    $rows[] = [
        "id" => $issue->id,
        "studentname" => $studentname,
        "studentemail" => $hasstudent ? $issue->email : "",
        "hasstudentemail" => $hasstudent && !empty($issue->email),
        "coursename" => !empty($issue->coursefullname)
            ? format_string($issue->coursefullname)
            : get_string("course_removed", "koperedashboard_attest"),
        "created" => userdate::format($issue->timecreated),
        "validuntil" => userdate::format($issue->validuntil),
        "statuslabel" => $statuslabel,
        "statuslabelclass" => $statuslabelclass,
        "hasopenurl" => $canopen,
        "openurl" => $canopen ? new moodle_url("/local/kopere_dashboard/plugins/attest/pdf.php", [
            "token" => $issue->token,
        ]) : null,
    ];
}

$pagingbar = $OUTPUT->paging_bar($total, $page, $perpage, $pageurl);
$baseurl = new moodle_url("/local/kopere_dashboard/plugins/attest/all.php", ["tplid" => $tplid]);

$content = $OUTPUT->render_from_template("koperedashboard_attest/all", [
    "tplid" => $tplid,
    "templatename" => format_string($template->name),
    "description" => get_string("all_issues_desc", "koperedashboard_attest", $total),
    "courseid" => $courseid,
    "userid" => $userid,
    "courseoptions" => $courseoptions,
    "useroptions" => $useroptions,
    "hasrows" => !empty($rows),
    "rows" => $rows,
    "pagingbar" => $pagingbar,
    "filterurl" => $baseurl,
    "clearurl" => $baseurl,
    "backurl" => new moodle_url("/local/kopere_dashboard/plugins/attest/manage.php"),
]);
layout::page_render($context, $content, true);
