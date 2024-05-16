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
 * @created    16/05/17 04:10
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\util\json;
use local_kopere_dashboard\util\user_util;

/**
 * Class enroll
 * @package local_kopere_dashboard
 */
class enroll {
    /**
     * @return array
     * @throws \dml_exception
     */
    public function last_enroll() {
        global $DB;

        return $DB->get_records_sql("
               SELECT DISTINCT ue.id, ra.roleid, e.courseid, c.fullname,
                               ue.userid, ue.timemodified, ue.timeend, ue.status, e.enrol
                 FROM {user_enrolments}  ue
                 JOIN {role_assignments} ra  ON ue.userid = ra.userid
                 JOIN {enrol}            e   ON e.id = ue.enrolid
                 JOIN {context}          ctx ON ctx.instanceid = e.courseid
                 JOIN {course}           c   ON c.id = e.courseid
                WHERE ra.contextid = ctx.id
             --  GROUP BY e.courseid, ue.userid
             ORDER BY ue.timemodified DESC
                LIMIT 10");
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function ajax_dashboard() {
        global $DB;

        $courseid = optional_param('courseid', 0, PARAM_INT);

        $sql
            = "SELECT DISTINCT ue.userid AS id, firstname, lastname, u.email, ue.status
		         FROM {user_enrolments} ue
                    LEFT JOIN {user} u ON u.id = ue.userid
                    LEFT JOIN {enrol} e ON e.id = ue.enrolid
                    LEFT JOIN {course} c ON c.id = e.courseid
                    LEFT JOIN {course_completions} cc ON cc.timecompleted > 0
                                                     AND cc.course = e.courseid
                                                     AND cc.userid = ue.userid
		        WHERE c.id = :id AND u.id IS NOT NULL
		     ";

        $result = $DB->get_records_sql($sql, ['id' => $courseid]);

        $result = user_util::column_fullname($result, 'nome');

        json::encode($result);
    }
}
