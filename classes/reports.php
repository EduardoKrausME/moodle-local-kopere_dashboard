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
 * reoprts file
 *
 * introduced 13/05/17 13:29
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\html\button;
use local_kopere_dashboard\report\custom\course_access;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\json;
use local_kopere_dashboard\util\submenu_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\util\export;
use local_kopere_dashboard\vo\local_kopere_dashboard_rcat;
use local_kopere_dashboard\vo\local_kopere_dashboard_reprt;

/**
 * Class reports
 *
 * @package local_kopere_dashboard
 */
class reports {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $CFG, $DB;

        dashboard_util::add_breadcrumb(get_string_kopere("reports_title"));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $type = optional_param("type", null, PARAM_TEXT);

        $koperereportcats = $DB->get_records("local_kopere_dashboard_rcat");

        /** @var local_kopere_dashboard_rcat $koperereportcat */
        foreach ($koperereportcats as $koperereportcat) {
            // Executa o SQL e vrifica se o SQL retorna status>0.
            if (strlen($koperereportcat->enablesql)) {
                $status = $DB->get_record_sql($koperereportcat->enablesql);
                if ($status == null || $status->status == 0) {
                    continue;
                }
            }

            if (strpos($koperereportcat->image, 'assets/') === 0) {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/{$koperereportcat->image}";
            } else {
                $icon = "{$CFG->wwwroot}/{$koperereportcat->image}";
            }

            title_util::print_h3("<img src='{$icon}' alt=\"Icon\" height=\"23\" width=\"23\" > " .
                self::get_title($koperereportcat), false);

            $koperereportss = $DB->get_records("local_kopere_dashboard_reprt",
                ["reportcatid" => $koperereportcat->id]);

            /** @var local_kopere_dashboard_reprt $koperereports */
            foreach ($koperereportss as $koperereports) {
                $title = self::get_title($koperereports);
                $extraenable = '';
                if (!$koperereports->enable) {
                    $extraenable = get_string_kopere("reports_disabled");
                }

                $url = local_kopere_dashboard_makeurl("reports", "load_report", ["report" => $koperereports->id]);
                echo "<h4 style='padding-left:31px;'>
                          {$extraenable} <a href='{$url}'>{$title}</a>
                      </h4>";
            }
        }

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * Function load_report
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function load_report() {
        global $DB;

        $report = optional_param("report", 0, PARAM_INT);
        $courseid = optional_param("courseid", 0, PARAM_INT);

        /** @var local_kopere_dashboard_reprt $koperereports */
        $koperereports = $DB->get_record("local_kopere_dashboard_reprt",
            ["id" => $report]);
        header::notfound_null($koperereports, get_string_kopere("reports_notfound"));

        /** @var local_kopere_dashboard_rcat $koperereportcat */
        $koperereportcat = $DB->get_record("local_kopere_dashboard_rcat",
            ["id" => $koperereports->reportcatid]);
        header::notfound_null($koperereportcat, get_string_kopere("reports_notfound"));

        $titlecat = self::get_title($koperereportcat);
        $titlereport = self::get_title($koperereports);

        dashboard_util::add_breadcrumb(get_string_kopere("reports_title"),
            local_kopere_dashboard_makeurl("reports", "dashboard"));
        dashboard_util::add_breadcrumb($titlecat,
            local_kopere_dashboard_makeurl("reports", "dashboard", ["type" => $koperereportcat->type]));
        dashboard_util::add_breadcrumb($titlereport);
        dashboard_util::start_page();

        echo '<div class="element-box table-responsive">';

        if (strlen($koperereports->prerequisit) && $courseid == 0) {
            try {
                ini_set("max_execution_time", 0);
            } catch (\Exception $e) {
                debugging($e->getMessage());
            }
            $this->prerequisit($report, $koperereports->prerequisit);
        } else {

            if (strpos($koperereports->reportsql, "local_kopere_dashboard") === 0) {
                $classname = $koperereports->reportsql;

                // Hack for download.
                ob_end_clean();

                /** @var course_access $class */
                $class = new $classname();
                $class->generate();

                die();

            } else {
                title_util::print_h3($titlereport, false);

                $columns = json_decode($koperereports->columns);
                if (!isset($columns->header)) {
                    $columns->header = [];
                }

                $url = local_kopere_dashboard_makeurl("reports", "getdata", ["report" => $report, "courseid" => $courseid]);
                $table = new data_table($columns->columns, $columns->header);

                $table->set_ajax_url($url);
                $table->print_header();
                $extra = ["searching" => false, "ordering" => false];
                $table->close(true, $extra, false, $titlereport);

                $urldow = local_kopere_dashboard_makeurl("reports", "download", ["report" => $report, "courseid" => $courseid]);
                button::primary(get_string_kopere("reports_download"), $urldow);
            }
        }
        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * Function prerequisit
     *
     * @param $report
     * @param $pre
     *
     * @throws \coding_exception
     */
    private function prerequisit($report, $pre) {
        if ($pre == "listCourses") {
            title_util::print_h3("reports_selectcourse");

            $table = new data_table();
            $table->add_header('#', "id", table_header_item::TYPE_INT, null, 'width: 20px');
            $table->add_header(get_string_kopere("courses_name"), "fullname");
            $table->add_header(get_string_kopere("courses_shortname"), "shortname");
            $table->add_header(get_string_kopere("visible"), "visible", table_header_item::RENDERER_VISIBLE);
            $table->add_header(get_string_kopere("courses_enrol"), "inscritos",
                table_header_item::TYPE_INT, null, 'width:50px;white-space:nowrap;');

            $table->set_ajax_url(local_kopere_dashboard_makeurl("courses", "load_all_courses"));
            $table->set_click_redirect(
                local_kopere_dashboard_makeurl("reports", "load_report",
                    ["type" => "course", "report" => $report, "courseid" => "{id}"]), "id");
            $table->print_header();
            $table->close();
        }
    }

    /**
     * Function getdata
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function getdata() {
        global $DB, $CFG;

        $report = optional_param("report", 0, PARAM_INT);
        $courseid = optional_param("courseid", 0, PARAM_INT);
        $start = optional_param("start", 0, PARAM_INT);
        $length = optional_param("length", 0, PARAM_INT);

        $cache = \cache::make("local_kopere_dashboard", "report_getdata_cache");
        $cachekey = "data-{$report}-{$courseid}-{$start}-{$length}";
        if ($cache->has($cachekey)) {

            $cache = $cache->get($cachekey);
            json::encode($cache["reports"], $cache["count_recordstotal"], $cache["count_recordstotal"]);
            die();
        }

        /** @var local_kopere_dashboard_reprt $koperereports */
        $koperereports = $DB->get_record("local_kopere_dashboard_reprt", ["id" => $report]);

        if ($CFG->dbtype == "pgsql") {
            $sql = "{$koperereports->reportsql} LIMIT {$length} OFFSET {$start}";
        } else {
            $sql = "{$koperereports->reportsql} LIMIT {$start}, {$length}";
        }

        if (strlen($koperereports->prerequisit) && $koperereports->prerequisit == "listCourses") {
            $reports = $DB->get_records_sql($sql, ["courseid" => $courseid]);
            $recordstotal = $DB->get_records_sql($koperereports->reportsql, ["courseid" => $courseid]);
        } else {
            $reports = $DB->get_records_sql($sql);
            $recordstotal = $DB->get_records_sql($koperereports->reportsql);
        }

        if (strlen($koperereports->foreach)) {
            foreach ($reports as $key => $item) {
                if ($koperereports->foreach == "userfullname") {
                    $item->userfullname = fullname($item);
                } else {
                    $item = call_user_func($koperereports->foreach, $item);
                }
                $reports[$key] = $item;
            }
        }

        $cache->set($cachekey, [
            "reports" => $reports,
            "count_recordstotal" => count($recordstotal),
        ]);

        json::encode($reports, count($recordstotal), count($recordstotal));
    }

