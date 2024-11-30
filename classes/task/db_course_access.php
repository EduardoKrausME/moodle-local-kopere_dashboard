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
 * Introduced  31/01/2024 09:18
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\task;

/**
 * Class db_course_access
 *
 * @package local_kopere_dashboard\task
 */
class db_course_access extends \core\task\scheduled_task {

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
     */
    public function execute() {
        global $DB, $CFG;

        if ($CFG->dbtype == "pgsql") {
            $reportsql = "
                SELECT CONCAT(courseid::text, userid::text, contextinstanceid::text) AS a, COUNT(*) AS contagem,
                       courseid, userid, contextinstanceid, timecreated
                  FROM {logstore_standard_log}
                 WHERE action = 'viewed'
                   AND timecreated BETWEEN (CURRENT_DATE - INTERVAL '30 DAY') AND CURRENT_DATE
              GROUP BY courseid, userid, contextinstanceid, timecreated
              ORDER BY timecreated DESC";
        } else {
            $reportsql = "
                SELECT CONCAT(courseid,userid,contextinstanceid) AS a, COUNT(*) AS contagem,
                       courseid, userid, contextinstanceid, timecreated
                  FROM {logstore_standard_log}
                 WHERE action = 'viewed'
                   AND timecreated BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -30 DAY) AND CURRENT_DATE()
              GROUP BY courseid, userid, contextinstanceid, timecreated
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
                $DB->insert_record("local_kopere_dashboard_acess", $data);
            } catch (\dml_exception $e) { // phpcs:disable
            }
        }
    }
}
