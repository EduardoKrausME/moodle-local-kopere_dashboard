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
 * @created    31/01/17 05:09
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\report\report_benchmark;
use local_kopere_dashboard\report\report_benchmark_test;
use local_kopere_dashboard\server\performancemonitor;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\title_util;

/**
 * Class benchmark
 * @package local_kopere_dashboard
 */
class benchmark {

    /**
     *
     * @throws \coding_exception
     */
    public function test() {
        dashboard_util::add_breadcrumb(get_string_kopere('benchmark_title'));
        dashboard_util::start_page(null, 'Performace');

        echo performancemonitor::load_monitor();

        echo '<div class="element-box">';
        mensagem::print_info(get_string_kopere('benchmark_based') . '
                    <a class="alert-link" href="https://moodle.org/plugins/report_benchmark"
                       target="_blank">report_benchmark</a>');

        echo '<div style="text-align: center;">' . get_string_kopere('benchmark_info');
        button::add(get_string_kopere('benchmark_execute'), local_kopere_dashboard_makeurl("benchmark", "execute"));
        echo '</div>';

        echo '</div>';

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function execute() {
        global $CFG;

        dashboard_util::add_breadcrumb(get_string_kopere('benchmark_title'), local_kopere_dashboard_makeurl("benchmark", "test"));
        dashboard_util::add_breadcrumb(get_string_kopere('benchmark_executing'));
        dashboard_util::add_breadcrumb(get_string_kopere('benchmark_title2'));
        dashboard_util::start_page();

        require_once("{$CFG->libdir}/filelib.php");

        echo '<div class="element-box">';

        $test = new report_benchmark();

        $score = $test->get_total();
        if ($score < 4) {
            mensagem::print_info('<strong>' . get_string_kopere('benchmark_timetotal') . '</strong>
                ' . $this->format_number($score) . ' ' . get_string_kopere('benchmark_seconds'));
        } else if ($score < 8) {
            mensagem::print_warning('<strong>' . get_string_kopere('benchmark_timetotal') . '</strong>
                ' . $this->format_number($score) . ' ' . get_string_kopere('benchmark_seconds'));
        } else {
            mensagem::print_danger('<strong>' . get_string_kopere('benchmark_timetotal') . '</strong>
                ' . $this->format_number($score) . ' ' . get_string_kopere('benchmark_seconds'));
        }

        echo '<table class="table" id="benchmarkresult">
                  <thead>
                      <tr>
                          <th class="text-center media-middle">#</th>
                          <th>' . get_string_kopere('benchmark_decription') . '</th>
                          <th class="media-middle">' . get_string_kopere('benchmark_timesec') . '</th>
                          <th class="media-middle">' . get_string_kopere('benchmark_max') . '</th>
                          <th class="media-middle">' . get_string_kopere('benchmark_critical') . '</th>
                      </tr>
                  </thead>
                  <tbody>';

        foreach ($test->get_results() as $result) {
            echo "<tr class='{$result['class']}' >
                      <td class='text-center media-middle'>{$result['id']}</td>
                      <td >{$result['name']}<div><small>{$result['info']}</small></div></td>
                      <td class='text-center media-middle'>{$this->format_number($result['during'])}</td>
                      <td class='text-center media-middle'>{$this->format_number($result['limit'])}</td>
                      <td class='text-center media-middle'>{$this->format_number($result['over'])}</td>
                  </tr>";
        }

        echo '</tbody></table>';

        title_util::print_h3('benchmark_testconf');
        $this->performance();

        echo '</div>';

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function performance() {
        global $CFG;

        echo "<table class='table' id='benchmarkresult'>
                  <tr>
                      <th>" . get_string_kopere('benchmark_testconf_problem') . "</th>
                      <th>" . get_string_kopere('benchmark_testconf_status') . "</th>
                      <th>" . get_string_kopere('benchmark_testconf_description') . "</th>
                      <th>" . get_string_kopere('benchmark_testconf_action') . "</th>
                  </tr>";

        $tests = [
            report_benchmark_test::themedesignermode(),
            report_benchmark_test::cachejs(),
            report_benchmark_test::debug(),
            report_benchmark_test::backup_auto_active(),
            report_benchmark_test::enablestats()
        ];

        foreach ($tests as $test) {
            echo "<tr class='{$test['class']}'>
                      <td>{$test['title']}</td>
                      <td>{$test['resposta']}</td>
                      <td>{$test['description']}</td>
                      <td><a target='_blank' href='{$CFG->wwwroot}/admin/{$test['url']}'>" . get_string('edit', '') . "</a></td>
                  </tr>";
        }

        echo '</tbody></table>';
    }

    /**
     * @param $number
     * @return mixed
     * @throws \coding_exception
     */
    private function format_number($number) {
        return str_replace('.', get_string('decsep', 'langconfig'), $number);
    }
}
