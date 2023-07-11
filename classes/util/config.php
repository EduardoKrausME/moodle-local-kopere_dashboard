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
 * Date: 20/10/17
 * Time: 17:40
 */

namespace local_kopere_dashboard\util;

/**
 * Class config
 *
 * @package local_kopere_dashboard\util
 */
class config {
    /**
     * @param $key
     * @param string $default
     * @return mixed|string
     */
    public static function get_key($key, $default = '') {
        try {
            $value = get_config('local_kopere_dashboard', $key);
        } catch (\dml_exception $e) {
            return $default;
        }

        try {
            return optional_param($key, $value, PARAM_RAW);
        } catch (\coding_exception $e) {
            return $default;
        }
    }

    /**
     * @param $key
     * @param int $default
     * @return int
     */
    public static function get_key_int($key, $default = 0) {
        try {
            $value = get_config('local_kopere_dashboard', $key);
        } catch (\dml_exception $e) {
            return intval($default);
        }

        try {
            return optional_param($key, $value, PARAM_INT);
        } catch (\coding_exception $e) {
            return intval($default);
        }
    }
}
