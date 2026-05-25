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
 * request_form.php
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
 * Class request_form
 */
class request_form extends moodleform {
    /**
     * Function definition
     *
     * @return void
     * @throws coding_exception
     */
    public function definition(): void {
        $mform = $this->_form;
        $customdata = (array) $this->_customdata;

        $categories = !empty($customdata["categories"]) ? (array) $customdata["categories"] : [];
        $courses = !empty($customdata["courses"]) ? (array) $customdata["courses"] : [];

        $mform->addElement("text", "subject", get_string("request_subject", "koperedashboard_requests"), ["size" => 70]);
        $mform->setType("subject", PARAM_TEXT);
        $mform->addRule("subject", null, "required", null, "client");

        $mform->addElement("select", "categoryid", get_string("request_category", "koperedashboard_requests"), $categories);
        $mform->setType("categoryid", PARAM_INT);
        $mform->addRule("categoryid", null, "required", null, "client");

        $mform->addElement("select", "courseid", get_string("request_course", "koperedashboard_requests"), $courses);
        $mform->setType("courseid", PARAM_INT);

        $mform->addElement("editor", "message_editor", get_string("request_message", "koperedashboard_requests"), null, [
            "maxfiles" => 0,
            "trusttext" => 0,
        ]);
        $mform->setType("message_editor", PARAM_RAW);

        $options = [
            "subdirs" => 0,
            "maxfiles" => 10,
            "accepted_types" => "*",
        ];
        $mform->addElement(
            "filemanager", "attachments_filemanager", get_string("request_attachments", "koperedashboard_requests"), null, $options
        );
        $mform->setType("attachments_filemanager", PARAM_INT);

        $this->add_action_buttons(true, get_string("save", "koperedashboard_requests"));
    }
}
