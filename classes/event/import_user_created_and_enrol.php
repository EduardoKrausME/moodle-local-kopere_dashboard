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
 * Date: 18/08/17
 * Time: 12:21
 */

namespace local_kopere_dashboard\event;

use core\event\base;

/**
 * Class import_user_created_and_enrol
 *
 * @package local_kopere_dashboard\event
 */
class import_user_created_and_enrol extends base {
    /**
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['action'] = 'created';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'user';
    }

    /**
     * @return string
     * @throws \coding_exception
     */
    public static function get_name() {
        return get_string('userimport_import_user_created_and_enrol_name', 'local_kopere_dashboard');
    }
}
