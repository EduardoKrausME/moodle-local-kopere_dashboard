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
 * url_util file
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class url_util
 *
 * @package local_kopere_dashboard\util
 */
class url_util {
    /**
     * make url.
     *
     * @param string $classname
     * @param string $method
     * @param array $params
     * @param string $file
     *
     * @return string
     */
    public static function makeurl($classname, $method, $params = [], $file = "view") {
        $query = "classname={$classname}&method={$method}";
        foreach ($params as $key => $param) {
            $query .= "&{$key}={$param}";
        }

        global $CFG;
        return "{$CFG->wwwroot}/local/kopere_dashboard/{$file}.php?{$query}";
    }

    /**
     * Function params
     *
     * @return array
     * @throws \coding_exception
     */
    public static function params() {
        parse_str($_SERVER["QUERY_STRING"], $params);

        $returnparams = [];
        foreach ($params as $key => $value) {
            if ($key === "classname" || $key === "method") {
                $returnparams[$key] = required_param($key, PARAM_TEXT);
            } else if (str_ends_with($key, "id")) {
                $returnparams[$key] = required_param($key, PARAM_INT);
            } else {
                $returnparams[$key] = required_param($key, PARAM_TEXT);
            }
        }

        return $returnparams;
    }
}
