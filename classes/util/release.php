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
 * Date: 15/07/17
 * Time: 13:52
 */

namespace local_kopere_dashboard\util;

/**
 * Class release
 *
 * @package local_kopere_dashboard\util
 */
class release {
    /**
     * @return string
     */
    public static function version() {
        global $CFG;
        $releases = explode('.', $CFG->release);

        return intval($releases[0]) + (intval($releases[1]) * 0.1);
    }
}
