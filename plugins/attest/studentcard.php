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
 * Student card
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\helper\studentcard_helper;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

require_login();

$userid = optional_param("userid", $USER->id, PARAM_INT);

$user = studentcard_helper::get_target_user($userid);

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url("/local/kopere_dashboard/plugins/attest/studentcard.php", ["userid" => $userid]);
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string("studentcard", "koperedashboard_attest"));

$contextmustache = [
    "hasphoto" => studentcard_helper::get_user_photo_tempfile($user),
];
if ($contextmustache["hasphoto"]) {
    $contextmustache += [
        'fullname' => fullname($user),
        'email' => $user->email,
        'cpf' => studentcard_helper::get_user_cpf($user),
        'generateurl' => new moodle_url(
            '/local/kopere_dashboard/plugins/attest/studentcard_pdf.php',
            ['userid' => $user->id]
        ),
        'generatelabel' => get_string('studentcardgenerate', 'koperedashboard_attest'),
    ];
} else {
    $contextmustache += [
        'message' => get_string('studentcardnophoto', 'koperedashboard_attest'),
        'editurl' => new moodle_url('/user/editadvanced.php', ['id' => $user->id]),
        'editlabel' => get_string('editmyprofile'),
    ];
}

$content = $OUTPUT->render_from_template('koperedashboard_attest/studentcard', $contextmustache);
layout::page_render($context, $content, true);
