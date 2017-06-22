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

use local_kopere_dashboard\report\custom\InfoInterface;
use local_kopere_dashboard\report\custom\ReportInterface;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;

class Reports {

    /**
     * @return array
     */
    public static function globalMenus() {
        global $CFG;

        $types = glob($CFG->dirroot . '/local/kopere_dashboard/classes/report/custom/*/Info.php');

        $menus = array();

        if (strpos($_SERVER['QUERY_STRING'], 'Reports::') === 0) {

            foreach ($types as $typePath) {
                $dirname = pathinfo($typePath, PATHINFO_DIRNAME);

                preg_match("/classes\/report\/custom\/(.*?)\/Info\.php/", $typePath, $typeInfo);
                $icon = str_replace($CFG->dirroot, '', $dirname);

                $className = "local_kopere_dashboard\\report\\custom\\{$typeInfo[1]}\\Info";
                /** @var InfoInterface $class */
                $class = new $className();

                if (!$class->isEnable()) {
                    continue;
                }

                $menus[] = array('Reports::dashboard&type=' . $typeInfo[1],
                    $class->getTypeName(),
                    "{$CFG->wwwroot}{$icon}/icon.svg"
                );
            }
        }

        return $menus;
    }

    public function dashboard() {
        global $CFG;

        DashboardUtil::startPage('Relatórios');

        echo '<div class="element-box">';
        echo '<h2>Relatórios</h2>';

        $type = optional_param('type', '*', PARAM_TEXT);

        $types = glob("{$CFG->dirroot}/local/kopere_dashboard/classes/report/custom/{$type}/Info.php");

        foreach ($types as $type) {
            $dirname = pathinfo($type, PATHINFO_DIRNAME);

            preg_match("/classes\/report\/custom\/(.*?)\/Info\.php/", $type, $typeInfo);
            $icon = str_replace($CFG->dirroot, '', $dirname);

            $className = "local_kopere_dashboard\\report\\custom\\{$typeInfo[1]}\\Info";
            /** @var InfoInterface $class */
            $class = new $className();

            if (!$class->isEnable()) {
                continue;
            }

            echo "<h3><img src='{$CFG->wwwroot}{$icon}/icon.svg' alt='Icon' height='23' width='23' > " .
                $class->getTypeName() . "</h3>";

            $reports = glob($dirname . '/reports/*.php');

            foreach ($reports as $report) {
                preg_match("/classes\/report\/custom\/(.*?)\/reports\/(.*?)\.php/", $report, $reportInfo);

                $className = "local_kopere_dashboard\\report\\custom\\{$reportInfo[1]}\\reports\\{$reportInfo[2]}";
                /** @var ReportInterface $class */
                $class = new $className();

                if ($class->isEnable()) {
                    echo "<h4 style='padding-left: 31px;'><a href='?Reports::loadReport&type={$reportInfo[1]}&report={$reportInfo[2]}'>{$class->reportName}</a></h4>";
                }
            }
        }

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function loadReport() {
        global $CFG;

        $type = optional_param('type', '', PARAM_TEXT);
        $report = optional_param('report', '', PARAM_TEXT);

        $reportFile = $CFG->dirroot . "/local/kopere_dashboard/classes/report/custom/$type/reports/$report.php";

        if (!file_exists($reportFile)) {
            Header::notfound('Relatório não localizado!');
        }

        $className = "local_kopere_dashboard\\report\\custom\\$type\\reports\\$report";
        /** @var ReportInterface $class */
        $class = new $className();

        DashboardUtil::startPage(array(
            array('Reports::dashboard', 'Relatórios'),
            $class->name()
        ));
        echo '<div class="element-box table-responsive">';

        $class->generate();

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function listData() {
        global $CFG;

        $type = optional_param('type', '', PARAM_TEXT);
        $report = optional_param('report', '', PARAM_TEXT);

        $reportFile = $CFG->dirroot . "/local/kopere_dashboard/classes/report/custom/$type/reports/$report.php";

        if (!file_exists($reportFile)) {
            Header::notfound('Relatório não localizado!');
        }

        $className = "local_kopere_dashboard\\report\\custom\\$type\\reports\\$report";
        /** @var ReportInterface $class */
        $class = new $className();

        $class->listData();
    }
}