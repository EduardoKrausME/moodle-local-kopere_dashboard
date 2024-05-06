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
 * Date: 31/01/2024
 * Time: 09:18
 */

namespace local_kopere_dashboard\task;

class db_course_access extends \core\task\scheduled_task {
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

        $count = $DB->get_record_sql("SELECT COUNT(*) AS registros FROM {kopere_dashboard_courseacces}");
        if (false && $count->registros) {
            $reportsql = "
                SELECT CONCAT(courseid,userid,contextinstanceid) AS a, COUNT(*) AS contagem,
                       courseid, userid, contextinstanceid, timecreated
                  FROM {logstore_standard_log}
                 WHERE action = 'viewed'
                   AND timecreated BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -30 DAY) AND CURRENT_DATE()
              GROUP BY courseid, userid, contextinstanceid
              ORDER BY timecreated DESC";
        } else {
            $reportsql = "
                SELECT CONCAT(courseid,userid,contextinstanceid) AS a, COUNT(*) AS contagem,
                       courseid, userid, contextinstanceid, timecreated
                  FROM {logstore_standard_log}
                 WHERE action = 'viewed'
              GROUP BY courseid, userid, contextinstanceid
              ORDER BY timecreated DESC";
        }

        $logstorestandardlogs = $DB->get_records_sql($reportsql);

        foreach ($logstorestandardlogs as $logstorestandardlog) {

            $data = (object)[
                "contagem" => $logstorestandardlog->contagem,
                "courseid" => $logstorestandardlog->courseid,
                "userid" => $logstorestandardlog->userid,
                "context" => $logstorestandardlog->contextinstanceid,
                "timecreated" => $logstorestandardlog->timecreated,
            ];

            try {
                $DB->insert_record("kopere_dashboard_courseacces", $data);
            } catch (\dml_exception $e) {
            }
        }
    }
}