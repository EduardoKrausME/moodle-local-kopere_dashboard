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

namespace local_kopere_dashboard\report\custom\badge\reports;

use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\ReportInterface;

class AllBadgesUser implements ReportInterface {

    public $reportName = 'Todos os Emblemas conquistado pelos Usuários';

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
        global $DB, $CFG;
        $reportSql
            = 'SELECT d.id, u.id AS userid, u.username, u.firstname, u.lastname, b.name AS badgename, t.criteriatype, t.method,
                    (SELECT c.shortname
                        FROM {course} c
                        WHERE c.id = b.courseid) as course,
                    d.dateissued
                 FROM {badge_issued}   d
                 JOIN {badge}          b ON d.badgeid = b.id
                 JOIN {user}           u ON d.userid  = u.id
                 JOIN {badge_criteria} t ON b.id      = t.badgeid
                WHERE t.criteriatype != 0
             ORDER BY u.username';

        $reports = $DB->get_records_sql($reportSql);

        $report = array();
        foreach ($reports as $item) {
            $item->criteriatype = get_string('criteria_' . $item->criteriatype, 'badges');
            $item->name = fullname($item);

            $report[] = $item;
        }

        // echo '<pre>';
        // print_r ( $report );
        // echo '</pre>';

        Botao::info(get_string('managebadges', 'badges'), "{$CFG->wwwroot}/badges/index.php?type=1");

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
        $table->addHeader('Nome', 'name');
        $table->addHeader('Emblema', 'badgename');
        $table->addHeader('Critério', 'criteriatype');
        $table->addHeader('Curso', 'course');
        $table->addHeader('Em', 'dateissued', TableHeaderItem::RENDERER_DATE);

        $table->setIsExport(true);
        // $table->setClickRedirect ( 'Courses::details&courseid={id}', 'id' );
        $table->printHeader('', false);
        $table->setRow($report);
        $table->close(false);
    }

    /**
     * @return void
     */
    public function listData() {

    }
}