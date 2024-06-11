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
 * User: Eduardo Kraus
 * Date: 11/07/17
 * Time: 11:13
 */

namespace local_kopere_dashboard\util;

use course_enrolment_manager;

/**
 * Class enroll_util
 *
 * @package local_kopere_dashboard\util
 */
class enroll_util {

    /**
     * @param $course
     *
     * @return bool
     * @throws \dml_exception
     */
    public static function status_enrol_manual($course) {
        global $DB;

        if ($course->isCoorte) {
            return true;
        }

        // Evita erro.
        $context = \context_course::instance($course->id, IGNORE_MISSING);
        if ($context == null) {
            mensagem::print_danger('"context_course::instance" not found');
            return false;
        }

        $enrol = $DB->get_record('enrol', ['courseid' => $course->id, 'enrol' => 'manual'], '*', IGNORE_MULTIPLE);
        if ($enrol == null) {
            return false;
        }
        return !$enrol->status;
    }

    /**
     * @param $course
     * @param $user
     *
     * @return bool
     * @throws \dml_exception
     */
    public static function enrolled($course, $user) {
        global $DB;

        // Evita erro.
        $context = \context_course::instance($course->id, IGNORE_MISSING);
        if ($context == null) {
            return false;
        }

        $enrol = $DB->get_record('enrol',
            ['courseid' => $course->id, 'enrol' => 'manual'], '*', IGNORE_MULTIPLE);
        if ($enrol == null) {
            return false;
        }

        $testroleassignments = $DB->get_record('role_assignments',
            ['roleid' => 5, 'contextid' => $context->id, 'userid' => $user->id], '*', IGNORE_MULTIPLE);
        if ($testroleassignments == null) {
            return false;
        }

        $userenrolments = $DB->get_record('user_enrolments',
            ['enrolid' => $enrol->id, 'userid' => $user->id], '*', IGNORE_MULTIPLE);
        if ($userenrolments != null) {
            return !$userenrolments->status;
        } else {
            return false;
        }
    }

    /**
     * @param $coorteid
     * @param $userid
     *
     * @throws \dml_exception
     */
    public static function cohort_enrol($coorteid, $userid) {
        global $DB;

        $coorteid = str_replace("c", "", $coorteid);

        $cohortmembers = new \stdClass();
        $cohortmembers->cohortid = $coorteid;
        $cohortmembers->userid = $userid;
        $cohortmembers->timeadded = time();

        $DB->insert_record('cohort_members', $cohortmembers);
    }

    /**
     * @param $coorteid
     * @param $userid
     *
     * @throws \dml_exception
     */
    public static function cohort_unenrol($coorteid, $userid) {
        global $DB;

        $coorteid = str_replace("c", "", $coorteid);
        $cohortmembers = [
            "cohortid" => $coorteid,
            "userid" => $userid
        ];
        $DB->delete_records('cohort_members', $cohortmembers);
    }

    /**
     * @param $course
     * @param $user
     * @param $timestart
     * @param $timeend
     * @param $status
     * @param int $roleid
     *      1 => manager
     *      2 => coursecreator
     *      3 => editingteacher
     *      4 => teacher
     *      5 => student
     *      6 => guest
     *      7 => user
     *      8 => frontpage
     *
     * @return bool
     *
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function enrol($course, $user, $timestart, $timeend, $status, $roleid = 5) {
        global $DB, $PAGE, $CFG;

        $enrol = $DB->get_record('enrol', ['courseid' => $course->id, 'enrol' => 'manual']);
        if (!$enrol) {
            return false;
        }

        require_once("{$CFG->dirroot}/enrol/locallib.php");
        $manager = new course_enrolment_manager($PAGE, $course);
        $instances = $manager->get_enrolment_instances();
        $plugins = $manager->get_enrolment_plugins(true); // Do not allow actions on disabled plugins.
        if (!array_key_exists($enrol->id, $instances)) {
            return false;
            // throw new Exception('invalidenrolinstance');
        }
        $instance = $instances[$enrol->id];
        if (!isset($plugins[$instance->enrol])) {
            return false;
            //throw new Exception('enrolnotpermitted');
        }

        //$context = context_course::instance($course->id, MUST_EXIST);

        /** @var \enrol_manual_plugin $plugin */
        $plugin = $plugins[$instance->enrol];
        if ($plugin->allow_enrol($instance)) {
            $roleid = 5;
            $timestart = time();
            $timeend = 0;
            $recovergrades = 0;
            $plugin->enrol_user($instance, $user->id, $roleid, $timestart, $timeend, null, $recovergrades);
        } else {
            return false;
        }
    }

    /**
     * @param $course
     * @param $user
     *
     * @return bool
     * @throws \dml_exception
     */
    public static function unenrol($course, $user) {
        global $DB, $PAGE, $CFG;

        $enrol = $DB->get_record('enrol', ['courseid' => $course->id, 'enrol' => 'manual']);
        if (!$enrol) {
            return false;
        }

        $userenrolment = $DB->get_record('user_enrolments', ['userid' => $user->id, 'enrolid' => $enrol->id]);

        require_once("{$CFG->dirroot}/enrol/locallib.php");
        $manager = new course_enrolment_manager($PAGE, $course);
        return $manager->unenrol_user($userenrolment);
    }
}
