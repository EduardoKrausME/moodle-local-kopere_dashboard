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
 * permission_form.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\form;

use core\exception\moodle_exception;
use moodleform;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once("{$CFG->libdir}/formslib.php");

/**
 * Class permission_form
 */
class permission_form extends moodleform {

    /**
     * Function definition
     *
     * @return void
     * @throws moodle_exception
     */
    public function definition(): void {
        global $OUTPUT;

        $mform = $this->_form;

        $mform->addElement("hidden", "actionform");
        $mform->setType("actionform", PARAM_TEXT);

        $mform->addElement("hidden", "capability");
        $mform->setType("capability", PARAM_RAW_TRIMMED);

        $capability = $this->_customdata["capability"];
        $roleid = $this->_customdata["roleid"];
        $contextid = $this->_customdata["contextid"];

        $data = [
            "roleid" => $roleid,
            "contextid" => $contextid,
        ];

        $templatecontext = [
            "existinguserselector" => (new permission_existing_selector("removeselect", $data))->display(true),
            "potentialuserselector" => (new permission_candidate_selector("addselect", $data))->display(true),
            "roleid" => $roleid,
            "contextid" => $contextid,
            "capability" => $capability,
        ];

        $html = $OUTPUT->render_from_template("local_kopere_dashboard/permissions-users", $templatecontext);
        $mform->addElement("html", $html);
    }
}
