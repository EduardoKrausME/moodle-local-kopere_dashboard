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

namespace local_kopere_dashboard;

defined('MOODLE_INTERNAL') || die();

/**
 * Class performancemonitor
 * @package local_kopere_dashboard
 */
class performancemonitor {

    /**
     * @var
     */
    private $start_time;
    /**
     * @var
     */
    private $soma_rede_in_start;
    /**
     * @var
     */
    private $soma_rede_out_start;

    /**
     *
     */
    public function network_start() {
        $this->start_time = microtime(true);

        $input_lines = shell_exec("cat /proc/net/dev");
        preg_match_all(
            "/\s*([a-z0-9]+):\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)/",
            $input_lines, $output_net);

        $this->soma_rede_in_start = $this->soma_rede_out_start = 0;
        foreach ($output_net[2] as $rede) {
            $this->soma_rede_in_start += $rede;
        }
        foreach ($output_net[10] as $rede) {
            $this->soma_rede_out_start += $rede;
        }
    }

    /**
     *
     */
    public function network_end() {
        $input_lines = shell_exec("cat /proc/net/dev");
        preg_match_all(
            "/\s*([a-z0-9]+):\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)/",
            $input_lines, $output_net);

        $end_time = microtime(true);

        $soma_rede_in_end = $soma_rede_out_end = 0;
        foreach ($output_net[2] as $rede) {
            $soma_rede_in_end += $rede;
        }
        foreach ($output_net[10] as $rede) {
            $soma_rede_out_end += $rede;
        }

        echo '<div class="part-monitor" id="network">';
        echo '<h3>Rede Videoteca</h3>';

        $bytes = $soma_rede_in_end - $this->soma_rede_in_start;
        $seconds = $end_time - $this->start_time;

        $bytes_per_second = $bytes / $seconds;
        $bits_per_second = $bytes_per_second * 8;

        echo '<span class="in">in: ';
        if ($bits_per_second > 1024 * 1024) {
            echo number_format($bits_per_second / 1024 / 1024, 2, get_string('decsep', 'langconfig'), get_string('thousandssep', 'langconfig')) . " Mbit/s";
        } else if ($bits_per_second > 1024) {
            echo number_format($bits_per_second / 1024, 2, get_string('decsep', 'langconfig'), get_string('thousandssep', 'langconfig')) . " Kbit/s";
        } else {
            echo("< 1 Kbit/s");
        }
        echo '</span>';

        $bytes = $soma_rede_out_end - $this->soma_rede_out_start;
        $seconds = $end_time - $this->start_time;

        $bytes_per_second = $bytes / $seconds;
        $bits_per_second = $bytes_per_second * 8;

        echo '<br><span class="out">out: ';
        if ($bits_per_second > 1024 * 1024 * 8) {
            echo number_format($bits_per_second / 1024 / 1024, 2, get_string('decsep', 'langconfig'), get_string('thousandssep', 'langconfig')) . " Mbit/s";
        } else if ($bits_per_second > 1024 * 8) {
            echo number_format($bits_per_second / 1024, 2, get_string('decsep', 'langconfig'), get_string('thousandssep', 'langconfig')) . " Kbit/s";
        } else {
            echo("< 1 Kbit/s");
        }
        echo '</span>';

        echo '</div>';
    }

