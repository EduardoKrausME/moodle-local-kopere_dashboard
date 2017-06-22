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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report\custom\user\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class UserNeverLogin implements ReportInterface {

    public $reportName = 'Usuários que nunca logaram';

    /**
     * @return string
     */
    public function name() {
        return $this->reportName;
    }

    /**
     * @return boolean
     */
    public function isEnable() {
        return false;
    }

    /**
     * @return void
     */
    public function generate() {
        global $DB;
        $reportSql
            = 'SELECT id, username, firstname, lastname, idnumber
                         FROM {user} u
                        WHERE u.deleted    = 0
                          AND u.lastlogin  = 0
                          AND u.lastaccess = 0';

        $report = $DB->get_records_sql($reportSql);

        // echo '<pre>';
        // print_r($report);
        // echo '</pre>';
    }

    /**
     * @return void
     */
    public function listData() {

    }
}