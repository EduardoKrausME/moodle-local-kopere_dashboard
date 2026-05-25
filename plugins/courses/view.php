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

$courseid = required_param("id", PARAM_INT);

$course = course_service::get_course($courseid);
$coursecontext = context_course::instance($courseid);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/courses/view.php", ["id" => $courseid]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("view_title", "koperedashboard_courses", format_string($course->fullname)));

$canviewparticipants = has_capability("moodle/course:viewparticipants", $coursecontext);

$templatecontext = [
    "id" => $course->id,
    "fullname" => format_string($course->fullname),
    "shortname" => $course->shortname,
    "idnumber" => !empty($course->idnumber) ? $course->idnumber : "-",
    "visible" => !empty($course->visible),
    "startdate" => !empty($course->startdate) ? userdate::format($course->startdate) : "-",
    "enddate" => !empty($course->enddate) ? userdate::format($course->enddate) : "-",
    "modified" => !empty($course->timemodified) ? userdate::format($course->timemodified) : "-",

    "searchurl" => new moodle_url("/local/kopere_dashboard/plugins/courses/"),
    "courseurl" => new moodle_url("/course/view.php", ["id" => $course->id]),

    "quicklinks" => [
        [
            "title" => get_string("action_open_course", "koperedashboard_courses"),
            "url" => new moodle_url("/course/view.php", ["id" => $course->id]),
            "type" => "primary",
        ],
        [
            "title" => get_string("action_participants", "koperedashboard_courses"),
            "url" => new moodle_url("/user/", ["id" => $course->id]),
            "type" => "secondary",
        ],
        [
            "title" => get_string("action_gradebook", "koperedashboard_courses"),
            "url" => new moodle_url("/grade/report/grader/", ["id" => $course->id]),
            "type" => "secondary",
        ],
        [
            "title" => get_string("action_edit_course", "koperedashboard_courses"),
            "url" => new moodle_url("/course/edit.php", ["id" => $course->id]),
            "type" => "secondary",
        ],
        [
            "title" => get_string("action_logs", "koperedashboard_courses"),
            "url" => new moodle_url("/report/log/", ["id" => $course->id]),
            "type" => "secondary",
        ],
        [
            "title" => get_string("quick_open_contracts", "koperedashboard_courses"),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"),
            "type" => "secondary",
        ],
        [
            "title" => get_string("quick_open_plans", "koperedashboard_courses"),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/recurringpayments/plans.php"),
            "type" => "secondary",
        ],
        [
            "title" => get_string("quick_open_requests", "koperedashboard_courses"),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/requests/manage.php"),
            "type" => "secondary",
        ],
    ],

    "metrics" => [],

    "cohorts" => [],
    "hascohorts" => false,

    "contracts" => [],
    "hascontracts" => false,

    "plans" => [],
    "hasplans" => false,

    "requests" => [],
    "hasrequests" => false,

    "participants" => [],
    "hasparticipants" => false,
    "canviewparticipants" => $canviewparticipants,
];

global $DB;

$manager = $DB->get_manager();
$tableexists = function(string $name) use ($manager): bool {
    return $manager->table_exists(new xmldb_table($name));
};

$enrolledcount = 0;
try {
    $enrolledcount = course_service::count_enrolled_users($courseid);
} catch (Exception $e) {
    $enrolledcount = 0;
}

$cohortcount = 0;
$contractcount = 0;
$plancount = 0;
$openrequests = 0;

// Cohorts linked via cohort enrolment method.
if ($tableexists("enrol") && $tableexists("cohort")) {
    $sql = "SELECT DISTINCT ch.id, ch.name
              FROM {enrol} e
              JOIN {cohort} ch ON ch.id = e.customint1
             WHERE e.courseid = :courseid
               AND e.enrol = 'cohort'
               AND e.status = 0
          ORDER BY ch.name ASC";
    $cohorts = $DB->get_records_sql($sql, ["courseid" => $courseid], 0, 100);
    foreach ($cohorts as $ch) {
        $templatecontext["cohorts"][] = [
            "id" => $ch->id,
            "name" => $ch->name,
        ];
    }
    $templatecontext["hascohorts"] = !empty($templatecontext["cohorts"]);
    $cohortcount = count($templatecontext["cohorts"]);
}

// Contracts mapped to the course.
if ($tableexists("koperedashboard_contract_courses") && $tableexists("koperedashboard_contracts")) {
    $sql = "SELECT c.id, c.name, c.active,
                   (SELECT COUNT(1)
                      FROM {koperedashboard_contract_accept} a
                     WHERE a.contractid = c.id AND a.courseid = :courseid1) AS acceptedcount
              FROM {koperedashboard_contract_courses} cc
              JOIN {koperedashboard_contracts} c ON c.id = cc.contractid
             WHERE cc.courseid = :courseid2
          ORDER BY c.name ASC";
    $rows = $DB->get_records_sql($sql, ["courseid1" => $courseid, "courseid2" => $courseid], 0, 200);

    foreach ($rows as $row) {
        $templatecontext["contracts"][] = [
            "id" => $row->id,
            "name" => format_string($row->name),
            "active" => !empty($row->active),
            "accepted" => $row->acceptedcount,
        ];
    }

    $templatecontext["hascontracts"] = !empty($templatecontext["contracts"]);
    $contractcount = count($templatecontext["contracts"]);
}

