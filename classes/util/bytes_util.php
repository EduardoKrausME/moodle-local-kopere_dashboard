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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class bytes_util
 *
 * @package local_kopere_dashboard\util
 */
class bytes_util {

    /** @var int */
    private static $divisor = 1000;

    /**
     * @param int $bytes
     *
     * @return string
     * @throws \coding_exception
     */
    public static function size_to_byte($bytes) {
        $bytes = intval($bytes);

        if ($bytes == 0) {
            return '0B';
        }
        $bytes = $bytes / self::$divisor;
        if ($bytes < 1000) {
            return self::remove_zero(number_format($bytes, 0, get_string('decsep', 'langconfig'),
                    get_string('thousandssep', 'langconfig')) . ' KB', 0);
        }

        $bytes = $bytes / self::$divisor;
        if ($bytes < 1000) {
            return self::remove_zero(number_format($bytes, 0, get_string('decsep', 'langconfig'),
                    get_string('thousandssep', 'langconfig')) . ' MB', 0);
        }

        $bytes = $bytes / self::$divisor;
        if ($bytes < 1000) {
            return self::remove_zero(number_format($bytes, 1, get_string('decsep', 'langconfig'),
                    get_string('thousandssep', 'langconfig')) . ' GB', 1);
        }

        $bytes = $bytes / self::$divisor;
        return self::remove_zero(number_format($bytes, 2, get_string('decsep', 'langconfig'),
                get_string('thousandssep', 'langconfig')) . ' TB', 2);
    }

    /**
     * @param $texto
     * @param $count
     * @return mixed
     */
    private static function remove_zero($texto, $count) {
        if ($count == 3) {
            return str_replace(',000', '', $texto);
        } else if ($count == 2) {
            return str_replace(',00', '', $texto);
        } else {
            return str_replace(',0', '', $texto);
        }
    }

    /**
     * @param $value
     *
     * @return int
     */
    public static function et_duration_segundos($value) {
        $partes = explode(':', $value);

        return ($partes[0] * 60 * 60) + ($partes[1] * 60) + $partes[2];
    }
}
