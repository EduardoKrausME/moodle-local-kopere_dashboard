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
 * @created    10/06/17 23:33
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

defined('MOODLE_INTERNAL') || die();

/**
 * Class input_checkbox
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_checkbox extends input_base {
    /**
     * @var bool
     */
    private $checked = false;

    /**
     * input_checkbox constructor.
     */
    public function __construct() {
        $this->set_type('checkbox');
        $this->set_value('1');
    }

    /**
     * @return input_checkbox
     */
    public static function new_instance() {
        return new input_checkbox();
    }

    /**
     * @return bool
     */
    public function is_checked() {
        return $this->checked;
    }

    /**
     * @param $checked
     *
     * @return $this
     */
    public function set_checked($checked) {
        $this->checked = $checked;

        return $this;
    }

    /**
     * @param $configname
     *
     * @return $this
     */
    public function set_checked_by_config($configname) {
        $this->set_name($configname);
        if (get_config('local_kopere_dashboard', $configname)) {
            $this->checked = true;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function to_string() {
        $return = '<input type="hidden" name="' . $this->name . '" value="0"/>';
        $return .= "<input ";

        $return .= "id=\"$this->name\" name=\"$this->name\" type=\"checkbox\" ";

        if ($this->checked) {
            $return .= 'checked="checked" ';
        }

        if ($this->value) {
            $return .= "value=\"" . htmlentities($this->value) . "\" ";
        }

        if ($this->class) {
            $return .= "class=\"$this->class\" ";
        }

        if ($this->style) {
            $return .= "style=\"$this->style\" ";
        }

        $return .= ">";

        return $return;
    }
}