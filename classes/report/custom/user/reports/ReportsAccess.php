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

use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\ReportInterface;
use local_kopere_dashboard\util\DatatableSearchUtil;

class ReportsAccess implements ReportInterface {
    public $reportName = 'Relatório de Logins dos alunos';

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
        $table = new DataTable();
        $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT);
        $table->addHeader('Nome', 'nome');
        $table->addHeader('E-mail', 'email');
        $table->addHeader('Cidade', 'city');
        $table->addHeader('Login em', 'timecreated', TableHeaderItem::RENDERER_DATE);

        $table->setAjaxUrl('Reports::listData&type=user&report=ReportsAccess');
        $table->setClickRedirect('Users::details&userid={id}', 'id');
        $table->printHeader();
        $table->close(true, 'order:[[4,"desc"]]');
    }

    /**
     * @return void
     */
    public function listData() {
        $columnSelect = array(
            'lsl.id',
            'u.id AS userid',
            'concat( u.firstname, \' \', u.lastname ) AS nome',
            'u.email',
            'u.city',
            'lsl.timecreated'
        );
        $columnOrder = array(
            'u.id',
            array('nome', 'concat(u.firstname, \' \', u.lastname)'),
            'u.email',
            'u.city',
            'lsl.timecreated'
        );

        $search = new DatatableSearchUtil($columnSelect, $columnOrder);

        $search->executeSqlAndReturn("
               SELECT {[columns]}
                 FROM {logstore_standard_log}   lsl
                 JOIN {user}                    u    ON u.id = lsl.userid
                WHERE lsl.action LIKE 'loggedin'
                  AND lsl.target LIKE 'user'");
    }
}