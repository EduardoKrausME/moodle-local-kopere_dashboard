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
 * @created    25/05/17 16:00
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\chartsjs;

class Base {
    private static $_isSend = false;

    private static $chartColors
        = array(
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        );

    private static $_lastColor = 0;

    protected static function getColor() {
        if (self::$_lastColor >= count(self::$chartColors)) {
            self::$_lastColor = 0;
        }

        return "'" . self::$chartColors[self::$_lastColor++] . "'";
    }

    protected static function start() {
        global $CFG;

        if (self::$_isSend) {
            return;
        }

        echo "<script src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/chartjs/Chart.bundle.js\" charset=\"utf-8\"></script>";
    }
}