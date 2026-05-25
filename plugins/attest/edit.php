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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\audit\logger;
use koperedashboard_attest\form\template_form;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\service\placeholders;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/attest:manage", $context);

$id = optional_param("id", 0, PARAM_INT);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/attest/edit.php", ["id" => $id]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("edit_template", "koperedashboard_attest"));

$tpl = null;
$selectedcourses = [];

if ($id) {
    $tpl = $DB->get_record("koperedashboard_attest_tpl", ["id" => $id], "*", MUST_EXIST);
    $map = $DB->get_records("koperedashboard_attest_tpl_course", ["tplid" => $id], "", "courseid");
    foreach ($map as $m) {
        $selectedcourses[$m->courseid] = $m->courseid;
    }
}

$url = new moodle_url("/local/kopere_dashboard/plugins/attest/edit.php", ["id" => $id]);
$form = new template_form($url);

if ($tpl) {
    $form->set_data(
        (object) [
            "id" => $id,
            "name" => $tpl->name,
            "html" => [
                "text" => $tpl->html,
                "format" => 1,
            ],
            "validmonths" => $tpl->validmonths,
            "allcourses" => !empty($tpl->allcourses),
            "active" => !empty($tpl->active),
            "courseids" => array_values($selectedcourses),
        ]
    );
} else {
    $form->set_data(
        (object) [
            "id" => $id,
            "html" => [
                "text" => "<html><body><h1>Attestation</h1><p>{{user_fullname}}</p></body></html>",
                "format" => 1,
            ],
            "validmonths" => 12,
            "active" => 1,
            "courseids" => [],
        ]
    );
}

if ($form->is_cancelled()) {
    redirect(new moodle_url("/local/kopere_dashboard/plugins/attest/manage.php"));
}

if ($data = $form->get_data()) {
    $now = time();
    $courseids = !empty($data->courseids) ? array_map("intval", (array) $data->courseids) : [];

    if ($id) {
        $tpl->name = $data->name;
        $tpl->html = $data->html["text"];
        $tpl->validmonths = max(1, $data->validmonths);
        $tpl->allcourses = !empty($data->allcourses) ? 1 : 0;
        $tpl->active = !empty($data->active) ? 1 : 0;
        $tpl->timemodified = $now;

        $DB->update_record("koperedashboard_attest_tpl", $tpl);
        $DB->delete_records("koperedashboard_attest_tpl_course", ["tplid" => $id]);

        if (!$tpl->allcourses) {
            foreach ($courseids as $cid) {
                $data = (object) [
                    "tplid" => $id,
                    "courseid" => $cid,
                ];
                $DB->insert_record("koperedashboard_attest_tpl_course", $data);
            }
        }

        logger::template_updated($id);
    } else {
        global $USER;

        $tpl = (object) [
            "name" => $data->name,
            "html" => $data->html["text"],
            "validmonths" => max(1, $data->validmonths),
            "allcourses" => !empty($data->allcourses) ? 1 : 0,
            "active" => !empty($data->active) ? 1 : 0,
            "timecreated" => $now,
            "timemodified" => $now,
            "createdby" => $USER->id,
        ];

        $id = $DB->insert_record("koperedashboard_attest_tpl", $tpl);

        if (!$tpl->allcourses) {
            foreach ($courseids as $cid) {
                $data = (object) [
                    "tplid" => $id,
                    "courseid" => $cid,
                ];
                $DB->insert_record("koperedashboard_attest_tpl_course", $data);
            }
        }

        logger::template_created($id);
    }

    redirect(new moodle_url("/local/kopere_dashboard/plugins/attest/manage.php"));
}

$placeholders = placeholders::catalog();

$content = $OUTPUT->render_from_template("koperedashboard_attest/edit", [
    "formhtml" => $form->render(),
    "placeholders" => $placeholders,
]);
layout::page_render($context, $content, true);
