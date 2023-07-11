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

        $enrol = $DB->get_record('enrol',
            array(
                'courseid' => $course->id,
                'enrol' => 'manual'
            ), '*', IGNORE_MULTIPLE);
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
            array(
                'courseid' => $course->id,
                'enrol' => 'manual'
            ), '*', IGNORE_MULTIPLE);
        if ($enrol == null) {
            return false;
        }

        $testroleassignments = $DB->get_record('role_assignments',
            array(
                'roleid' => 5,
                'contextid' => $context->id,
                'userid' => $user->id
            ), '*', IGNORE_MULTIPLE);
        if ($testroleassignments == null) {
            return false;
        }

        $userenrolments = $DB->get_record('user_enrolments',
            array(
                'enrolid' => $enrol->id,
                'userid' => $user->id
            ), '*', IGNORE_MULTIPLE);
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
        $cohortmembers = array(
            "cohortid" => $coorteid,
            "userid" => $userid
        );
        $DB->delete_records('cohort_members', $cohortmembers);
    }

    /**
     * @param $course
     * @param $user
     * @param $timestart
     * @param $timeend
     * @param $status
     * @param $roleid
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
     * @throws \dml_exception
     */
    public static function enrol($course, $user, $timestart, $timeend, $status, $roleid = 5) {
        global $DB, $USER;

        // Evita erro.
        $context = \context_course::instance($course->id, IGNORE_MISSING);
        if ($context == null) {
            return false;
        }

        /** @var \stdClass $enrol */
        $enrol = $DB->get_record('enrol',
            array(
                'courseid' => $course->id,
                'enrol' => 'manual'
            ));
        if ($enrol == null) {
            return false;
        }

        $testroleassignments = $DB->get_record('role_assignments',
            array(
                'roleid' => $roleid,
                'contextid' => $context->id,
                'userid' => $user->id
            ));
        if ($testroleassignments == null) {
            $roleassignments = new \stdClass();
            $roleassignments->roleid = $roleid;
            $roleassignments->contextid = $context->id;
            $roleassignments->userid = $user->id;
            $roleassignments->timemodified = time();

            $DB->insert_record('role_assignments', $roleassignments);
        }

        if ($USER && isset($USER->id) && $USER->id > 1) {
            $admin = $USER;
        } else {
            $admin = get_admin();
        }

        $userenrolments = $DB->get_record('user_enrolments',
            array(
                'enrolid' => $enrol->id,
                'userid' => $user->id
            ));
        if ($userenrolments != null) {
            $userenrolments->status = $status;
            $userenrolments->timestart = $timestart;
            $userenrolments->timeend = $timeend;
            $userenrolments->modifierid = $admin->id;
            $userenrolments->timemodified = time();

            $DB->update_record('user_enrolments', $userenrolments);

            return false;
        } else {
            $userenrolments = new \stdClass();
            $userenrolments->status = $status;
            $userenrolments->enrolid = $enrol->id;
            $userenrolments->userid = $user->id;
            $userenrolments->timestart = $timestart;
            $userenrolments->timeend = $timeend;
            $userenrolments->modifierid = $admin->id;
            $userenrolments->timecreated = time();
            $userenrolments->timemodified = time();

            $DB->insert_record('user_enrolments', $userenrolments);

            return true;
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
        global $DB;

        /** @var \stdClass $enrol */
        $enrol = $DB->get_record('enrol',
            array(
                'courseid' => $course->id,
                'enrol' => 'manual'
            ));
        if ($enrol == null) {
            return false;
        }

        $userenrolments = $DB->get_record('user_enrolments',
            array(
                'enrolid' => $enrol->id,
                'userid' => $user->id
            ));
        if ($userenrolments != null) {
            $userenrolments->status = ENROL_INSTANCE_DISABLED;
            $userenrolments->modifierid = get_admin()->id;
            $userenrolments->timemodified = time();

            $DB->update_record('user_enrolments', $userenrolments);
        }

        return true;
    }
}
