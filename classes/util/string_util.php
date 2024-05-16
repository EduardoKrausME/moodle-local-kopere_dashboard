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
 * @created    23/05/17 18:24
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class string_util
 *
 * @package local_kopere_dashboard\util
 */
class string_util {
    /**
     * @param int $length
     *
     * @return string
     */
    public static function generate_random_string($length = 10) {
        $characters = '123456789';
        $characters .= 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $characters .= 'abcdefghjkmnpqrstuvwxyz';

        $lengthstring = strlen($characters);
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, $lengthstring - 1)];
        }

        return $string;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function generate_random_password($length = 10) {
        $characters = '123456789';
        $characters .= 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $characters .= 'abcdefghjkmnpqrstuvwxyz';
        $characters .= '!@#$%*()+=-{}[]:;<>?~!@#$%*()+=-{}[]:;<>?~';

        $lengthstring = strlen($characters);
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, $lengthstring - 1)];
        }

        return $string;
    }

    /**
     * @param string $id
     * @return string
     */
    public static function generate_uid($id = null) {
        if ($id) {
            return strtolower(
                substr($id, 0, 8) . '-' .
                substr($id, 8, 4) . '-' .
                substr($id, 12, 4) . '-' .
                substr($id, 24, 12)
            );
        } else {
            return strtolower(
                self::generate_random_string(8) . '-' .
                self::generate_random_string(4) . '-' .
                self::generate_random_string(4) . '-' .
                self::generate_random_string(12)
            );
        }
    }

    /**
     * @param $param
     * @param $default
     * @param $type
     *
     * @return array|mixed
     */
    public static function clear_all_params($param, $default, $type) {
        $post = $_POST;
        if ($param == null) {
            return self::clear_params_array($post, $type);
        }

        if (!isset($post[$param])) {
            return $default;
        }

        if (is_string($post[$param])) {
            return self::clear_params_array($param, $type);
        }

        return self::clear_params_array($post[$param], $type);
    }

    /**
     * @param $in
     * @param $type
     *
     * @return array|mixed
     */
    private static function clear_params_array($in, $type) {
        $out = [];
        if (is_array($in)) {
            foreach ($in as $key => $value) {
                $out[$key] = self::clear_params_array($value, $type);
            }
        } else if (is_string($in)) {
            try {
                return clean_param($in, $type);
            } catch (\coding_exception $e) {
                debugging($e->getMessage());
            }
        } else {
            return $in;
        }

        return $out;
    }
}
