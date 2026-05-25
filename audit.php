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
 * audit.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\audit\manager;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../config.php");

$context = context_system::instance();
require_login();
require_capability("local/kopere_dashboard:viewaudit", $context);

$q = optional_param("q", "", PARAM_TEXT);
$component = optional_param("component", "", PARAM_ALPHANUMEXT);
$userid = optional_param("userid", 0, PARAM_INT);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/audit.php", ["q" => $q, "component" => $component, "userid" => $userid]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("audit", "local_kopere_dashboard"));

$filters = [
    "q" => $q,
    "component" => $component,
    "userid" => $userid ?: null,
];

$rows = manager::search($filters, 300);

$data = [
    "q" => $q,
    "component" => $component,
    "userid" => $userid,
    "rows" => [],
];

foreach ($rows as $r) {
    $userlabel = "-";
    if (!empty($r->userid)) {
        $u = core_user::get_user($r->userid, "*", MUST_EXIST);
        $userlabel = fullname($u) . " (#{$u->id})";
    }

    $data["rows"][] = [
        "time" => userdate::format($r->timecreated),
        "component" => $r->component,
        "action" => $r->action,
        "user" => $userlabel,
        "description" => format_text($r->description, FORMAT_PLAIN),
    ];
}

$content = $OUTPUT->render_from_template("local_kopere_dashboard/audit", $data);
layout::page_render($context, $content, true);