    /**
     *
     */
    public function cpu() {
        echo '<div class="part-monitor" id="cpu">';
        echo '<h3>Uso do CPU</h3>';

        if ($_SESSION ['cpus'] == null) {
            $input_lines = shell_exec('cat /proc/stat');
            preg_match_all(
                "/cpu\d+ ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+)/",
                $input_lines, $output_cpu_count);

            $_SESSION ['cpus'] = count($output_cpu_count[0]);
        }

        $input_line = shell_exec('top -b -n 2');
        preg_match_all(
            "/Cpu.*?([0-9.]+)%us.*?([0-9.]+)%sy.*?([0-9.]+)%ni.*?([0-9.]+)%id.*?([0-9.]+)%wa.*?([0-9.]+)%hi.*?([0-9.]+)%si.*?([0-9.]+)%st/",
            $input_line, $output_cpu_process);

        $us = $output_cpu_process[1][1];
        $sy = $output_cpu_process[2][1];
        $ni = $output_cpu_process[3][1];
        // $id = $output_cpu_process[ 4 ][ 1 ];
        // $wa = $output_cpu_process[ 5 ][ 1 ];
        // $hi = $output_cpu_process[ 6 ][ 1 ];
        // $si = $output_cpu_process[ 7 ][ 1 ];
        // $st = $output_cpu_process[ 8 ][ 1 ];

        echo $_SESSION ['cpus'] . ' CORES - ';
        echo number_format($us + $sy + $ni, 1, get_string('decsep', 'langconfig'), '') . '% <br/>';
        echo ' us: ' . number_format($us, 1, get_string('decsep', 'langconfig'), '') . '%, sys: ' .
            number_format($ni, 1, get_string('decsep', 'langconfig'), '') . '%';
        echo '</div>';
    }

    /**
     *
     */
    public function memory() {
        $input_lines = shell_exec("cat /proc/meminfo");
        preg_match("/MemFree:\s*([0-9]+)/", $input_lines, $output_mem_freee);
        preg_match("/MemTotal:\s*([0-9]+)/", $input_lines, $output_mem_total);
        // preg_match ( "/Cached:\s*([0-9]+)/", $input_lines, $output_mem_cache );

        $free = $output_mem_freee[1];
        $all = $output_mem_total[1];
        // $cache = $output_mem_cache[ 1 ];

        echo '<div class="part-monitor" id="memoria">';
        echo '<h3>Memória</h3>';

        echo 'Total: ';
        if ($all > 1024 * 1024) {
            echo intval($all / 1024 / 1024) . " GB";
        } else if ($all > 1024) {
            echo intval($all / 1024) . " MB";
        } else {
            echo intval($all) . " KB";
        }

        echo '<br/>Livre: ';
        if ($free > 1024 * 1024) {
            echo intval($free / 1024 / 1024) . " GB";
        } else if ($free > 1024) {
            echo intval($free / 1024) . " MB";
        } else {
            echo intval($free) . " KB";
        }

        // echo '<br/>Cache: ';
        // if ( $cache > 1024 * 1024 ) {
        // echo intval ( $cache / 1024 / 1024 ) . " GB";
        // } else if ( $cache > 1024 ) {
        // echo intval ( $cache / 1024 ) . " MB";
        // } else {
        // echo intval ( $cache ) . " KB";

        echo '</div>';
    }

    /**
     *
     */
    public function disk() {
        $input_lines = shell_exec("df -h");
        preg_match_all("/([0-9,\.]+\w)\s+([0-9]+%)\s+\/\n/", $input_lines, $output_disco_linha1);
        preg_match_all("/([0-9,\.]+\w)\s+([0-9]+%)\s+\/var\/www/", $input_lines, $output_disco_linha2);

        echo '<div class="part-monitor" id="disco">';
        echo '<h3>HD (livre)</h3>';

        echo 'SO: ';
        echo $output_disco_linha1[1][0] . 'B ';
        echo $output_disco_linha1[2][0] . '';

        echo '<br/>Dados: ';
        echo $output_disco_linha2[1][0] . 'B ';
        echo $output_disco_linha2[2][0] . '';

        echo '</div>';
    }

    /**
     *
     */
    public function load_average() {
        $input_lines = shell_exec("uptime");
        preg_match("/average[s]?:\s*([0-9.]+),\s*([0-9.]+),\s*([0-9.]+)/", $input_lines, $output_load);

        echo '<div class="part-monitor" id="average">';
        echo '<h3>Desempenho</h3>';

        echo ' 1 min: ' . $output_load[1] . '%<br/>';
        // echo ' 5 min: ' . $output_load[ 2 ] . '%<br/>';
        echo '15 min: ' . $output_load[3] . '%';

        echo '</div>';
    }
}

