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
 * benchmark file
 *
 * introduced 31/01/17 05:09
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\report\report_benchmark;
use local_kopere_dashboard\report\report_benchmark_test;
use local_kopere_dashboard\server\performancemonitor;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\util\url_util;

/**
 * Class benchmark
 *
 * @package local_kopere_dashboard
 */
class benchmark {

    /**
     * Function test
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function test() {
        dashboard_util::add_breadcrumb(get_string("benchmark_title", "local_kopere_dashboard"));
        dashboard_util::start_page(null, "Performace");

        echo performancemonitor::load_monitor();

        echo '<div class="element-box">';
        message::print_info(get_string("benchmark_based", "local_kopere_dashboard") . '
                    <a class="alert-link" href="https://moodle.org/plugins/report_benchmark"
                       target="_blank">report_benchmark</a>');

        echo '<div style="text-align: center;">' . get_string("benchmark_info", "local_kopere_dashboard");
        button::add(get_string("benchmark_execute", "local_kopere_dashboard"), url_util::makeurl("benchmark", "execute"));
        echo "</div>";

        echo "</div>";

        dashboard_util::end_page();
    }

    /**
     * Function execute
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function execute() {
        global $CFG, $OUTPUT;

        dashboard_util::add_breadcrumb(get_string("benchmark_title", "local_kopere_dashboard"),
            url_util::makeurl("benchmark", "test"));
        dashboard_util::add_breadcrumb(get_string("benchmark_executing", "local_kopere_dashboard"));
        dashboard_util::add_breadcrumb(get_string("benchmark_title2", "local_kopere_dashboard"));
        dashboard_util::start_page();

        require_once("{$CFG->libdir}/filelib.php");

        echo '<div class="element-box">';

        $test = new report_benchmark();

        $score = $test->get_total();
        if ($score < 4) {
            message::print_info("<strong>" . get_string("benchmark_timetotal", "local_kopere_dashboard") . '</strong>
                ' . $this->format_number($score) . ' ' . get_string("benchmark_seconds", "local_kopere_dashboard"));
        } else if ($score < 8) {
            message::print_warning("<strong>" . get_string("benchmark_timetotal", "local_kopere_dashboard") . '</strong>
                ' . $this->format_number($score) . ' ' . get_string("benchmark_seconds", "local_kopere_dashboard"));
        } else {
            message::print_danger("<strong>" . get_string("benchmark_timetotal", "local_kopere_dashboard") . '</strong>
                ' . $this->format_number($score) . ' ' . get_string("benchmark_seconds", "local_kopere_dashboard"));
        }

        $data = [
            "tests" => array_values($test->get_results()),
        ];
        echo $OUTPUT->render_from_template("local_kopere_dashboard/benchmark_execute", $data);

        title_util::print_h3("benchmark_testconf");
        $this->performance();

        echo "</div>";

        dashboard_util::end_page();
    }

    /**
     * Function performance
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function performance() {
        global $OUTPUT;

        $tests = [
            report_benchmark_test::themedesignermode(),
            report_benchmark_test::cachejs(),
            report_benchmark_test::debug(),
            report_benchmark_test::backup_auto_active(),
            report_benchmark_test::enablestats(),
        ];
        echo $OUTPUT->render_from_template("local_kopere_dashboard/benchmark_performance", ["tests" => $tests]);

    }

    /**
     * Function format_number
     *
     * @param $number
     *
     * @return mixed
     * @throws \coding_exception
     */
    private function format_number($number) {
        return str_replace(".", get_string("decsep", "langconfig"), $number);
    }
}
