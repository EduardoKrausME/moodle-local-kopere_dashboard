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
 * @package   koperedashboard_users
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_users\service\user_service;
use local_kopere_dashboard\myoverview_helper;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/users:view", $context);

$userid = required_param("id", PARAM_INT);

$canmanage = has_capability("koperedashboard/users:manage", $context);

if (!$canmanage && $USER->id != $userid) {
    throw new required_capability_exception($context, "koperedashboard/users:manage", "nopermissions", "");
}

$user = user_service::get_user($userid);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/users/view.php", ["id" => $userid]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("view_title", "koperedashboard_users", fullname($user)));

$userpicture = new user_picture($user);
$userpicture->size = 100;

$templatecontext = [
    "id" => $user->id,
    "name" => fullname($user),
    "username" => $user->username,
    "email" => $user->email,
    "picture" => $userpicture->get_url($PAGE),
    "profileurl" => new moodle_url("/user/profile.php", ["id" => $user->id]),
    "searchurl" => new moodle_url("/local/kopere_dashboard/plugins/users/"),
    "quicklinks" => [
        [
            "title" => get_string("action_profile", "koperedashboard_users"),
            "url" => new moodle_url("/user/profile.php", ["id" => $user->id]),
            "type" => "secondary",
        ],
        [
            "title" => get_string("quick_open_contracts", "koperedashboard_users"),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"),
            "type" => "secondary",
        ],
        [
            "title" => get_string("quick_open_payments", "koperedashboard_users"),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/recurringpayments/plans.php"),
            "type" => "secondary",
        ],
        [
            "title" => get_string("quick_open_requests", "koperedashboard_users"),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/requests/manage.php"),
            "type" => "secondary",
        ],
    ],
];

global $DB;

$manager = $DB->get_manager();
$tableexists = function(string $name) use ($manager): bool {
    return $manager->table_exists(new xmldb_table($name));
};

$cohorts = [];
if ($tableexists("cohort_members")) {
    $sql = "SELECT c.id, c.name
              FROM {cohort_members} cm
              JOIN {cohort} c ON c.id = cm.cohortid
             WHERE cm.userid = :userid
          ORDER BY c.name ASC";
    $cohorts = $DB->get_records_sql($sql, ["userid" => $userid], 0, 50);
}

$courses = enrol_get_users_courses($userid, true, "id, fullname, shortname, visible");
if (!$canmanage || $USER->id == $userid) {
    $courses = myoverview_helper::remove_hidden_courses($courses, $userid);
}
$courseitems = [];
foreach ($courses as $course) {
    $courseitems[] = [
        "id" => $course->id,
        "name" => format_string($course->fullname),
        "shortname" => $course->shortname,
        "url" => new moodle_url("/course/view.php", ["id" => $course->id]),
    ];
}
usort($courseitems, function($a, $b) {
    return strcmp($a["name"], $b["name"]);
});

$reportcarditems = [];
if (has_capability("koperedashboard/reportcard:view", $context)) {
    foreach ($courseitems as $courseitem) {
        $reportcarditems[] = [
            "name" => $courseitem["name"],
            "url" => new moodle_url("/local/kopere_dashboard/plugins/reportcard/my.php", [
                "userid" => $userid,
                "courseid" => $courseitem["id"],
            ]),
        ];
    }
}

$contractitems = [];
if ($tableexists("koperedashboard_contract_accept")) {
    $sql = "SELECT a.id, a.contractid, a.courseid, a.timeaccepted, a.readseconds,
                   c.name AS contractname,
                   cr.fullname AS coursename
              FROM {koperedashboard_contract_accept} a
              JOIN {koperedashboard_contracts} c ON c.id = a.contractid
              JOIN {course} cr ON cr.id = a.courseid
             WHERE a.userid = :userid
          ORDER BY a.timeaccepted DESC";
    $rows = $DB->get_records_sql($sql, ["userid" => $userid], 0, 100);
    foreach ($rows as $row) {
        $contractitems[] = [
            "contract" => $row->contractname,
            "course" => format_string($row->coursename),
            "timeaccepted" => !empty($row->timeaccepted) ? userdate::format($row->timeaccepted) : "-",
            "readseconds" => $row->readseconds,
        ];
    }
}

