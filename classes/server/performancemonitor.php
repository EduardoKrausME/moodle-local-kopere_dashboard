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
 * @created    20/05/17 18:20
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\server;

use local_kopere_dashboard\util\server_util;

defined('MOODLE_INTERNAL') || die();

/**
 * Class performancemonitor
 * @package local_kopere_dashboard
 */
class performancemonitor {

    /**
     * @throws \coding_exception
     * @return string
     */
    public static function load_monitor() {
        global $PAGE;

        $PAGE->requires->js_call_amd('local_kopere_dashboard/monitor', 'init');

        return '
            <div class="element-content">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="element-box color_cpu">
                            <div class="label">' . get_string_kopere('performancemonitor_cpu') . '</div>
                            <div class="value"><span>
                            ' . performancemonitor::cpu(false) . '
                            </span></div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="element-box color_memory">
                            <div class="label">' . get_string_kopere('performancemonitor_memory') . '</div>
                            <div class="value"><span>
                                ' . performancemonitor::memory(false) . '%
                            </span></div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="element-box color_hd">
                            <div class="label">' . get_string_kopere('performancemonitor_hd') . '</div>
                            <div class="value"><span id="load_monitor-performancemonitor_hd"></span></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="element-box color_performance">
                            <div class="label">' . get_string_kopere('performancemonitor_performance') . '</div>
                            <div class="value"><span>
                                ' . performancemonitor::load_average(false) . '
                            </span></div>
                        </div>
                    </div>
                </div>
            </div>';
    }

    /**
     * @param $return_number
     * @return int|string
     * @throws \coding_exception
     */
    public static function cpu($return_number) {

        if (!server_util::function_enable('shell_exec')) {
            if ($return_number) {
                return -1;
            }
            return "Function disabled by hosting";
        }


        $inputline = shell_exec('top -b -n 2');

        preg_match_all(
            "/Cpu.*?([0-9.]+).*?us.*?([0-9.]+).*?sy.*?([0-9.]+).*?ni/",
            $inputline, $outputcpuprocess);

        $us = $outputcpuprocess[1][1];
        $sy = $outputcpuprocess[2][1];
        $ni = $outputcpuprocess[3][1];

        if ($return_number) {
            return $us + $sy + $ni;
        }

        return ' us: ' . number_format($us, 1, get_string('decsep', 'langconfig'), '') . '%, sys: ' .
            number_format($ni, 1, get_string('decsep', 'langconfig'), '') . '%';
    }

    /**
     *
     */
    public static function memory($return_number) {

        if (!server_util::function_enable('shell_exec')) {
            if ($return_number) {
                return -1;
            }
            return "Function disabled by hosting";
        }

        $inputlines = shell_exec("cat /proc/meminfo");
        preg_match("/MemFree:\s*([0-9]+)/", $inputlines, $outputmemfreee);
        preg_match("/MemTotal:\s*([0-9]+)/", $inputlines, $outputmemtotal);

        $free = $outputmemfreee[1];
        $all = $outputmemtotal[1];

        if ($return_number) {
            return 100 - (($free / $all) * 100);
        } else {
            return number_format(100 - (($free / $all) * 100), 1, ",", ".");
        }
    }

    /**
     * @param $return_number
     * @return mixed|string
     * @throws \Exception
     */
    public static function disk_moodledata($return_number) {
        global $CFG;

        $cache = \cache::make('local_kopere_dashboard', 'performancemonitor');
        if ($cache->has('server')) {
            return $cache->get('server');
        }

        if (!server_util::function_enable('shell_exec')) {
            if ($return_number) {
                return -1;
            }
            return "Function disabled by hosting";
        }

        $lines = shell_exec("du -h {$CFG->dataroot}");

        $lines = trim($lines);
        $pos = strrpos($lines, "\n");
        $lastLine = substr($lines, $pos);
        $bytes = explode("\t", $lastLine)[0];

        $cache->set('server', $bytes);

        return $bytes;
    }

    /**
     *
     */
    public static function load_average($return_number) {

        if (!server_util::function_enable('shell_exec')) {
            if ($return_number) {
                return -1;
            }
            return "Function disabled by hosting";
        }

        $inputlines = shell_exec("uptime");
        preg_match("/average[s]?:\s*([0-9.]+),\s*([0-9.]+),\s*([0-9.]+)/", $inputlines, $outputload);

        if ($return_number) {
            return $outputload[1];
        }

        $return = get_string_kopere('performancemonitor_min', 1) . $outputload[1] . '%, ';
        $return .= get_string_kopere('performancemonitor_min', 5) . $outputload[3] . '%';

        return $return;
    }

    /**
     *
     */
    public static function online() {
        global $DB;

        $param = array(
            'timefrom' => time() - 300, // 300 - 5 minute
            'now' => time()
        );
        $sql = "SELECT COUNT( DISTINCT id) AS cont
                  FROM {user_lastaccess}
                 WHERE timeaccess >  :timefrom
                   AND timeaccess <= :now";

        $online = $DB->get_record_sql($sql, $param);

        return $online->cont;
    }
}

