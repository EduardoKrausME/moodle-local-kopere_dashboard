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
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\audit;

use dml_exception;
use local_kopere_dashboard\util\request;

/**
 * Class manager
 */
class manager {
    /**
     * Function log
     *
     * @param string $component
     * @param string $action
     * @param string|null $objecttable
     * @param int|null $objectid
     * @param string $description
     * @param int|null $contextid
     * @param array $details
     * @return void
     * @throws dml_exception
     */
    public static function log(
        string $component,
        string $action,
        ?string $objecttable = null,
        ?int $objectid = null,
        string $description = "",
        ?int $contextid = null,
        array $details = []
    ): void {
        global $DB, $USER;

        $record = (object) [
            "timecreated" => time(),
            "userid" => (!empty($USER) && !empty($USER->id)) ? $USER->id : null,
            "component" => $component,
            "action" => $action,
            "objecttable" => $objecttable,
            "objectid" => $objectid,
            "description" => $description,
            "contextid" => $contextid,
            "ip" => getremoteaddr(),
            "useragent" => request::user_agent(),
            "detailsjson" => !empty($details) ? json_encode($details) : null,
        ];

        $DB->insert_record("local_kopere_dashboard_audit", $record);
    }

    /**
     * Function search
     *
     * @param array $filters
     * @param int $limit
     * @return array
     * @throws dml_exception
     */
    public static function search(array $filters, int $limit = 200): array {
        global $DB;

        $where = [];
        $params = [];

        if (!empty($filters["q"])) {
            $where[] = $DB->sql_like("description", ":q", false, false);
            $params["q"] = "%{$filters["q"]}%";
        }

        if (!empty($filters["component"])) {
            $where[] = "component = :component";
            $params["component"] = $filters["component"];
        }

        if (!empty($filters["userid"])) {
            $where[] = "userid = :userid";
            $params["userid"] = $filters["userid"];
        }

        $wheresql = !empty($where) ? ("WHERE " . implode(" AND ", $where)) : "";

        return $DB->get_records_sql(
            "SELECT * FROM {local_kopere_dashboard_audit} {$wheresql} ORDER BY timecreated DESC",
            $params,
            0,
            $limit
        );
    }
}
