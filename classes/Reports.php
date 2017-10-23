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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\report\custom\InfoInterface;
use local_kopere_dashboard\report\custom\ReportInterface;
use local_kopere_dashboard\report\custom\ReportsCourseAccess;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Json;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\util\Export;
use local_kopere_dashboard\vo\kopere_dashboard_reportcat;
use local_kopere_dashboard\vo\kopere_dashboard_reports;

class Reports {

    /**
     * @return array
     */
    public static function globalMenus() {
        global $DB, $CFG;

        $menus = array();

        $kopere_reportcats = $DB->get_records('kopere_dashboard_reportcat', array('enable' => 1));
        /** @var kopere_dashboard_reportcat $kopere_reportcat */
        foreach ($kopere_reportcats as $kopere_reportcat) {
            // Executa o SQL e vrifica se o SQL retorna status>0
            if (strlen($kopere_reportcat->enablesql)) {
                $status = $DB->get_record_sql($kopere_reportcat->enablesql);
                if ($status == null || $status->status == 0) {
                    continue;
                }
            }

            if (strpos($kopere_reportcat->image, 'assets/') === 0) {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/{$kopere_reportcat->image}";
            } else {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/icon/report.svg";
            }

            $menus[] = array('Reports::dashboard&type=' . $kopere_reportcat->type,
                self::getTitle( $kopere_reportcat ),
                $icon
            );
        }

        return $menus;
    }

