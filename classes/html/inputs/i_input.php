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
 * i_input file
 *
 * introduced 10/06/17 20:30
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

/**
 * Interface i_input
 *
 * @package local_kopere_dashboard\html\inputs
 */
interface i_input {

    /**
     * Function get_name
     *
     * @return mixed
     */
    public function get_name();

    /**
     * Function set_name
     *
     * @param $name
     *
     * @return mixed
     */
    public function set_name($name);

    /**
     * Function get_type
     *
     * @return mixed
     */
    public function get_type();

    /**
     * Function set_type
     *
     * @param $type
     *
     * @return mixed
     */
    public function set_type($type);

    /**
     * Function get_class
     *
     * @return mixed
     */
    public function get_class();

    /**
     * Function set_class
     *
     * @param $class
     *
     * @return mixed
     */
    public function set_class($class);

    /**
     * Function get_style
     *
     * @return mixed
     */
    public function get_style();

    /**
     * Function set_style
     *
     * @param $style
     *
     * @return mixed
     */
    public function set_style($style);

    /**
     * Function get_value
     *
     * @return mixed
     */
    public function get_value();

    /**
     * Function set_value
     *
     * @param $value
     *
     * @return mixed
     */
    public function set_value($value);

    /**
     * Function set_value_by_config
     *
     * @param $configname
     *
     * @return mixed
     */
    public function set_value_by_config($configname);

    /**
     * Function get_title
     *
     * @return mixed
     */
    public function get_title();

    /**
     * Function set_title
     *
     * @param $title
     *
     * @return mixed
     */
    public function set_title($title);

    /**
     * Function get_description
     *
     * @return mixed
     */
    public function get_description();

    /**
     * Function set_description
     *
     * @param $description
     *
     * @return mixed
     */
    public function set_description($description);

    /**
     * Function set_required
     *
     * @return mixed
     */
    public function set_required();

    /**
     * Function add_validator
     *
     * @param $validator
     *
     * @return mixed
     */
    public function add_validator($validator);

    /**
     * Function to_string
     *
     * @return mixed
     */
    public function to_string();
}
