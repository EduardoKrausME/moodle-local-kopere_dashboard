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
 * Date: 24/09/17
 * Time: 07:17
 */

namespace local_kopere_dashboard\util;

/**
 * Class end_util
 *
 * @package local_kopere_dashboard\util
 */
class end_util {
    /**
     * @param string $print
     */
    public static function end_script_show($print = '') {
        die($print);
    }

    /**
     * @param $motivo
     */
    public static function end_script_header($motivo) {
        header("die-motivo:{$motivo}");
        die();
    }
}
