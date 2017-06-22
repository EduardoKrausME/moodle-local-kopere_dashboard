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

namespace local_kopere_dashboard\report\custom\enrol_cohort\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class CohortsByUser implements ReportInterface {

    public $reportName = 'Coortes e os usuários';

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
        return true;
    }

    /**
     * @return void
     */
    public function generate() {
        $reportSql
            = 'SELECT u.firstname, u.lastname, h.idnumber, h.name
                            FROM {cohort} h
                            JOIN {cohort_members} hm ON h.id = hm.cohortid
                            JOIN {user} u ON hm.userid = u.id
                            ORDER BY u.firstname';
    }

    /**
     * @return void
     */
    public function listData() {

    }
}