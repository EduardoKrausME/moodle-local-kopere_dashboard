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

class UsuariosNuncaLogin implements ReportInterface {

    public $reportName = 'Os usuários registrados, que não fizeram login no Curso';

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
            = 'SELECT u.id AS ID,
                      ul.timeaccess,
                      u.firstname AS Firstname,
                      u.lastname AS Lastname,
                      u.email AS Email,
                      u.city AS City,
                      u.idnumber AS IDNumber,
                      u.phone1 AS Phone,
                      u.institution AS Institution,
                      u.lastaccess,
                      (SELECT r.name
                         FROM  {user_enrolments} ue2
                         JOIN {enrol} e ON e.id = ue2.enrolid
                         JOIN {role}  r ON e.id = r.id
                        WHERE ue2.userid = u.id
                          AND e.courseid = c.id ) AS RoleName

                 FROM {user_enrolments} ue
                 JOIN {enrol}  e ON e.id = ue.enrolid
                 JOIN {course} c ON c.id = e.courseid
                 JOIN {user}   u ON u.id = ue.userid
                 LEFT JOIN {user_lastaccess} ul ON ul.userid = u.id
                WHERE ul.timeaccess IS NULL';

        $report = $DB->get_records_sql($reportSql);
    }

    /**
     * @return void
     */
    public function listData() {

    }
}
