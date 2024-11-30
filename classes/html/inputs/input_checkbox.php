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
 * input_checkbox file
 *
 * introduced 10/06/17 23:33
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

use local_kopere_dashboard\util\config;

/**
 * Class input_checkbox
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_checkbox extends input_base {
    /** @var bool */
    private $checked = false;

    /**
     * Function new_instance
     *
     * @return input_checkbox
     * @throws \coding_exception
     */
    public static function new_instance() {
        $input = new input_checkbox();
        $input->set_value(1);

        return $input;
    }

    /**
     * Function is_checked
     *
     * @return bool
     */
    public function is_checked() {
        return $this->checked;
    }

    /**
     * Function set_checked
     *
     * @param $checked
     *
     * @return $this
     */
    public function set_checked($checked) {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Function set_checked_by_config
     *
     * @param $configname
     *
     * @return $this
     */
    public function set_checked_by_config($configname) {
        $this->set_name($configname);
        if (config::get_key($configname)) {
            $this->checked = true;
        }

        return $this;
    }

    /**
     * Function to_string
     *
     * @return mixed|string
     */
    public function to_string() {
        $return = "<input id=\"{$this->inputid}\" name=\"{$this->name}\" type=\"checkbox\" ";

        if ($this->value) {
            $value = htmlentities($this->value, ENT_COMPAT);
            $return .= "value=\"{$value}\" ";
        }
        if ($this->style) {
            $return .= "style=\"{$this->style}\" ";
        }
        if ($this->checked) {
            $return .= 'checked="checked" ';
        }

        $return .= $this->extras;

        $return .= "class=\"ios-checkbox {$this->class}\">";

        return $return;
    }
}
