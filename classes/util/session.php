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
 * Introduced  24/03/2018 06:26
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class session
 *
 * @package local_kopere_dashboard\util
 */
class session {

    /**
     * Function get
     *
     * @param $key
     *
     * @return null
     */
    public static function get($key) {
        if (isset($_SESSION["kopere-dashboard-{$key}"])) {
            return $_SESSION["kopere-dashboard-{$key}"];
        } else {
            return null;
        }
    }

    /**
     * Function set
     *
     * @param $key
     * @param $value
     */
    public static function set($key, $value) {
        $_SESSION["kopere-dashboard-{$key}"] = $value;
    }
}
