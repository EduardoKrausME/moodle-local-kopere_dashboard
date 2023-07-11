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
 * Date: 15/11/2018
 * Time: 11:41
 */

namespace local_kopere_dashboard\util;

class url_util {
    /**
     * @return mixed
     * @throws \coding_exception
     */
    public static function querystring() {
        return "?" . clean_param($_SERVER['QUERY_STRING'], PARAM_TEXT);
    }
}
