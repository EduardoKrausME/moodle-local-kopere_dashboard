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
 * permissions.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\api\subplugin_manager;
use local_kopere_dashboard\form\permission_controller;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../config.php");
require_once("{$CFG->libdir}/adminlib.php");

$context = context_system::instance();

require_login();
require_capability("local/kopere_dashboard:managepermissions", $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url("/local/kopere_dashboard/permissions.php"));
$PAGE->add_body_class("local-kopere_dashboard");

$actionform = optional_param("actionform", "", PARAM_ALPHA);
$capability = optional_param("capability", "", PARAM_RAW_TRIMMED);

$capabilities = subplugin_manager::get_all_capability_definitions();

if ($actionform == "edit" && $capability != "") {
    if (!array_key_exists($capability, $capabilities)) {
        throw new moodle_exception("invalidparameter", "error", "", "capability");
    }

    $controller = new permission_controller();
    $templatecontext = $controller->manage_capability($capability, $capabilities[$capability]);

    $title = get_string("permissions", "local_kopere_dashboard");
    $PAGE->set_title("{$title} / {$templatecontext['name']}");

    $content = $OUTPUT->render_from_template("local_kopere_dashboard/permissions_edit", $templatecontext);
    layout::page_render($context, $content, true);
}

$PAGE->set_title(get_string("permissions", "local_kopere_dashboard"));

$stringmanager = get_string_manager();
$groups = [];

foreach ($capabilities as $cap => $def) {
    $pluginname = get_string("pluginname", "local_kopere_dashboard");
    $component = "local_kopere_dashboard";
    $capabilitylabel = !empty($def["name"]) ? format_string($def["name"]) : $cap;

    if (preg_match("#^kopere_dashboard/([a-z0-9_]+):([a-z0-9_]+)$#", $cap, $matches)) {
        $component = "koperedashboard_{$matches[1]}";
        $pluginname = $stringmanager->string_exists("pluginname", $component)
            ? get_string("pluginname", $component)
            : $matches[1];

        $capkey = "cap_{$matches[2]}";
        if ($stringmanager->string_exists($capkey, $component)) {
            $capabilitylabel = get_string($capkey, $component);
        }
    }

    if (preg_match("#^local/kopere_dashboard:([a-z0-9_]+)$#", $cap, $matches)) {
        $capkey = "cap_{$matches[1]}";
        if ($stringmanager->string_exists($capkey, "local_kopere_dashboard")) {
            $capabilitylabel = get_string($capkey, "local_kopere_dashboard");
        }
    }

    if (!isset($groups[$component])) {
        $groups[$component] = [
            "pluginname" => format_string($pluginname),
            "items" => [],
        ];
    }

    $groups[$component]["items"][] = [
        "capability" => $cap,
        "name" => $capabilitylabel,
        "description" => !empty($def["description"]) ? format_text($def["description"], FORMAT_PLAIN) : "",
        "manageurl" => new moodle_url("/local/kopere_dashboard/permissions.php", [
            "actionform" => "edit",
            "capability" => $cap,
        ]),
    ];
}

ksort($groups);
$templatecontext = [
    "groups" => array_values($groups),
];

$content = $OUTPUT->render_from_template("local_kopere_dashboard/permissions_list", $templatecontext);
layout::page_render($context, $content, true);
