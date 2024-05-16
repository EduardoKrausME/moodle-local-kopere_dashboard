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
 * Date: 23/12/2018
 * Time: 12:07
 */

namespace local_kopere_dashboard\util;

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
     * @return mixed
     */
    public function get_classname() {
        return $this->classname;
    }

    /**
     * @param mixed $classname
     * @return submenu_util
     */
    public function set_classname($classname) {
        $this->classname = $classname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function get_methodname() {
        return $this->methodname;
    }

    /**
     * @param mixed $methodname
     * @return submenu_util
     */
    public function set_methodname($methodname) {
        $this->methodname = $methodname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function get_urlextra() {
        return $this->urlextra;
    }

    /**
     * @param mixed $urlextra
     * @return submenu_util
     */
    public function set_urlextra($urlextra) {
        $this->urlextra = $urlextra;
        return $this;
    }

    /**
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return submenu_util
     */
    public function set_title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function get_icon() {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return submenu_util
     */
    public function set_icon($icon) {
        $this->icon = $icon;
        return $this;
    }
}
