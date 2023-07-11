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
 * @created    10/06/17 20:30
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

/**
 * Interface i_input
 *
 * @package local_kopere_dashboard\html\inputs
 */
interface i_input {
    /**
     * @return string
     */
    public function get_name();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function set_name($name);

    /**
     * @return string
     */
    public function get_type();

    /**
     * @param string $type
     *
     * @return $this
     */
    public function set_type($type);

    /**
     * @return string
     */
    public function get_class();

    /**
     * @param string $class
     *
     * @return $this
     */
    public function set_class($class);

    /**
     * @return string
     */
    public function get_style();

    /**
     * @param string $style
     *
     * @return $this
     */
    public function set_style($style);

    /**
     * @return string
     */
    public function get_value();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function set_value($value);

    /**
     * @param $configname
     *
     * @return $this
     */
    public function set_value_by_config($configname);

    /**
     * @return string
     */
    public function get_title();

    /**
     * @param string $title
     *
     * @return $this
     */
    public function set_title($title);

    /**
     * @return string
     */
    public function get_description();

    /**
     * @param string $description
     *
     * @return $this
     */
    public function set_description($description);

    /**
     * @return $this
     */
    public function set_required();

    /**
     * @param $validator
     *
     * @return $this
     */
    public function add_validator($validator);

    /**
     * @return mixed
     */
    public function to_string();
}
