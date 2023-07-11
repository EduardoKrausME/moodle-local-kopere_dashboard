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
 * @created    10/06/17 20:45
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

/**
 * Class input_password
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_password extends input_base {
    /**
     * input_password constructor.
     */
    public function __construct() {
        $this->set_type('password');
    }

    /**
     * @return input_password
     */
    public static function new_instance() {
        return new input_password();
    }

    /**
     * @return mixed|string
     * @throws \coding_exception
     */
    public function to_string() {
        $this->set_value(null);

        return parent::to_string();
    }
}
