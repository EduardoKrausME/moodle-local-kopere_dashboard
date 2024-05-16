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

use local_kopere_dashboard\util\config;

/**
 * Class input_checkbox_select
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_checkbox_select extends input_select {
    /** @var bool */
    private $checked = false;

    /**
     * input_checkbox_select constructor.
     * @throws \coding_exception
     */
    public function __construct() {
        $this->set_value("false");
        $this->set_values(array(
             ['key' => 0, 'value' => get_string('no')],
             ['key' => 1, 'value' => get_string('yes')],
        ));
    }

    /**
     * @return input_checkbox_select
     * @throws \coding_exception
     */
    public static function new_instance() {
        return new input_checkbox_select();
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
     * @throws \coding_exception
     */
    public function set_checked($checked) {
        $this->checked = $checked;

        if ($this->checked) {
            $this->set_value(1);
        } else {
            $this->set_value(0);
        }

        return $this;
    }

    /**
     * @param $configname
     *
     * @return $this
     * @throws \coding_exception
     */
    public function set_checked_by_config($configname) {
        $this->set_name($configname);
        if (config::get_key_int($configname)) {
            $this->set_checked(1);
        } else {
            $this->set_checked(0);
        }

        return $this;
    }
}