    public function dashboard() {
        global $CFG, $DB;

        DashboardUtil::startPage(get_string_kopere('reports_title'), -1);

        echo '<div class="element-box">';

        $type = optional_param('type', null, PARAM_TEXT);

        if ($type) {
            $kopere_reportcats = $DB->get_records('kopere_dashboard_reportcat', array('type' => $type, 'enable' => 1));
        } else {
            $kopere_reportcats = $DB->get_records('kopere_dashboard_reportcat', array('enable' => 1));
        }

        /** @var kopere_dashboard_reportcat $kopere_reportcat */
        foreach ($kopere_reportcats as $kopere_reportcat) {
            // Executa o SQL e vrifica se o SQL retorna status>0
            if (strlen($kopere_reportcat->enablesql)) {
                $status = $DB->get_record_sql($kopere_reportcat->enablesql);
                if ($status == null || $status->status == 0) {
                    continue;
                }
            }

            if (strpos($kopere_reportcat->image, 'assets/') === 0) {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/{$kopere_reportcat->image}";
            } else {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/icon/report.svg";
            }

            TitleUtil::printH3("<img src='{$icon}' alt='Icon' height='23' width='23' > " .
                self::getTitle($kopere_reportcat), false);

            $kopere_reportss = $DB->get_records('kopere_dashboard_reports', array('reportcatid' => $kopere_reportcat->id, 'enable' => 1));

            /** @var kopere_dashboard_reports $kopere_reports */
            foreach ($kopere_reportss as $kopere_reports) {
                $title = self::getTitle($kopere_reports);
                echo "<h4 style='padding-left: 31px;'><a href='?Reports::loadReport&report={$kopere_reports->id}'>{$title}</a></h4>";
            }
        }

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function loadReport() {
        global $DB;

        $report = optional_param('report', 0, PARAM_INT);
        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_reports */
        $kopere_reports = $DB->get_record('kopere_dashboard_reports',
            array('id' => $report));
        Header::notfoundNull($kopere_reports, get_string_kopere('reports_notfound'));

        /** @var kopere_dashboard_reportcat $kopere_reportcat */
        $kopere_reportcat = $DB->get_record('kopere_dashboard_reportcat',
            array('id' => $kopere_reports->reportcatid));
        Header::notfoundNull($kopere_reportcat, get_string_kopere('reports_notfound'));

        DashboardUtil::startPage(array(
            array('Reports::dashboard', get_string_kopere('reports_title')),
            array('Reports::dashboard&type=' . $kopere_reportcat->type, self::getTitle($kopere_reportcat)),
            self::getTitle($kopere_reports)
        ));

        echo '<div class="element-box table-responsive">';

        if (strlen($kopere_reports->prerequisit) && $id == 0) {
            $this->prerequisit($report, $kopere_reports->prerequisit);
        } else {

            if (strpos($kopere_reports->reportsql, 'local_kopere_dashboard') === 0) {
                $className = $kopere_reports->reportsql;

                $class = new $className();
                TitleUtil::printH3($class->name(), false);
                $class->generate();

            } else {
                TitleUtil::printH3(self::getTitle($kopere_reports), false);

                $columns = json_decode($kopere_reports->columns);
                if (!isset($columns->header))
                    $columns->header = array();
                $table = new DataTable($columns->columns, $columns->header);
                $table->setAjaxUrl('Reports::getdata&report=' . $report);
                $table->printHeader();
                $table->close(true, '', '"searching":false,"ordering":false');

                Button::primary('Baixar estes dados', "Reports::download&report={$report}&id={$id}");
            }
        }
        echo '</div>';
        DashboardUtil::endPage();
    }

    private function prerequisit($report, $pre) {
        if ($pre == 'listCourses') {
            TitleUtil::printH3('reports_selectcourse');

            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
            $table->addHeader(get_string_kopere('courses_name'), 'fullname');
            $table->addHeader(get_string_kopere('courses_shortname'), 'shortname');
            $table->addHeader(get_string_kopere('courses_visible'), 'visible', TableHeaderItem::RENDERER_VISIBLE);
            $table->addHeader(get_string_kopere('courses_enrol'), 'inscritos', TableHeaderItem::TYPE_INT, null, 'width:50px;white-space:nowrap;');

            $table->setAjaxUrl('Courses::loadAllCourses');
            $table->setClickRedirect('Reports::loadReport&type=course&report=' . $report . '&id={id}', 'id');
            $table->printHeader();
            $table->close();
        }
    }

    public function getdata(){
        global $DB, $CFG;

        $report = optional_param('report', 0, PARAM_INT);
        $id     = optional_param('id', 0, PARAM_INT);
        $start = optional_param('start', 0, PARAM_INT);
        $length = optional_param('length', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_reports */
        $kopere_reports = $DB->get_record('kopere_dashboard_reports', array('id' => $report));

        if ($CFG->dbtype == 'pgsql') {
            $sql = "{$kopere_reports->reportsql} LIMIT $length OFFSET $start";
        }else{
            $sql = "{$kopere_reports->reportsql} LIMIT $start, $length";
        }


        if (strlen($kopere_reports->prerequisit) && $kopere_reports->prerequisit == 'listCourses') {
            $reports = $DB->get_records_sql($sql, array('courseid' => $id));
            $recordsTotal = $DB->get_records_sql($kopere_reports->reportsql, array('courseid' => $id));
        } else {
            $reports = $DB->get_records_sql($sql);
            $recordsTotal = $DB->get_records_sql($kopere_reports->reportsql);
        }

        if (strlen($kopere_reports->foreach)) {
            foreach ($reports as $key => $item) {
                eval($kopere_reports->foreach);
                $reports[$key] = $item;
            }
        }

        Json::encodeAndReturn($reports, count($recordsTotal), count($recordsTotal));
    }

    private static function getTitle($obj) {
        if (strpos($obj->title, '[[') === 0) {
            return get_string_kopere(substr($obj->title, 2, -2));
        } else {
            return $obj->title;
        }
    }

    public function download() {
        global $DB;

        $report = optional_param('report', 0, PARAM_INT);
        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_reports */
        $kopere_reports = $DB->get_record('kopere_dashboard_reports',
            array('id' => $report));
        Header::notfoundNull($kopere_reports, get_string_kopere('reports_notfound'));

        Export::exportHeader('xls', self::getTitle($kopere_reports));

        if (strlen($kopere_reports->prerequisit) && $kopere_reports->prerequisit == 'listCourses') {
            $reports = $DB->get_records_sql($kopere_reports->reportsql, array('courseid' => $id));
        } else {
            $reports = $DB->get_records_sql($kopere_reports->reportsql);
        }

        $columns = json_decode($kopere_reports->columns);
        if (!isset($columns->header))
            $columns->header = array();
        $table = new DataTable($columns->columns, $columns->header);
        $table->printHeader('', false);
        $table->setRow($reports);
        echo '</table>';


        Export::exportClose();
    }
}