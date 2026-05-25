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
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\api\subplugin_manager;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\report\dashboard;
use local_kopere_dashboard\report\student_dashboard;

require_once(__DIR__ . "/../../config.php");

$menu = optional_param("menu", false, PARAM_TEXT);
$page = optional_param("p", false, PARAM_TEXT);
if ($menu !== false || $page !== false) {
    redirect(new moodle_url("/local/kopere_dashboard/plugins/pages/", ["menu" => $menu, "p" => $page]));
}

$context = context_system::instance();
require_login();

$canviewdashboard = has_capability("local/kopere_dashboard:view", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("dashboard", "local_kopere_dashboard"));

if ($canviewdashboard) {
    $PAGE->requires->strings_for_js(["js_processing", "js_completed"], "local_kopere_dashboard");
    $PAGE->requires->js_call_amd("local_kopere_dashboard/dashboard", "init", []);

    $report = new dashboard();

    $template = "local_kopere_dashboard/dashboard";
    $templatecontext = [
        "kpis" => subplugin_manager::get_home_kpis($context, 8),
        "linejson" => json_encode($report->get_enrollments_series()),
        "donutjson" => json_encode($report->get_contracts_status()),
        "auditrows" => $report->get_latest_audit_rows(),
    ];
} else {
    $report = new student_dashboard();
    $template = "local_kopere_dashboard/student_dashboard";
    $templatecontext = $report->export_for_template($USER->id);
}

$content = $OUTPUT->render_from_template($template, $templatecontext);
layout::page_render($context, $content, false);
