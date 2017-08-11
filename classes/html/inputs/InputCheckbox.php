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

class InputCheckbox extends InputBase {
    /**
     * @var bool
     */
    private $checked = false;

    /**
     * InputCheckbox constructor.
     */
    public function __construct() {
        $this->setType('checkbox');
        $this->setValue('1');
    }

    /**
     * @return InputCheckbox
     */
    public static function newInstance() {
        return new InputCheckbox();
    }

    /**
     * @return bool
     */
    public function isChecked() {
        return $this->checked;
    }

    /**
     * @param $checked
     *
     * @return $this
     */
    public function setChecked($checked) {
        $this->checked = $checked;

        return $this;
    }

    /**
     * @param $configName
     *
     * @return $this
     */
    public function setCheckedByConfig($configName) {
        $this->setName($configName);
        if (get_config('local_kopere_dashboard', $configName)) {
            $this->checked = true;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toString() {
        $returnInput = '<input type="hidden" name="' . $this->name . '" value="0"/>';
        $returnInput .= "<input ";

        $returnInput .= "id=\"$this->name\" name=\"$this->name\" type=\"checkbox\" ";

        if ($this->checked) {
            $returnInput .= 'checked="checked" ';
        }

        if ($this->value) {
            $returnInput .= "value=\"" . htmlentities($this->value) . "\" ";
        }

        if ($this->class) {
            $returnInput .= "class=\"$this->class\" ";
        }

        if ($this->style) {
            $returnInput .= "style=\"$this->style\" ";
        }

        $returnInput .= ">";

        return $returnInput;
    }
}