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
 * manage.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/contracts:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("manage_title", "koperedashboard_contracts"));

$records = $DB->get_records("koperedashboard_contracts", null, "id DESC", "id,name,active,timemodified");

$rows = [];
foreach ($records as $c) {
    $rows[] = [
        "id" => $c->id,
        "name" => format_string($c->name),
        "active" => !empty($c->active),
        "editurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/edit.php", ["id" => $c->id]),
        "deleteurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/delete.php", ["id" => $c->id]),
        "modified" => userdate::format($c->timemodified),
        "signaturesurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/signatures.php", ["contractid" => $c->id]),
    ];
}

$data = [
    "newurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/edit.php"),
    "signaturesurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/signatures.php"),
    "rows" => $rows,
];

$content = $OUTPUT->render_from_template("koperedashboard_contracts/manage", $data);
layout::page_render($context, $content, true);
