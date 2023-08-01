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
 * Date: 27/07/2023
 * Time: 17:03
 */

namespace local_kopere_dashboard\task;

class db_report_login extends \core\task\scheduled_task {
    /**
     * @return string
     * @throws \coding_exception
     */
    public function get_name() {
        return get_string('crontask_db_report_login', 'local_kopere_dashboard');
    }

    /**
     * @throws \dml_exception
     */
    public function execute() {
        global $DB;

        $reportsql = "
                SELECT lsl.userid + lsl.timecreated as u, lsl.userid, lsl.timecreated, lsl.ip
                  FROM {logstore_standard_log} lsl
                 WHERE lsl.action LIKE 'loggedin'
                   AND lsl.target LIKE 'user'";

        $logstorestandardlogs = $DB->get_records_sql($reportsql);

        $DB->delete_records("kopere_dashboard_reportlogin");

        foreach ($logstorestandardlogs as $logstorestandardlog) {

            $data = (object)[
                "userid" => $logstorestandardlog->userid,
                "ip" => $logstorestandardlog->ip,
                "timecreated" => $logstorestandardlog->timecreated,
            ];

            try {
                $DB->insert_record("kopere_dashboard_reportlogin", $data);
            } catch (\dml_exception $e) {
            }
        }
    }
}