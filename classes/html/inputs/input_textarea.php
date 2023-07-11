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
 * @created    10/06/17 23:06
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

/**
 * Class input_textarea
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_textarea extends input_base {
    /**
     * input_textarea constructor.
     */
    public function __construct() {
        $this->set_type('textarea');
    }

    /**
     * @return input_textarea
     */
    public static function new_instance() {
        return new input_textarea();
    }

    /**
     * @return string
     */
    public function to_string() {
        $return = "<textarea ";

        $inputid = preg_replace('/[\W]/', '', $this->name);
        $return .= "id='{$inputid}' name='{$this->name}' ";

        if ($this->type) {
            $return .= "type='{$this->type}' ";
        }

        if ($this->class) {
            $return .= "class='{$this->class}' ";
        }

        if ($this->style) {
            $return .= "style='{$this->style}' ";
        }

        $return .= ">";

        if ($this->value) {
            $return .= htmlentities($this->value);
        }

        $return .= "</textarea>";

        return $return;
    }
}