$planitems = [];
$installmentspending = 0;
$activeplans = 0;
if ($tableexists("koperedashboard_rpay_plan_user")) {
    $sql = "SELECT pu.id, pu.planid, pu.dueday, pu.status,
                   p.name AS planname, p.installments, p.amount
              FROM {koperedashboard_rpay_plan_user} pu
              JOIN {koperedashboard_rpay_plan} p ON p.id = pu.planid
             WHERE pu.userid = :userid
          ORDER BY pu.timemodified DESC";
    $rows = $DB->get_records_sql($sql, ["userid" => $userid], 0, 50);
    foreach ($rows as $row) {
        if ($row->status == "active") {
            $activeplans++;
        }

        $pending = 0;
        $paid = 0;
        $overdue = 0;
        $nextdue = null;

        if ($tableexists("koperedashboard_rpay_installment")) {
            $sql2 = "SELECT id, status, duedate
                       FROM {koperedashboard_rpay_installment}
                      WHERE planuserid = :puid AND excluded = 0
                   ORDER BY duedate ASC";
            $inst = $DB->get_records_sql($sql2, ["puid" => $row->id], 0, 500);

            $now = time();
            foreach ($inst as $i) {
                if ($i->status == "paid") {
                    $paid++;
                    continue;
                }

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

        $installmentspending += $pending;

        $planitems[] = [
            "plan" => $row->planname,
            "status" => $row->status,
            "dueday" => $row->dueday,
            "installments" => $row->installments,
            "amount" => format_float($row->amount, 2),
            "pending" => $pending,
            "paid" => $paid,
            "overdue" => $overdue,
            "nextdue" => $nextdue ? userdate::format($nextdue) : "-",
        ];
    }
}

$requestitems = [];
$openrequests = 0;
if ($tableexists("koperedashboard_req_request")) {
    $sql = "SELECT r.id, r.subject, r.status, r.timecreated,
                   cat.name AS categoryname,
                   cr.fullname AS coursename
              FROM {koperedashboard_req_request} r
              JOIN {koperedashboard_req_category} cat ON cat.id = r.categoryid
         LEFT JOIN {course} cr ON cr.id = r.courseid
             WHERE r.userid = :userid
          ORDER BY r.timemodified DESC";
    $rows = $DB->get_records_sql($sql, ["userid" => $userid], 0, 20);
    foreach ($rows as $row) {
        if ($row->status == "open") {
            $openrequests++;
        }
        $requestitems[] = [
            "id" => $row->id,
            "subject" => $row->subject,
            "status" => $row->status,
            "category" => $row->categoryname,
            "course" => !empty($row->coursename) ? format_string($row->coursename) : "-",
            "timecreated" => userdate::format($row->timecreated),
            "url" => new moodle_url("/local/kopere_dashboard/plugins/requests/view.php", ["id" => $row->id]),
        ];
    }
}

$templatecontext += [
    "cohorts" => array_values(array_map(function($c) {
        return ["name" => $c->name];
    }, $cohorts)),
    "hascohorts" => !empty($cohorts),

    "courses" => $courseitems,
    "hascourses" => !empty($courseitems),

    "reportcards" => $reportcarditems,
    "hasreportcards" => !empty($reportcarditems),

    "contracts" => $contractitems,
    "hascontracts" => !empty($contractitems),

    "plans" => $planitems,
    "hasplans" => !empty($planitems),

    "requests" => $requestitems,
    "hasrequests" => !empty($requestitems),
    "metrics" => [
        [
            "label" => get_string("metric_courses", "koperedashboard_users"),
            "value" => count($courseitems),
        ],
        [
            "label" => get_string("metric_contracts", "koperedashboard_users"),
            "value" => count($contractitems),
        ],
        [
            "label" => get_string("metric_plans", "koperedashboard_users"),
            "value" => $activeplans,
        ],
        [
            "label" => get_string("metric_installments_pending", "koperedashboard_users"),
            "value" => $installmentspending,
        ],
        [
            "label" => get_string("metric_requests_open", "koperedashboard_users"),
            "value" => $openrequests,
        ],
    ],
];

$content = $OUTPUT->render_from_template("koperedashboard_users/view", $templatecontext);
layout::page_render($context, $content, true);
