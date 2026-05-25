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
 * online.php
 *
 * @package   koperedashboard_users
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_users\service\user_service;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/users:view", $context);

$minutes = optional_param("minutes", 10, PARAM_INT);
$minutes = max(1, min(1440, $minutes));

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/users/online.php", ["minutes" => $minutes]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("online_page_title", "koperedashboard_users"));

$records = user_service::get_online_users($minutes, 500, 0);

$templatecontext = [
    "formurl" => new moodle_url("/local/kopere_dashboard/plugins/users/online.php"),
    "title" => get_string("online_title", "koperedashboard_users"),
    "help" => get_string("online_help", "koperedashboard_users"),
    "countonline" => user_service::count_online_users($minutes),
    "minutesoptions" => [],
    "results" => [],
    "hasresults" => !empty($records),
    "noresults" => get_string("online_empty", "koperedashboard_users"),
];

foreach ([5, 10, 15, 30, 60, 120] as $option) {
    $templatecontext["minutesoptions"][] = [
        "value" => $option,
        "label" => $option . " min",
        "selected" => ($minutes == $option),
    ];
}

foreach ($records as $user) {
    $userinfo = user_service::get_user($user->id);

    $userpicture = new user_picture($userinfo);
    $userpicture->size = 40;

    $templatecontext["results"][] = [
        "id" => $userinfo->id,
        "name" => fullname($userinfo),
        "username" => $userinfo->username,
        "email" => $userinfo->email,
        "lastaccess" => !empty($userinfo->lastaccess) ? userdate::format($userinfo->lastaccess) : "-",
        "suspended" => !empty($userinfo->suspended),
        "picture" => $userpicture->get_url($PAGE),
        "viewurl" => new moodle_url("/local/kopere_dashboard/plugins/users/view.php", ["id" => $userinfo->id]),
        "profileurl" => new moodle_url("/user/profile.php", ["id" => $userinfo->id]),
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_users/online", $templatecontext);
layout::page_render($context, $content, true);
