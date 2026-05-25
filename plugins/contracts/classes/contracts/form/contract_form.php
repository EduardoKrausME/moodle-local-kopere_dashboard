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
 * contract_form.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_contracts\contracts\form;

use coding_exception;
use dml_exception;
use local_kopere_dashboard\form\categorycourse_select_element;
use moodleform;
use MoodleQuickForm;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once("{$CFG->libdir}/formslib.php");

/**
 * Class contract_form
 */
class contract_form extends moodleform {
    /**
     * Function definition
     *
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public function definition(): void {
        global $CFG;

        $mform = $this->_form;

        MoodleQuickForm::registerElementType(
            "categorycourse_select",
            "{$CFG->dirroot}/local/kopere_dashboard/classes/form/categorycourse_select_element.php",
            "\\local_kopere_dashboard\\form\\categorycourse_select_element"
        );

        $mform->addElement("hidden", "id", "0");
        $mform->setType("id", PARAM_INT);

        $mform->addElement("text", "name", get_string("field_name", "koperedashboard_contracts"), ["size" => 70]);
        $mform->setType("name", PARAM_TEXT);
        $mform->addRule("name", null, "required", null, "client");

        $mform->addElement(
            "textarea", "summary",
            get_string("field_summary", "koperedashboard_contracts"),
            "wrap=\"virtual\" rows=\"3\" cols=\"70\""
        );
        $mform->setType("summary", PARAM_TEXT);

        $mform->addElement("editor", "content_editor", get_string("field_content", "koperedashboard_contracts"), null, [
            "maxfiles" => 0,
            "maxbytes" => 0,
            "trusttext" => 0,
        ]);
        $mform->setType("content_editor", PARAM_RAW);

        $mform->addElement(
            "categorycourse_select",
            "courseids",
            get_string("field_courses", "koperedashboard_contracts"),
            categorycourse_select_element::get_course_options()
        );
        $mform->setType("courseids", PARAM_INT);

        $mform->addElement("selectyesno", "active", get_string("field_active", "koperedashboard_contracts"));
        $mform->setType("active", PARAM_INT);
        $mform->setDefault("active", 1);

        $this->add_action_buttons(true, get_string("btn_save", "koperedashboard_contracts"));
    }
}
