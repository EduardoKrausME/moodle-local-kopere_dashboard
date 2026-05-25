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
 * edit.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_contracts\audit\logger;
use koperedashboard_contracts\contracts\form\contract_form;
use koperedashboard_contracts\contracts\manager;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$id = optional_param("id", 0, PARAM_INT);

$context = context_system::instance();
require_login();
require_capability("koperedashboard/contracts:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/contracts/edit.php", ["id" => $id]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("editcontract", "koperedashboard_contracts"));

$customdata = [];
$form = new contract_form(null, $customdata);

$record = null;
if ($id) {
    $record = $DB->get_record("koperedashboard_contracts", ["id" => $id], "*", MUST_EXIST);

    $selected = $DB->get_records("koperedashboard_contract_courses", ["contractid" => $id], "", "courseid");
    $courseids = [];
    foreach ($selected as $s) {
        $courseids[] = $s->courseid;
    }

    $draft = (object) [
        "id" => $record->id,
        "name" => $record->name,
        "summary" => $record->summary,
        "active" => $record->active,
        "courseids" => $courseids,
        "content_editor" => [
            "text" => $record->content,
            "format" => $record->contentformat,
            "itemid" => 0,
        ],
    ];

    $form->set_data($draft);
}

if ($form->is_cancelled()) {
    redirect(new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"));
}

if ($data = $form->get_data()) {
    $payload = (object) [
        "id" => !empty($data->id) ? $data->id : 0,
        "name" => $data->name,
        "summary" => $data->summary,
        "content" => $data->content_editor["text"],
        "contentformat" => $data->content_editor["format"],
        "active" => !empty($data->active) ? 1 : 0,
    ];

    $courseids = [];
    if (!empty($data->courseids)) {
        foreach ($data->courseids as $cid) {
            $courseids[] = $cid;
        }
    }

    $newid = manager::upsert_contract($payload, $courseids);

    if (empty($paylokoperedashboard->id)) {
        logger::contract_created($newid);
    } else {
        logger::contract_updated($newid);
    }

    redirect(new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"));
}

$formhtml = $form->render();

$content = $OUTPUT->render_from_template("koperedashboard_contracts/edit", ["formhtml" => $formhtml]);
layout::page_render($context, $content, true);
