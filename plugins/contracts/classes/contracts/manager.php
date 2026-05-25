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
 * manager.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_contracts\contracts;

use dml_exception;
use stdClass;

/**
 * Class manager
 */
class manager {
    /**
     * Function get_session_key
     *
     * @return string
     */
    protected static function get_session_key(): string {
        return "koperedashboard_contracts";
    }

    /**
     * Function is_course_cached_as_accepted
     *
     * @param int $courseid
     * @return bool
     */
    public static function is_course_cached_as_accepted(int $courseid): bool {
        $key = self::get_session_key();
        return !empty($_SESSION[$key]["coursesaccepted"][$courseid]);
    }

    /**
     * Function is_contract_cached_as_accepted
     *
     * @param int $contractid
     * @param int $courseid
     * @return bool
     */
    public static function is_contract_cached_as_accepted(int $contractid, int $courseid): bool {
        $key = self::get_session_key();
        return !empty($_SESSION[$key]["contractsaccepted"][$courseid][$contractid]);
    }

    /**
     * Function mark_contract_as_accepted_in_session
     *
     * @param int $contractid
     * @param int $courseid
     * @return void
     */
    public static function mark_contract_as_accepted_in_session(int $contractid, int $courseid): void {
        $key = self::get_session_key();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }

        if (!isset($_SESSION[$key]["contractsaccepted"])) {
            $_SESSION[$key]["contractsaccepted"] = [];
        }

        if (!isset($_SESSION[$key]["contractsaccepted"][$courseid])) {
            $_SESSION[$key]["contractsaccepted"][$courseid] = [];
        }

        $_SESSION[$key]["contractsaccepted"][$courseid][$contractid] = 1;
    }

    /**
     * Function mark_course_as_accepted_in_session
     *
     * @param int $courseid
     * @return void
     */
    public static function mark_course_as_accepted_in_session(int $courseid): void {
        $key = self::get_session_key();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }

        if (!isset($_SESSION[$key]["coursesaccepted"])) {
            $_SESSION[$key]["coursesaccepted"] = [];
        }

        $_SESSION[$key]["coursesaccepted"][$courseid] = 1;
    }

    /**
     * Function get_active_contracts_for_course
     *
     * @param int $courseid
     * @return array
     * @throws dml_exception
     */
    public static function get_active_contracts_for_course(int $courseid): array {
        global $DB;

        $sql = "SELECT c.*
                  FROM {koperedashboard_contracts} c
                  JOIN {koperedashboard_contract_courses} cc ON cc.contractid = c.id
                 WHERE cc.courseid = :courseid AND c.active = 1
              ORDER BY c.id ASC";
        return $DB->get_records_sql($sql, ["courseid" => $courseid]);
    }

    /**
     * Function user_has_accepted
     *
     * @param int $contractid
     * @param int $courseid
     * @param int $userid
     * @return bool
     * @throws dml_exception
     */
    public static function user_has_accepted(int $contractid, int $courseid, int $userid): bool {
        global $DB;

        if (self::is_contract_cached_as_accepted($contractid, $courseid)) {
            return true;
        }

        $contractaccept = [
            "contractid" => $contractid,
            "courseid" => $courseid,
            "userid" => $userid,
        ];

        $accepted = $DB->record_exists("koperedashboard_contract_accept", $contractaccept);
        if ($accepted) {
            self::mark_contract_as_accepted_in_session($contractid, $courseid);
        }

        return $accepted;
    }

    /**
     * Returns one acceptance record for the user, course and contract.
     *
     * @param int $contractid
     * @param int $courseid
     * @param int $userid
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_acceptance_record(int $contractid, int $courseid, int $userid): ?stdClass {
        global $DB;

        $record = $DB->get_record("koperedashboard_contract_accept", [
            "contractid" => $contractid,
            "courseid" => $courseid,
            "userid" => $userid,
        ]);

        if ($record) {
            self::mark_contract_as_accepted_in_session($contractid, $courseid);
        }

        return $record ?: null;
    }

    /**
     * Returns one acceptance record by id.
     *
     * @param int $acceptid
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_acceptance_by_id(int $acceptid): ?stdClass {
        global $DB;

        return $DB->get_record("koperedashboard_contract_accept", ["id" => $acceptid]) ?: null;
    }

    /**
     * Function accept_contract
     *
     * @param int $contractid
     * @param int $courseid
     * @param int $userid
     * @param int $readseconds
     * @return int
     * @throws dml_exception
     */
    public static function accept_contract(int $contractid, int $courseid, int $userid, int $readseconds): int {
        global $DB;

        $existing = self::get_acceptance_record($contractid, $courseid, $userid);
        if ($existing) {
            return (int) $existing->id;
        }

        $record = (object) [
            "contractid" => $contractid,
            "courseid" => $courseid,
            "userid" => $userid,
            "timeaccepted" => time(),
            "ip" => getremoteaddr(),
            "useragent" => !empty($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null,
            "readseconds" => max(0, $readseconds),
        ];
        $record->id = $DB->insert_record("koperedashboard_contract_accept", $record);

        self::mark_contract_as_accepted_in_session($contractid, $courseid);
        pdf_service::store_signed_pdf((int) $record->id);

        return (int) $record->id;
    }

    /**
     * Function upsert_contract
     *
     * @param object $data
     * @param array $courseids
     * @return int
     * @throws dml_exception
     */
    public static function upsert_contract(object $data, array $courseids): int {
        global $DB;

        $now = time();

        if (!empty($data->id)) {
            $data->timemodified = $now;
            $DB->update_record("koperedashboard_contracts", $data);

            $DB->delete_records("koperedashboard_contract_courses", ["contractid" => $data->id]);
            foreach ($courseids as $courseid) {
                $contractcourses = (object) [
                    "contractid" => $data->id,
                    "courseid" => $courseid,
                ];
                $DB->insert_record("koperedashboard_contract_courses", $contractcourses);
            }

            return $data->id;
        }

        $data->timecreated = $now;
        $data->timemodified = $now;
        $id = $DB->insert_record("koperedashboard_contracts", $data);

        foreach ($courseids as $courseid) {
            $contractcourses = (object) [
                "contractid" => $id,
                "courseid" => $courseid,
            ];
            $DB->insert_record("koperedashboard_contract_courses", $contractcourses);
        }

        return $id;
    }
}
