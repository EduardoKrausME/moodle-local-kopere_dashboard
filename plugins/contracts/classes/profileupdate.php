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
 * profileupdate.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_contracts;

use coding_exception;
use core\exception\moodle_exception;
use dml_exception;
use local_kopere_dashboard\util\userdate;
use moodle_url;

/**
 * Class profileupdate
 */
class profileupdate {
    /**
     * Function get_accepted_contracts
     *
     * @param int $userid
     * @return array
     * @throws dml_exception
     */
    public static function get_accepted_contracts(int $userid): array {
        global $DB;

        $sql = "SELECT a.id, a.contractid, a.courseid, a.timeaccepted, a.readseconds, c.name
                  FROM {koperedashboard_contract_accept} a
                  JOIN {koperedashboard_contracts} c ON c.id = a.contractid
                 WHERE a.userid = :userid
              ORDER BY a.timeaccepted DESC";
        return $DB->get_records_sql($sql, ["userid" => $userid]);
    }

    /**
     * Function render_profile_section
     *
     * @param int $userid
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     * @throws dml_exception
     */
    public static function render_profile_section(int $userid): string {
        global $OUTPUT;

        $items = [];
        foreach (self::get_accepted_contracts($userid) as $r) {
            $items[] = [
                "name" => format_string($r->name),
                "courseid" => $r->courseid,
                "timeaccepted" => userdate::format($r->timeaccepted),
                "readseconds" => $r->readseconds,
                "courseurl" => new moodle_url("/course/view.php", ["id" => $r->courseid]),
                "pdfurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/open_contract.php", ["acceptid" => $r->id]),
            ];
        }

        return $OUTPUT->render_from_template("koperedashboard_contracts/profile_section", [
            "title" => get_string("profile_section_title", "koperedashboard_contracts"),
            "items" => $items,
        ]);
    }
}
