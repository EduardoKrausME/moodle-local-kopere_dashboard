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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\audit\logger;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

require_login();

$context = context_system::instance();
if (!has_capability("koperedashboard/attest:manage", $context)) {
    redirect("/local/kopere_dashboard/plugins/attest/my.php");
}
require_capability("koperedashboard/attest:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/attest/manage.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("manage_title", "koperedashboard_attest"));

$delete = optional_param("delete", 0, PARAM_INT);
if ($delete && confirm_sesskey()) {
    $DB->delete_records("koperedashboard_attest_tpl_course", ["tplid" => $delete]);
    $DB->delete_records("koperedashboard_attest_tpl", ["id" => $delete]);

    logger::template_deleted($delete);

    redirect(new moodle_url("/local/kopere_dashboard/plugins/attest/manage.php"));
}

$rows = [];
$templates = $DB->get_records("koperedashboard_attest_tpl", null, "id DESC");

foreach ($templates as $t) {
    $rows[] = [
        "id" => $t->id,
        "name" => format_string($t->name),
        "active" => !empty($t->active),
        "validmonths" => $t->validmonths,
        "allcourses" => !empty($t->allcourses),
        "editurl" => new moodle_url("/local/kopere_dashboard/plugins/attest/edit.php", ["id" => $t->id]),
        "allissuesurl" => new moodle_url("/local/kopere_dashboard/plugins/attest/all.php", ["tplid" => $t->id]),
        "delurl" => new moodle_url(
            "/local/kopere_dashboard/plugins/attest/manage.php",
            ["delete" => $t->id, "sesskey" => sesskey()]
        ),
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_attest/manage", [
    "rows" => $rows,
    "newurl" => new moodle_url("/local/kopere_dashboard/plugins/attest/edit.php"),
]);
layout::page_render($context, $content, true);
