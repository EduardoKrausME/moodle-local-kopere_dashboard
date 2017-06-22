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
 * @created    21/05/17 02:52
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report\custom\server\reports;

use local_kopere_dashboard\chartsjs\Pie;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\ReportInterface;
use local_kopere_dashboard\report\Files;
use local_kopere_dashboard\util\BytesUtil;
use local_kopere_dashboard\util\Json;

class ReportsDisk implements ReportInterface {
    public $reportName = 'Relatório de uso do Disco';

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

    public function generate() {
        echo '<h3>Todo o Moodle</h3>';
        echo '<h5>' . BytesUtil::sizeToByte(Files::countAllSpace()) . '</h5>';

        echo '<h3>Todos os Usuários</h3>';
        echo '<h5>' . BytesUtil::sizeToByte(Files::countAllUsersSpace()) . '</h5>';

        echo '<h3>Cursos</h3>';
        // echo '<h5>' . BytesUtil::sizeToByte ( Files::countAllCourseSpace () ) . '</h5>';

        $fileDatas = Files::listSizesCourses();

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
        $table->addHeader('Nome do Curso', 'fullname');
        $table->addHeader('Nome curto', 'shortname');
        $table->addHeader('Visível', 'visible', TableHeaderItem::RENDERER_VISIBLE);
        $table->addHeader('Arquivos do Curso', 'coursesize', TableHeaderItem::TYPE_BYTES);
        $table->addHeader('Arquivos dos Módulos', 'modulessize', TableHeaderItem::TYPE_BYTES);

        $table->setIsExport(true);
        $table->setClickRedirect('Courses::details&courseid={id}', 'id');
        $table->printHeader('', false);
        $table->setRow($fileDatas);
        $table->close(false);

        $pieData = array();
        foreach ($fileDatas as $fileData) {
            $pieData[] = new Pie($fileData->fullname, $fileData->allsize);
        }

        Pie::createRegular($pieData);
    }

    public function listData() {
        Json::encodeAndReturn(Files::listSizesCourses());
    }
}