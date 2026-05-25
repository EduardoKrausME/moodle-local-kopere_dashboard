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
 * user_service.php
 *
 * @package   koperedashboard_users
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_users\service;

use core_user;
use dml_exception;
use stdClass;

/**
 * Class user_service
 */
class user_service {

    /**
     * Search users by name, email, username or idnumber.
     *
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array<int,stdClass>
     * @throws dml_exception
     */
    public static function search_users(string $query, int $limit = 25, int $offset = 0): array {
        global $DB;

        $query = trim($query);
        if ($query == "") {
            return self::get_recent_users($limit);
        }

        $params = [];
        $conditions = [
            "u.deleted = 0",
        ];

        $like = "%" . $DB->sql_like_escape($query) . "%";

        $fullname = $DB->sql_concat_join("' '", ["u.firstname", "u.lastname"]);

        $searchcond = "(" .
            $DB->sql_like("u.firstname", ":q1", false) . " OR " .
            $DB->sql_like("u.lastname", ":q2", false) . " OR " .
            $DB->sql_like("u.email", ":q3", false) . " OR " .
            $DB->sql_like("u.username", ":q4", false) . " OR " .
            $DB->sql_like("u.idnumber", ":q5", false) . " OR " .
            $DB->sql_like($fullname, ":q6", false) .
            ")";

        $params += [
            "q1" => $like,
            "q2" => $like,
            "q3" => $like,
            "q4" => $like,
            "q5" => $like,
            "q6" => $like,
        ];

        if (ctype_digit($query)) {
            $searchcond = "(" . $searchcond . " OR u.id = :idexact)";
            $params["idexact"] = $query;
        }

        $conditions[] = $searchcond;

        $sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.username, u.idnumber, u.suspended, u.lastaccess
                  FROM {user} u
                 WHERE " . implode(" AND ", $conditions) . "
              ORDER BY u.lastname ASC, u.firstname ASC";

        return $DB->get_records_sql($sql, $params, $offset, $limit);
    }

    /**
     * Get most recently active users.
     *
     * @param int $limit
     * @return array<int,stdClass>
     * @throws dml_exception
     */
    public static function get_recent_users(int $limit = 25): array {
        global $DB;

        $sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.username, u.idnumber, u.suspended, u.lastaccess
                  FROM {user} u
                 WHERE u.deleted = 0
              ORDER BY u.lastaccess DESC, u.id DESC";

        return $DB->get_records_sql($sql, [], 0, $limit);
    }

    /**
     * Get a full user record.
     *
     * @param int $userid
     * @return stdClass
     * @throws dml_exception
     */
    public static function get_user(int $userid): stdClass {
        return core_user::get_user($userid, "*", MUST_EXIST);
    }

    /**
     * Get users online in the last N minutes.
     *
     * @param int $minutes
     * @param int $limit
     * @param int $offset
     * @return array<int,stdClass>
     * @throws dml_exception
     */
    public static function get_online_users(int $minutes = 10, int $limit = 250, int $offset = 0): array {
        global $DB;

        $minutes = max(1, $minutes);
        $timefinish = time();
        $onlinestart = $timefinish - ($minutes * MINSECS);

        $sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.username, u.idnumber, u.suspended, u.lastaccess
                  FROM {user} u
                 WHERE u.deleted = 0
                   AND u.lastaccess BETWEEN :onlinestart AND :timefinish
              ORDER BY u.lastaccess DESC, u.id DESC";

        return $DB->get_records_sql($sql, [
            "onlinestart" => $onlinestart,
            "timefinish" => $timefinish,
        ], $offset, $limit);
    }

    /**
     * Count users online in the last N minutes.
     *
     * @param int $minutes
     * @return int
     * @throws dml_exception
     */
    public static function count_online_users(int $minutes = 10): int {
        global $DB;

        $minutes = max(1, $minutes);
        $timefinish = time();
        $onlinestart = $timefinish - ($minutes * MINSECS);

        return $DB->count_records_select("user", "deleted = 0 AND lastaccess BETWEEN :onlinestart AND :timefinish", [
            "onlinestart" => $onlinestart,
            "timefinish" => $timefinish,
        ]);
    }
}
