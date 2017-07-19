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
 * @created    13/05/17 13:29
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\InfoInterface;
use local_kopere_dashboard\report\custom\ReportInterface;
use local_kopere_dashboard\report\custom\ReportsCourseAccess;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\vo\kopere_dashboard_reportcat;
use local_kopere_dashboard\vo\kopere_dashboard_reports;

class Reports {

    /**
     * @return array
     */
    public static function globalMenus() {
        global $DB, $CFG;

        $menus = array();

        $kopere_dashboard_reportcats = $DB->get_records('kopere_dashboard_reportcat', array('enable' => 1));
        /** @var kopere_dashboard_reportcat $kopere_dashboard_reportcat */
        foreach ($kopere_dashboard_reportcats as $kopere_dashboard_reportcat) {
            // Executa o SQL e vrifica se o SQL retorna status>0
            if (strlen($kopere_dashboard_reportcat->enablesql)) {
                $status = $DB->get_record_sql($kopere_dashboard_reportcat->enablesql);
                if ($status->status == 0)
                    continue;
            }

            if (strpos($kopere_dashboard_reportcat->image, 'assets/') === 0) {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/{$kopere_dashboard_reportcat->image}";
            } else {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/icon/report.svg";
            }

            $menus[] = array('Reports::dashboard&type=' . $kopere_dashboard_reportcat->type,
                $kopere_dashboard_reportcat->title,
                $icon
            );
        }

        return $menus;
    }

    public function dashboard() {
        global $CFG, $DB;

        DashboardUtil::startPage(get_string_kopere('reports_title'));

        echo '<div class="element-box">';
        TitleUtil::printH3('reports_title');

        $type = optional_param('type', null, PARAM_TEXT);

        if ($type)
            $kopere_dashboard_reportcats = $DB->get_records('kopere_dashboard_reportcat', array('type' => $type, 'enable' => 1));
        else
            $kopere_dashboard_reportcats = $DB->get_records('kopere_dashboard_reportcat', array('enable' => 1));

        /** @var kopere_dashboard_reportcat $kopere_dashboard_reportcat */
        foreach ($kopere_dashboard_reportcats as $kopere_dashboard_reportcat) {
            // Executa o SQL e vrifica se o SQL retorna status>0
            if (strlen($kopere_dashboard_reportcat->enablesql)) {
                $status = $DB->get_record_sql($kopere_dashboard_reportcat->enablesql);
                if ($status->status == 0)
                    continue;
            }

            if (strpos($kopere_dashboard_reportcat->image, 'assets/') === 0) {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/{$kopere_dashboard_reportcat->image}";
            } else {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/icon/report.svg";
            }

            TitleUtil::printH3("<img src='{$icon}' alt='Icon' height='23' width='23' > " .
                $kopere_dashboard_reportcat->title, false);

            $kopere_dashboard_reportss = $DB->get_records('kopere_dashboard_reports', array('reportcatid' => $kopere_dashboard_reportcat->id, 'enable' => 1));

            /** @var kopere_dashboard_reports $kopere_dashboard_reports */
            foreach ($kopere_dashboard_reportss as $kopere_dashboard_reports) {
                echo "<h4 style='padding-left: 31px;'><a href='?Reports::loadReport&report={$kopere_dashboard_reports->id}'>{$kopere_dashboard_reports->title}</a></h4>";
            }
        }

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function loadReport() {
        global $DB, $CFG;

        $report = optional_param('report', 0, PARAM_INT);
        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_dashboard_reports */
        $kopere_dashboard_reports = $DB->get_record('kopere_dashboard_reports', array('id' => $report));
        Header::notfoundNull($kopere_dashboard_reports, 'Relatório não localizado!');

        /** @var kopere_dashboard_reportcat $kopere_dashboard_reportcat */
        $kopere_dashboard_reportcat = $DB->get_record('kopere_dashboard_reportcat', array('id' => $kopere_dashboard_reports->reportcatid));
        Header::notfoundNull($kopere_dashboard_reportcat, 'Relatório não localizado!');

        DashboardUtil::startPage(array(
            array('Reports::dashboard', 'Relatórios'),
            array('Reports::dashboard&type=' . $kopere_dashboard_reportcat->type, $kopere_dashboard_reportcat->title),
            $kopere_dashboard_reports->title
        ));

        echo '<div class="element-box table-responsive">';

        if (strlen($kopere_dashboard_reports->prerequisit) && $id == 0) {
            $this->prerequisit($report, $kopere_dashboard_reports->prerequisit);
        } else {

            if (strpos($kopere_dashboard_reports->reportsql, '\\') === 0) {
                $className = $kopere_dashboard_reports->reportsql;

                $class = new $className();
                TitleUtil::printH3($class->name(), false);
                $class->generate();

            } else {
                TitleUtil::printH3($kopere_dashboard_reports->title, false);

                if (strlen($kopere_dashboard_reports->prerequisit)) {
                    if ($kopere_dashboard_reports->prerequisit == 'listCourses')
                        $reports = $DB->get_records_sql($kopere_dashboard_reports->reportsql, array('courseid' => $id));
                } else
                    $reports = $DB->get_records_sql($kopere_dashboard_reports->reportsql);

                if (strlen($kopere_dashboard_reports->foreach))
                    foreach ($reports as $key => $item) {
                        eval($kopere_dashboard_reports->foreach);
                        $reports[$key] = $item;
                    }
                $columns = json_decode($kopere_dashboard_reports->columns);
                if (!isset($columns->header))
                    $columns->header = array();
                $table = new DataTable($columns->columns, $columns->header);
                $table->setIsExport(true);
                $table->printHeader('', false);
                $table->setRow($reports);
                $table->close(false);
            }
        }
        echo '</div>';
        DashboardUtil::endPage();
    }

    private function prerequisit($report, $pre) {
        if ($pre == 'listCourses') {
            echo '<h3>Selecione o curso para gerar o relatório</h3>';

            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
            $table->addHeader('Nome do Curso', 'fullname');
            $table->addHeader('Nome curto', 'shortname');
            $table->addHeader('Visível', 'visible', TableHeaderItem::RENDERER_VISIBLE);
            $table->addHeader('Nº alunos inscritos', 'inscritos', TableHeaderItem::TYPE_INT, null, 'width:50px;white-space:nowrap;');

            $table->setAjaxUrl('Courses::loadAllCourses');
            $table->setClickRedirect('Reports::loadReport&type=course&report=' . $report . '&id={id}', 'id');
            $table->printHeader();
            $table->close();
        }
    }
}