    /**
     * Function download
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function download() {
        global $DB;

        $report = optional_param("report", 0, PARAM_INT);
        $courseid = optional_param("courseid", 0, PARAM_INT);

        /** @var local_kopere_dashboard_reprt $koperereports */
        $koperereports = $DB->get_record("local_kopere_dashboard_reprt",
            ["id" => $report]);
        header::notfound_null($koperereports, get_string_kopere("reports_notfound"));

        export::header("xls", self::get_title($koperereports));

        if (strlen($koperereports->prerequisit) && $koperereports->prerequisit == "listCourses") {
            $reports = $DB->get_records_sql($koperereports->reportsql, ["courseid" => $courseid]);
        } else {
            $reports = $DB->get_records_sql($koperereports->reportsql);
        }

        if (strlen($koperereports->foreach)) {
            foreach ($reports as $key => $item) {
                $item = call_user_func($koperereports->foreach, $item);
                $reports[$key] = $item;
            }
        }

        $columns = json_decode($koperereports->columns);
        if (!isset($columns->header)) {
            $columns->header = [];
        }
        $table = new data_table($columns->columns, $columns->header);
        $table->print_header('', false);
        $table->set_row($reports);
        echo '</table>';

        export::close();
    }

    /**
     * Function get_title
     *
     * @param $obj
     *
     * @return string
     * @throws \coding_exception
     */
    private static function get_title($obj) {
        if (strpos($obj->title, '[[[') === 0) {
            return get_string(substr($obj->title, 3, -3));
        } else if (strpos($obj->title, '[[') === 0) {
            return get_string_kopere(substr($obj->title, 2, -2));
        } else {
            return $obj->title;
        }
    }

    /**
     * Function global_menus
     *
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function global_menus() {
        global $DB, $CFG;

        $menus = [];

        $koperereportcats = $DB->get_records("local_kopere_dashboard_rcat", ["enable" => 1]);
        /** @var local_kopere_dashboard_rcat $koperereportcat */
        foreach ($koperereportcats as $koperereportcat) {
            // Executa o SQL e vrifica se o SQL retorna status>0.
            if (strlen($koperereportcat->enablesql)) {
                $status = $DB->get_record_sql($koperereportcat->enablesql);
                if ($status == null || $status->status == 0) {
                    continue;
                }
            }

            if (strpos($koperereportcat->image, 'assets/') === 0) {
                $icon = "{$CFG->wwwroot}/local/kopere_dashboard/{$koperereportcat->image}";
            } else {
                $icon = "{$CFG->wwwroot}/{$koperereportcat->image}";
            }

            $menus[] = (new submenu_util())
                ->set_classname("reports")
                ->set_methodname("dashboard")
                ->set_urlextra("&type={$koperereportcat->type}")
                ->set_title(self::get_title($koperereportcat))
                ->set_icon($icon);
        }

        return $menus;
    }
}
