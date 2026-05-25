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

$q = optional_param("q", "", PARAM_RAW_TRIMMED);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/users/", ["q" => $q]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("page_title", "koperedashboard_users"));

$PAGE->requires->strings_for_js(["search_help", "no_results"], "koperedashboard_users");
$PAGE->requires->js_call_amd("koperedashboard_users/search", "init", []);

$results = user_service::search_users($q, 30, 0);

$templatecontext = [
    "q" => $q,
    "placeholder" => get_string("search_placeholder", "koperedashboard_users"),
    "help" => get_string("search_help", "koperedashboard_users"),
    "results" => [],
    "hasresults" => !empty($results),
    "emptytitle" => get_string("empty_query_title", "koperedashboard_users"),
    "noresults" => get_string("no_results", "koperedashboard_users"),
];

foreach ($results as $user) {
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

$content = $OUTPUT->render_from_template("koperedashboard_users/index", $templatecontext);
layout::page_render($context, $content, true);
