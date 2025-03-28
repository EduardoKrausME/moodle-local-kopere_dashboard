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
 * Introduced  27/07/2023 17:03
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\task;

/**
 * Class db_report_login
 *
 * @package local_kopere_dashboard\task
 */
class db_report_login extends \core\task\scheduled_task {

    /**
     * Function get_name
     *
     * @return string
     * @throws \coding_exception
     */
    public function get_name() {
        return get_string("crontask_db_report_login", "local_kopere_dashboard");
    }

    /**
     * Function execute
     *
     * @throws \dml_exception
     * @throws \ddl_exception
     */
    public function execute() {
        global $DB;

        if (!$DB->get_manager()->table_exists("local_kopere_dashboard_login")) {
            return;
        }

        $reportsql = "
                SELECT lsl.userid + lsl.timecreated as u, lsl.userid, lsl.timecreated, lsl.ip
                  FROM {logstore_standard_log} lsl
                 WHERE lsl.action LIKE 'loggedin'
                   AND lsl.target LIKE 'user'";

        $logstorestandardlogs = $DB->get_records_sql($reportsql);

        $DB->delete_records("local_kopere_dashboard_login");

        foreach ($logstorestandardlogs as $logstorestandardlog) {

            $data = (object)[
                "userid" => $logstorestandardlog->userid,
                "ip" => $logstorestandardlog->ip,
                "timecreated" => $logstorestandardlog->timecreated,
            ];

            try {
                $DB->insert_record("local_kopere_dashboard_login", $data);
            } catch (\dml_exception $e) { // phpcs:disable
            }
        }
    }
}
