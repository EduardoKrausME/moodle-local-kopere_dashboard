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
 * categories.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_requests\audit\logger;
use koperedashboard_requests\form\category_form;
use local_kopere_dashboard\api\subplugin_manager;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/requests:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/requests/categories.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);

$editid = optional_param("edit", false, PARAM_INT);

$cats = $DB->get_records("koperedashboard_req_category", null, "name ASC");
$rows = [];
foreach ($cats as $c) {
    $rows[] = [
        "id" => $c->id,
        "name" => format_string($c->name),
        "active" => !empty($c->active),
        "allowteacher" => !empty($c->allowteacher),
        "editurl" => new moodle_url("/local/kopere_dashboard/plugins/requests/categories.php", ["edit" => $c->id]),
    ];
}

$capabilitygroups = [];
$selectedcaps = [];
foreach (subplugin_manager::get_all_capability_definitions() as $capability => $definition) {
    if (!preg_match("/^kopere_dashboard\\/([^:]+):(.+)$/", $capability, $matches)) {
        continue;
    }

    $plugin = $matches[1];
    $component = "koperedashboard_{$plugin}";
    $groupname = get_string_manager()->string_exists("pluginname", $component)
        ? get_string("pluginname", $component)
        : $plugin;

    if (!isset($capabilitygroups[$groupname])) {
        $capabilitygroups[$groupname] = [];
    }

    $capabilitygroups[$groupname][$capability] = !empty($definition["name"])
        ? format_string($definition["name"])
        : $capability;
}

$formhtml = "";
$edit = null;
if ($editid !== false) {
    if ($editid) {
        $c = $DB->get_record("koperedashboard_req_category", ["id" => $editid], "*", MUST_EXIST);
        $selectedcaps = array_values(preg_split("/\\r\\n|\\r|\\n/", $c->responsiblecaps, -1, PREG_SPLIT_NO_EMPTY));
        $edit = [
            "id" => $c->id,
            "name" => $c->name,
            "description" => $c->description,
            "allowteacher" => !empty($c->allowteacher),
            "active" => !empty($c->active),
        ];

        $PAGE->set_title($c->name);
    } else {
        $edit = [
            "id" => 0,
            "active" => 1,
        ];

        $PAGE->set_title(get_string("category_new", "koperedashboard_requests"));
    }

    $form = new category_form(
        new moodle_url("/local/kopere_dashboard/plugins/requests/categories.php", ["edit" => $editid]),
        ["capabilitygroups" => $capabilitygroups]
    );
    $form->set_data(
        (object) [
            "id" => $edit["id"] ?? 0,
            "name" => $edit["name"] ?? "",
            "description" => $edit["description"] ?? "",
            "allowteacher" => !empty($edit["allowteacher"]),
            "responsiblecaps" => $selectedcaps,
            "active" => !empty($edit["active"]),
        ]
    );

    if ($form->is_cancelled()) {
        redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/categories.php"));
    }

    if ($data = $form->get_data()) {
        $now = time();
        $record = (object) [
            "id" => $data->id,
            "name" => $data->name,
            "description" => $data->description,
            "allowteacher" => !empty($data->allowteacher) ? 1 : 0,
            "responsiblecaps" => implode("\n", array_filter((array) ($data->responsiblecaps ?? []))),
            "active" => !empty($data->active) ? 1 : 0,
            "timemodified" => $now,
        ];

        if ($record->id > 0) {
            $DB->update_record("koperedashboard_req_category", $record);
            logger::category_updated($record->id);
        } else {
            $record->timecreated = $now;
            $newid = $DB->insert_record("koperedashboard_req_category", $record);
            logger::category_created($newid);
        }

        redirect(new moodle_url("/local/kopere_dashboard/plugins/requests/categories.php"));
    }

    $formhtml = $form->render();
} else {
    $PAGE->set_title(get_string("categories_title", "koperedashboard_requests"));
}

$content = $OUTPUT->render_from_template("koperedashboard_requests/categories", [
    "rows" => $rows,
    "edit" => $edit,
    "formhtml" => $formhtml,
    "posturl" => new moodle_url("/local/kopere_dashboard/plugins/requests/categories.php"),
]);
layout::page_render($context, $content, true);
