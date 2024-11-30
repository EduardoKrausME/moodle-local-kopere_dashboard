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
 * Introduced  23/12/2018 12:07
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class submenu_util
 *
 * @package local_kopere_dashboard\util
 */
class submenu_util {
    /** @var string */
    private $classname;
    /** @var string */
    private $methodname;
    /** @var string */
    public $urlextra;
    /** @var string */
    private $title;
    /** @var string */
    private $icon;

    /**
     * Function get_classname
     *
     * @return string
     */
    public function get_classname() {
        return $this->classname;
    }

    /**
     * Function set_classname
     *
     * @param $classname
     *
     * @return $this
     */
    public function set_classname($classname) {
        $this->classname = $classname;
        return $this;
    }

    /**
     * Function get_methodname
     *
     * @return string
     */
    public function get_methodname() {
        return $this->methodname;
    }

    /**
     * Function set_methodname
     *
     * @param $methodname
     *
     * @return $this
     */
    public function set_methodname($methodname) {
        $this->methodname = $methodname;
        return $this;
    }

    /**
     * Function get_urlextra
     *
     * @return string
     */
    public function get_urlextra() {
        return $this->urlextra;
    }

    /**
     * Function set_urlextra
     *
     * @param $urlextra
     *
     * @return $this
     */
    public function set_urlextra($urlextra) {
        $this->urlextra = $urlextra;
        return $this;
    }

    /**
     * Function get_title
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Function set_title
     *
     * @param $title
     *
     * @return $this
     */
    public function set_title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Function get_icon
     *
     * @return string
     */
    public function get_icon() {
        return $this->icon;
    }

    /**
     * Function set_icon
     *
     * @param $icon
     *
     * @return $this
     */
    public function set_icon($icon) {
        $this->icon = $icon;
        return $this;
    }
}
