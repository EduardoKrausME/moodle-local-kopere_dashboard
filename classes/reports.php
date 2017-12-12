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

use Horde\Socket\Client\Exception;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\html\button;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\json;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\util\export;
use local_kopere_dashboard\vo\kopere_dashboard_reportcat;
use local_kopere_dashboard\vo\kopere_dashboard_reports;

/**
 * Class reports
 * @package local_kopere_dashboard
 */
class reports {

    /**
     * @return array
     */
    public static function global_menus() {
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

            $menus[] = array('reports::dashboard&type=' . $kopere_reportcat->type,
                self::get_title( $kopere_reportcat ),
                $icon
            );
        }

        return $menus;
    }

    /**
     *
     */
    public function dashboard() {
        global $CFG, $DB;

        dashboard_util::start_page(get_string_kopere('reports_title'), -1);

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

            title_util::print_h3("<img src='{$icon}' alt='Icon' height='23' width='23' > " .
                self::get_title($kopere_reportcat), false);

            $kopere_reportss = $DB->get_records('kopere_dashboard_reports', array('reportcatid' => $kopere_reportcat->id, 'enable' => 1));

            /** @var kopere_dashboard_reports $kopere_reports */
            foreach ($kopere_reportss as $kopere_reports) {
                $title = self::get_title($kopere_reports);
                echo "<h4 style='padding-left: 31px;'><a href='?reports::load_report&report={$kopere_reports->id}'>{$title}</a></h4>";
            }
        }

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     *
     */
    public function load_report() {
        global $DB;

        $report = optional_param('report', 0, PARAM_INT);
        $courseid = optional_param('courseid', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_reports */
        $kopere_reports = $DB->get_record('kopere_dashboard_reports',
            array('id' => $report));
        header::notfound_null($kopere_reports, get_string_kopere('reports_notfound'));

        /** @var kopere_dashboard_reportcat $kopere_reportcat */
        $kopere_reportcat = $DB->get_record('kopere_dashboard_reportcat',
            array('id' => $kopere_reports->reportcatid));
        header::notfound_null($kopere_reportcat, get_string_kopere('reports_notfound'));

        dashboard_util::start_page(array(
            array('reports::dashboard', get_string_kopere('reports_title')),
            array('reports::dashboard&type=' . $kopere_reportcat->type, self::get_title($kopere_reportcat)),
            self::get_title($kopere_reports)
        ));

        echo '<div class="element-box table-responsive">';

        if (strlen($kopere_reports->prerequisit) && $courseid == 0) {
            try{
                ini_set ( 'max_execution_time', 0 );
            }catch(Exception $e){}
            $this->prerequisit($report, $kopere_reports->prerequisit);
        } else {

            if (strpos($kopere_reports->reportsql, 'local_kopere_dashboard') === 0) {
                $class_name = $kopere_reports->reportsql;

                $class = new $class_name();
                title_util::print_h3($class->name(), false);
                $class->generate();

            } else {
                title_util::print_h3(self::get_title($kopere_reports), false);

                $columns = json_decode($kopere_reports->columns);
                if (!isset($columns->header))
                    $columns->header = array();
                $table = new data_table($columns->columns, $columns->header);
                $table->set_ajax_url ( 'reports::getdata&report=' . $report . '&courseid=' . $courseid );
                $table->print_header();
                $table->close(true, '', '"searching":false,"ordering":false');

                button::primary('Baixar estes dados', "reports::download&report={$report}&courseid={$courseid}");
            }
        }
        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * @param $report
     * @param $pre
     */
    private function prerequisit($report, $pre) {
        if ($pre == 'listCourses') {
            title_util::print_h3('reports_selectcourse');

            $table = new data_table();
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width: 20px');
            $table->add_header(get_string_kopere('courses_name'), 'fullname');
            $table->add_header(get_string_kopere('courses_shortname'), 'shortname');
            $table->add_header(get_string_kopere('courses_visible'), 'visible', table_header_item::RENDERER_VISIBLE);
            $table->add_header(get_string_kopere('courses_enrol'), 'inscritos', table_header_item::TYPE_INT, null, 'width:50px;white-space:nowrap;');

            $table->set_ajax_url('courses::load_all_courses');
            $table->set_click_redirect('reports::load_report&type=course&report=' . $report . '&courseid={id}', 'id');
            $table->print_header();
            $table->close();
        }
    }

    /**
     *
     */
    public function getdata(){
        global $DB, $CFG;

        $report   = optional_param('report', 0, PARAM_INT);
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $start    = optional_param('start', 0, PARAM_INT);
        $length   = optional_param('length', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_reports */
        $kopere_reports = $DB->get_record('kopere_dashboard_reports', array('id' => $report));

        if ($CFG->dbtype == 'pgsql') {
            $sql = "{$kopere_reports->reportsql} LIMIT $length OFFSET $start";
        }else{
            $sql = "{$kopere_reports->reportsql} LIMIT $start, $length";
        }

        if (strlen($kopere_reports->prerequisit) && $kopere_reports->prerequisit == 'listCourses') {
            $reports = $DB->get_records_sql($sql, array('courseid' => $courseid));
            $records_total = $DB->get_records_sql($kopere_reports->reportsql, array('courseid' => $courseid));
        } else {
            $reports = $DB->get_records_sql($sql);
            $records_total = $DB->get_records_sql($kopere_reports->reportsql);
        }

        if (strlen($kopere_reports->foreach)) {
            foreach ($reports as $key => $item) {
                eval($kopere_reports->foreach);
                $reports[$key] = $item;
            }
        }

        json::encode($reports, count($records_total), count($records_total));
    }

    /**
     * @param $obj
     * @return string
     */
    private static function get_title($obj) {
        if (strpos($obj->title, '[[') === 0) {
            return get_string_kopere(substr($obj->title, 2, -2));
        } else {
            return $obj->title;
        }
    }

    /**
     *
     */
    public function download() {
        global $DB;

        $report = optional_param('report', 0, PARAM_INT);
        $courseid = optional_param('courseid', 0, PARAM_INT);

        /** @var kopere_dashboard_reports $kopere_reports */
        $kopere_reports = $DB->get_record('kopere_dashboard_reports',
            array('id' => $report));
        header::notfound_null($kopere_reports, get_string_kopere('reports_notfound'));

        export::header('xls', self::get_title($kopere_reports));

        if (strlen($kopere_reports->prerequisit) && $kopere_reports->prerequisit == 'listCourses') {
            $reports = $DB->get_records_sql($kopere_reports->reportsql, array('courseid' => $courseid));
        } else {
            $reports = $DB->get_records_sql($kopere_reports->reportsql);
        }

        $columns = json_decode($kopere_reports->columns);
        if (!isset($columns->header))
            $columns->header = array();
        $table = new data_table($columns->columns, $columns->header);
        $table->print_header('', false);
        $table->set_row($reports);
        echo '</table>';
        
        export::close();
    }
}