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
 * category_form.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_requests\form;

use coding_exception;
use moodleform;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once("{$CFG->libdir}/formslib.php");

/**
 * Form to create or edit request categories.
 */
class category_form extends moodleform {
    /**
     * Define the form fields.
     *
     * @return void
     * @throws coding_exception
     */
    public function definition(): void {
        $mform = $this->_form;
        $customdata = (array) $this->_customdata;
        $capabilitygroups = !empty($customdata["capabilitygroups"]) ? (array) $customdata["capabilitygroups"] : [];

        $mform->addElement("hidden", "id", 0);
        $mform->setType("id", PARAM_INT);

        $mform->addElement("text", "name", get_string("category_name", "koperedashboard_requests"), ["size" => 70]);
        $mform->setType("name", PARAM_TEXT);
        $mform->addRule("name", null, "required", null, "client");

        $mform->addElement(
            "textarea", "description", get_string("category_desc", "koperedashboard_requests"),
            ["rows" => 3, "cols" => 80]
        );
        $mform->setType("description", PARAM_TEXT);

        $mform->addElement("selectyesno", "allowteacher", get_string("category_allowteacher", "koperedashboard_requests"));
        $mform->setType("allowteacher", PARAM_INT);

        $mform->addElement(
            "selectgroups",
            "responsiblecaps",
            get_string("category_responsiblecaps", "koperedashboard_requests"),
            $capabilitygroups,
            ["multiple" => "multiple", "size" => 10]
        );
        $mform->setType("responsiblecaps", PARAM_RAW_TRIMMED);
        $mform->addHelpButton("responsiblecaps", "category_responsiblecaps", "koperedashboard_requests");

        $mform->addElement("selectyesno", "active", get_string("category_active", "koperedashboard_requests"));
        $mform->setType("active", PARAM_INT);

        $this->add_action_buttons(true, get_string("save", "koperedashboard_requests"));
    }
}
