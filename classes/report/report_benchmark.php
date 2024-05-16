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
 * @created    31/01/17 06:30
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;

/**
 * Class report_benchmark
 *
 * @package local_kopere_dashboard\report
 */
class report_benchmark {
    /** @var array */
    private $results = [];

    /**
     * benchmark constructor.
     * @throws \coding_exception
     */
    public function __construct() {
        $tests = [
            'cload',
            'processor',
            'fileread',
            'filewrite',
            'courseread',
            'coursewrite',
            'querytype1',
            'querytype2',
            'loginguest',
            'loginuser',
        ];
        $benchs = [];
        $idtest = 0;

        foreach ($tests as $name) {

            ++$idtest;

            // Inistialize and execute the test.
            $start = microtime(true);
            $result = $this->start_test($name);

            // Populate if empty result.
            empty($result['limit']) ? $result['limit'] = 0 : null;
            empty($result['over']) ? $result['over'] = 0 : null;

            // Overwrite the result if start/stop if defined.
            $overstart = isset($result['start']) ? $result['start'] : $start;
            $overstop = isset($result['stop']) ? $result['stop'] : microtime(true);
            $stop = round($overstop - $overstart, 3);

            // Store and merge result.
            $benchs[$name] = [
                    'during' => $stop,
                    'id' => $idtest,
                    'class' => $this->get_feedback_class($stop, $result['limit'], $result['over']),
                    'name' => get_string($name . 'name', 'local_kopere_dashboard'),
                    'info' => get_string($name . 'moreinfo', 'local_kopere_dashboard'),
                ] + $result;
        }

        // Store all results.
        $this->results = $benchs;
    }

    /**
     * Start a benchmark test
     *
     * @param string $name Test name
     *
     * @return array Test result
     */
    private function start_test($name) {
        return report_benchmark_test::$name();
    }

    /**
     * Get the class with the timer result
     *
     * @param int $during
     * @param int $limit
     * @param int $over
     *
     * @return string Get the class
     */
    private function get_feedback_class($during, $limit, $over) {
        if ($during >= $over) {
            $class = 'bg-danger';
        } else if ($during >= $limit) {
            $class = 'bg-warning';
        } else {
            $class = 'bg-success';
        }

        return $class;
    }

    /**
     * @return array Get the result of all tests
     */
    public function get_results() {
        return $this->results;
    }

    /**
     * @return int
     */
    public function get_total() {
        $total = 0;

        foreach ($this->results as $result) {
            $total += $result['during'];
        }

        return $total;
    }
}
