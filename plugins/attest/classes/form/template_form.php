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
 * template_form.php
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest\form;

use local_kopere_dashboard\form\categorycourse_select_element;
use moodleform;
use MoodleQuickForm;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once("{$CFG->libdir}/formslib.php");

/**
 * Form to create or edit attestation templates.
 */
class template_form extends moodleform {
    /**
     * Define the form fields.
     *
     * @return void
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public function definition(): void {
        global $CFG;

        $mform = $this->_form;

        MoodleQuickForm::registerElementType(
            "categorycourse_select",
            "{$CFG->dirroot}/local/kopere_dashboard/classes/form/categorycourse_select_element.php",
            "\\local_kopere_dashboard\\form\\categorycourse_select_element"
        );

        $mform->addElement("hidden", "id", 0);
        $mform->setType("id", PARAM_INT);

        $mform->addElement("text", "name", get_string("field_name", "koperedashboard_attest"), ["size" => 70]);
        $mform->setType("name", PARAM_TEXT);
        $mform->addRule("name", null, "required", null, "client");

        $mform->addElement("text", "validmonths", get_string("field_validmonths", "koperedashboard_attest"), ["size" => 10]);
        $mform->setType("validmonths", PARAM_INT);
        $mform->addRule("validmonths", null, "required", null, "client");
        $mform->addRule("validmonths", null, "numeric", null, "client");

        $mform->addElement("selectyesno", "allcourses", get_string("field_allcourses", "koperedashboard_attest"));
        $mform->setType("allcourses", PARAM_INT);

        $options = categorycourse_select_element::get_course_options();
        $mform->addElement(
            "categorycourse_select",
            "courseids",
            get_string("field_courses", "koperedashboard_attest"),
            $options,
            ["multiple" => "multiple", "size" => 10]
        );
        $mform->setType("courseids", PARAM_INT);
        $mform->addHelpButton("courseids", "field_courses", "koperedashboard_attest");
        $mform->disabledIf("courseids", "allcourses", "checked");

        $mform->addElement("editor", "html", get_string("field_html", "koperedashboard_attest"), ["rows" => 18, "cols" => 80]);
        $mform->setType("html", PARAM_RAW);
        $mform->addRule("html", null, "required", null, "client");
        $mform->addHelpButton("html", "field_html", "koperedashboard_attest");

        $mform->addElement("selectyesno", "active", get_string("field_active", "koperedashboard_attest"));
        $mform->setType("active", PARAM_INT);

        $this->add_action_buttons(true, get_string("savechanges"));
    }

    /**
     * Validate submitted data.
     *
     * @param array $data Submitted data.
     * @param array $files Submitted files.
     * @return array
     * @throws \coding_exception
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        if ($data["validmonths"] < 1) {
            $errors["validmonths"] = get_string("err_positive", "form");
        }

        if (empty($data["allcourses"]) && empty($data["courseids"])) {
            $errors["courseids"] = get_string("required");
        }

        return $errors;
    }
}
