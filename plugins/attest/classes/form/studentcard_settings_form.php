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
 * Form used to edit the front and back student card Mustache templates.
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest\form;

use moodleform;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once("{$CFG->libdir}/formslib.php");

/**
 * Student card template settings form.
 */
class studentcard_settings_form extends moodleform {
    /**
     * Define the form fields.
     *
     * @return void
     * @throws \coding_exception
     */
    public function definition(): void {
        $mform = $this->_form;

        $mform->addElement(
            "header",
            "frontheader",
            get_string("studentcard_settings_front", "koperedashboard_attest")
        );
        $mform->setExpanded("frontheader");

        $mform->addElement(
            "textarea",
            "studentcard_front_mustache",
            get_string("studentcard_settings_front_template", "koperedashboard_attest"),
            [
                "rows" => 28,
                "cols" => 120,
                "class" => "w-100 font-monospace",
                "spellcheck" => "false",
            ]
        );
        $mform->setType("studentcard_front_mustache", PARAM_RAW);
        $mform->addRule("studentcard_front_mustache", null, "required", null, "client");
        $mform->addHelpButton(
            "studentcard_front_mustache",
            "studentcard_settings_front_template",
            "koperedashboard_attest"
        );

        $mform->addElement(
            "header",
            "backheader",
            get_string("studentcard_settings_back", "koperedashboard_attest")
        );
        $mform->setExpanded("backheader");

        $mform->addElement(
            "textarea",
            "studentcard_back_mustache",
            get_string("studentcard_settings_back_template", "koperedashboard_attest"),
            [
                "rows" => 28,
                "cols" => 120,
                "class" => "w-100 font-monospace",
                "spellcheck" => "false",
            ]
        );
        $mform->setType("studentcard_back_mustache", PARAM_RAW);
        $mform->addRule("studentcard_back_mustache", null, "required", null, "client");
        $mform->addHelpButton(
            "studentcard_back_mustache",
            "studentcard_settings_back_template",
            "koperedashboard_attest"
        );

        $this->add_action_buttons(true, get_string("savechanges"));
    }
}
