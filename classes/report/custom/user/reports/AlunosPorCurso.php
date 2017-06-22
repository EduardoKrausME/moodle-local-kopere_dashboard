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

use local_kopere_dashboard\chartsjs\Pie;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\ReportInterface;

class AlunosPorCurso implements ReportInterface {
    public $reportName = 'Contagem de alunos em cada curso';

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
        global $DB;
        $reportSql
            = 'SELECT c.id, c.fullname, c.shortname, context.id AS contextid, COUNT(c.id) AS alunos
                 FROM {role_assignments}  asg
                 JOIN {context}  context ON asg.contextid = context.id AND context.contextlevel = 50
                 JOIN {user}     u ON u.id = asg.userid
                 JOIN {course}   c ON context.instanceid = c.id
                WHERE asg.roleid = 5
             GROUP BY c.id';
        $report = $DB->get_records_sql($reportSql);

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
        $table->addHeader('Nome do Curso', 'fullname');
        $table->addHeader('Nome curto', 'shortname');
        $table->addHeader('Alunos', 'alunos');

        $table->setIsExport(true);
        $table->setClickRedirect('Courses::details&courseid={id}', 'id');
        $table->printHeader('', false);
        $table->setRow($report);
        $table->close(false);

        $pieData = array();
        foreach ($report as $item) {
            $pieData[] = new Pie($item->fullname, $item->alunos);
        }

        Pie::createRegular($pieData);

    }

    /**
     * @return void
     */
    public function listData() {

    }
}

