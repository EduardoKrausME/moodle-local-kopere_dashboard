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

class ConclusaoCriterio implements ReportInterface {

    public $reportName = 'Conclusão do curso com Critério';

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
            = 'SELECT u.username AS USER,
                c.shortname AS course,
                t.timecompleted,
                p.module,p.moduleinstance
 --                ,
 --                CASE
 --                WHEN (SELECT a.method FROM {course_completion_aggr_methd}  a  WHERE (a.course = c.id AND a.criteriatype IS NULL) = 1) THEN "Any"
 --                ELSE "All"
 --                END AS aggregation,
 --                CASE
 --                WHEN p.criteriatype = 1 THEN "Self"
 --                WHEN p.criteriatype = 2 THEN "By Date"
 --                WHEN p.criteriatype = 3 THEN "Unenrol Status"
 --                WHEN p.criteriatype = 4 THEN "Activity"
 --                WHEN p.criteriatype = 5 THEN "Duration"
 --                WHEN p.criteriatype = 6 THEN "Course Grade"
 --                WHEN p.criteriatype = 7 THEN "Approve by Role"
 --                WHEN p.criteriatype = 8 THEN "Previous Course"
 --                END AS criteriatype,
 --                CASE
 --                WHEN p.criteriatype = 1 THEN "*"
 --                WHEN p.criteriatype = 2 THEN DATE_FORMAT(FROM_UNIXTIME(p.timeend),\'%Y-%m-%d\')
 --                WHEN p.criteriatype = 3 THEN t.unenroled
 --                WHEN p.criteriatype = 4 THEN
 --                WHEN p.criteriatype = 5 THEN p.enrolperiod
 --                WHEN p.criteriatype = 6 THEN p.gradepass,t.gradefinal
 --                WHEN p.criteriatype = 7 THEN p.ROLE
 --                WHEN p.criteriatype = 8 THEN (SELECT pc.shortname FROM {course} pc WHERE pc.id = p.courseinstance)
 --                END AS criteriadetail
                FROM {course_completion_crit_compl} t
                JOIN {user}    u ON t.userid = u.id
                JOIN {course}  c ON t.course = c.id
                JOIN {course_completion_criteria} p ON t.criteriaid = p.id';

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