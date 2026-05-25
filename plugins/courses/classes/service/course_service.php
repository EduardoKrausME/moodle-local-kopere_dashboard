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
 * course_service.php
 *
 * @package   koperedashboard_courses
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_courses\service;

use dml_exception;
use stdClass;

/**
 * Class course_service
 */
class course_service {

    /**
     * Search courses by fullname, shortname or idnumber.
     *
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array<int,stdClass>
     * @throws dml_exception
     */
    public static function search_courses(string $query, int $limit = 25, int $offset = 0): array {
        global $DB;

        $query = trim($query);
        if ($query == "") {
            return self::get_recent_courses($limit);
        }

        $params = [];
        $conditions = [
            "c.id <> 1",
        ];

        $like = "%" . $DB->sql_like_escape($query) . "%";

        $searchcond = "(" .
            $DB->sql_like("c.fullname", ":q1", false) . " OR " .
            $DB->sql_like("c.shortname", ":q2", false) . " OR " .
            $DB->sql_like("c.idnumber", ":q3", false) .
            ")";

        $params += [
            "q1" => $like,
            "q2" => $like,
            "q3" => $like,
        ];

        if (ctype_digit($query)) {
            $searchcond = "(" . $searchcond . " OR c.id = :idexact)";
            $params["idexact"] = $query;
        }

        $conditions[] = $searchcond;

        $sql = "SELECT c.id, c.fullname, c.shortname, c.visible, c.startdate, c.enddate, c.timemodified, c.category,
                       cc.name AS categoryname
                  FROM {course} c
                  JOIN {course_categories} cc ON cc.id = c.category
                 WHERE " . implode(" AND ", $conditions) . "
              ORDER BY c.fullname ASC";

        return $DB->get_records_sql($sql, $params, $offset, $limit);
    }

    /**
     * Get recently modified courses.
     *
     * @param int $limit
     * @return array<int,stdClass>
     * @throws dml_exception
     */
    public static function get_recent_courses(int $limit = 25): array {
        global $DB;

        $sql = "SELECT c.id, c.fullname, c.shortname, c.visible, c.startdate, c.enddate, c.timemodified, c.category,
                       cc.name AS categoryname
                  FROM {course} c
                  JOIN {course_categories} cc ON cc.id = c.category
                 WHERE c.id <> 1
              ORDER BY c.timemodified DESC, c.id DESC";

        return $DB->get_records_sql($sql, [], 0, $limit);
    }

    /**
     * Get course record.
     *
     * @param int $courseid
     * @return stdClass
     * @throws dml_exception
     */
    public static function get_course(int $courseid): stdClass {
        global $DB;
        return $DB->get_record("course", ["id" => $courseid], "*", MUST_EXIST);
    }

    /**
     * Count enrolled active users in course.
     *
     * @param int $courseid
     * @return int
     * @throws dml_exception
     */
    public static function count_enrolled_users(int $courseid): int {
        global $DB;

        $sql = "SELECT COUNT(1)
                  FROM {user_enrolments} ue
                  JOIN {enrol} e ON e.id = ue.enrolid
                  JOIN {user} u ON u.id = ue.userid
                 WHERE e.courseid = :courseid
                   AND e.status = 0
                   AND ue.status = 0
                   AND u.deleted = 0";

        return $DB->count_records_sql($sql, ["courseid" => $courseid]);
    }
}
