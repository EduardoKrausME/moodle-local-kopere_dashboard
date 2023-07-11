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
 * User: Eduardo Kraus
 * Date: 10/08/17
 * Time: 18:38
 */

namespace local_kopere_dashboard\util;

/**
 * Class date_util
 *
 * @package local_kopere_dashboard\util
 */
class date_util {
    /**
     * @param $datetime
     *
     * @return false|int
     */
    public static function convert_to_time($datetime) {
        if (is_numeric($datetime)) {
            if ($datetime > 1000000000) {
                return $datetime;
            }
        }

        $date = \DateTime::createFromFormat('Y-m-d H:i', $datetime);
        if ($date) {
            return $date->getTimestamp();
        }

        $date = \DateTime::createFromFormat('Y-m-d', $datetime);
        if ($date) {
            return $date->getTimestamp();
        }

        $date = \DateTime::createFromFormat('d/m/Y H:i', $datetime);
        if ($date) {
            return $date->getTimestamp();
        }

        return strtotime($datetime);
    }
}
