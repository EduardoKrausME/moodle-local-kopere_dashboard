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
 * Introduced  11/07/17 11:12
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class finder_user_util
 *
 * @package local_kopere_dashboard\util
 */
class finder_user_util {

    /**
     * Function find
     *
     * @param $key
     * @param $value
     *
     * @return mixed|null
     * @throws \dml_exception
     */
    public static function find($key, $value) {
        global $DB;

        if (strlen($value) == 0) {
            return null;
        }

        $user = $DB->get_record("user", [$key => $value, "deleted" => 0], '*', IGNORE_MULTIPLE);

        return $user;
    }
}
