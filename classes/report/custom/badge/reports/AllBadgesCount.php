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

class AllBadgesCount implements ReportInterface {

    public $reportName = 'Todos os Emblemas disponíveis no Moodle';

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
            = 'SELECT b.id, b.name, b.description, b.type, b.status,
                (SELECT COUNT(*)
                   FROM {badge_issued} d
                   WHERE d.badgeid = b.id
                 )AS alunos
                FROM {badge} b';

        $reports = $DB->get_records_sql($reportSql);

        // echo '<pre>';
        // print_r ( $reports );
        // echo '</pre>';

        Botao::info(get_string('managebadges', 'badges'), "{$CFG->wwwroot}/badges/index.php?type=1");

        $report = array();
        foreach ($reports as $item) {
            if ($item->status == 0 || $item->status == 2) {
                $item->statustext = false;
            } else if ($item->status == 1 || $item->status == 3) {
                $item->statustext = false;
            } else if ($item->status == 4) {
                $item->statustext = "-";
            }

            if ($item->type == 1) {
                $item->context = 'Sistema';
            }
            if ($item->type == 1) {
                $item->context = 'Curso';
            }

            $report[] = $item;
        }

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
        $table->addHeader('Nome', 'name');
        $table->addHeader('Contesto', 'context');
        $table->addHeader('Ativo', 'statustext', TableHeaderItem::RENDERER_STATUS);
        $table->addHeader('Quantidade de alunos', 'alunos', TableHeaderItem::TYPE_INT);

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