// Payment plans that include this course.
if ($tableexists("koperedashboard_rpay_plan_course") && $tableexists("koperedashboard_rpay_plan")) {
    $sql = "SELECT p.id, p.name, p.active, p.installments, p.amount,
                   (SELECT COUNT(1)
                      FROM {koperedashboard_rpay_plan_user} pu
                     WHERE pu.planid = p.id AND pu.status = 'active') AS activeusers
              FROM {koperedashboard_rpay_plan_course} pc
              JOIN {koperedashboard_rpay_plan} p ON p.id = pc.planid
             WHERE pc.courseid = :courseid
          ORDER BY p.name ASC";
    $rows = $DB->get_records_sql($sql, ["courseid" => $courseid], 0, 200);

    foreach ($rows as $row) {
        $pending = 0;
        $overdue = 0;
        $nextdue = null;

        if ($tableexists("koperedashboard_rpay_installment") && $tableexists("koperedashboard_rpay_plan_user")) {
            $sql2 = "SELECT i.status, i.duedate
                       FROM {koperedashboard_rpay_installment} i
                       JOIN {koperedashboard_rpay_plan_user} pu ON pu.id = i.planuserid
                      WHERE pu.planid = :planid
                        AND pu.status = 'active'
                        AND i.excluded = 0
                        AND i.status <> 'paid'
                   ORDER BY i.duedate ASC";
            $inst = $DB->get_records_sql($sql2, ["planid" => $row->id], 0, 2000);

            $now = time();
            foreach ($inst as $i) {
                if ($i->duedate < $now) {
                    $overdue++;
                } else {
                    $pending++;
                    if ($nextdue == null) {
                        $nextdue = $i->duedate;
                    }
                }
            }
        }

        $templatecontext["plans"][] = [
            "id" => $row->id,
            "name" => format_string($row->name),
            "active" => !empty($row->active),
            "installments" => $row->installments,
            "amount" => format_float($row->amount, 2),
            "activeusers" => $row->activeusers,
            "pending" => $pending,
            "overdue" => $overdue,
            "nextdue" => $nextdue ? userdate::format($nextdue) : "-",
        ];
    }

    $templatecontext["hasplans"] = !empty($templatecontext["plans"]);
    $plancount = count($templatecontext["plans"]);
}

// Requests in this course.
if ($tableexists("koperedashboard_req_request") && $tableexists("koperedashboard_req_category")) {
    $sql = "SELECT r.id, r.subject, r.status, r.timecreated,
                   cat.name AS categoryname,
                   u.firstname, u.lastname, u.id AS userid
              FROM {koperedashboard_req_request} r
              JOIN {koperedashboard_req_category} cat ON cat.id = r.categoryid
              JOIN {user} u ON u.id = r.userid
             WHERE r.courseid = :courseid
          ORDER BY r.timemodified DESC";

    $rows = $DB->get_records_sql($sql, ["courseid" => $courseid], 0, 30);

    foreach ($rows as $row) {
        if ($row->status == "open") {
            $openrequests++;
        }

        $templatecontext["requests"][] = [
            "id" => $row->id,
            "subject" => $row->subject,
            "status" => $row->status,
            "category" => $row->categoryname,
            "user" => fullname($row),
            "timecreated" => userdate::format($row->timecreated),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $row->id]),
        ];
    }

    $templatecontext["hasrequests"] = !empty($templatecontext["requests"]);
}

// Participants sample.
if ($canviewparticipants) {
    $fields =
        "u.id, u.firstname, u.lastname, u.email, u.lastaccess,
         u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename";
    $enrolled = get_enrolled_users($coursecontext, "", 0, $fields, "u.lastname ASC, u.firstname ASC", 0, 30);

    foreach ($enrolled as $u) {
        $templatecontext["participants"][] = [
            "name" => fullname($u),
            "email" => $u->email,
            "lastaccess" => !empty($u->lastaccess) ? userdate::format($u->lastaccess) : "-",
            "profileurl" => new moodle_url("/user/profile.php", ["id" => $u->id]),
        ];
    }

    $templatecontext["hasparticipants"] = !empty($templatecontext["participants"]);
}

$templatecontext["metrics"] = [
    [
        "label" => get_string("metric_enrolled", "koperedashboard_courses"),
        "value" => $enrolledcount,
    ],
    [
        "label" => get_string("metric_cohorts", "koperedashboard_courses"),
        "value" => $cohortcount,
    ],
    [
        "label" => get_string("metric_contracts", "koperedashboard_courses"),
        "value" => $contractcount,
    ],
    [
        "label" => get_string("metric_plans", "koperedashboard_courses"),
        "value" => $plancount,
    ],
    [
        "label" => get_string("metric_requests_open", "koperedashboard_courses"),
        "value" => $openrequests,
    ],
];

$content = $OUTPUT->render_from_template("koperedashboard_courses/view", $templatecontext);
layout::page_render($context, $content, true);
