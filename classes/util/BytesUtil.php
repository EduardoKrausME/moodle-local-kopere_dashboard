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

class BytesUtil {

    private static $divisor = 1000;

    /**
     * @param int $_bytes
     *
     * @return string
     */
    public static function sizeToByte($_bytes) {
        $_bytes = intval($_bytes);

        if ($_bytes == 0) {
            return '0B';
        }
        $bytes = $_bytes / self::$divisor;
        if ($bytes < 1000) {
            return self::removeZero(number_format($bytes, 0, ',', '.') . ' KB', 0);
        }

        $bytes = $_bytes / self::$divisor / self::$divisor;
        if ($bytes < 1000) {
            return self::removeZero(number_format($bytes, 0, ',', '.') . ' MB', 0);
        }

        $bytes = $_bytes / self::$divisor / self::$divisor / self::$divisor;
        if ($bytes < 1000) {
            return self::removeZero(number_format($bytes, 1, ',', '.') . ' GB', 1);
        }

        $bytes = $_bytes / self::$divisor / self::$divisor / self::$divisor / self::$divisor;

        return self::removeZero(number_format($bytes, 2, ',', '.') . ' TB', 2);
    }

    /**
     * @param string $texto
     *
     * @return string
     */
    private static function removeZero($texto, $count) {
        if ($count == 3) {
            return str_replace(',000', '', $texto);
        } else if ($count == 2) {
            return str_replace(',00', '', $texto);
        } else {
            return str_replace(',0', '', $texto);
        }
    }

    public static function getDurationSegundos($value) {
        $partes = explode(':', $value);

        return ($partes[0] * 60 * 60) + ($partes[1] * 60) + $partes[2];
    }

}