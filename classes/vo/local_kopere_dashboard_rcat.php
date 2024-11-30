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
 * local_kopere_dashboard_rcat file
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\vo;

/**
 * Class local_kopere_dashboard_rcat
 *
 * @package local_kopere_dashboard\vo
 */
class local_kopere_dashboard_rcat extends \stdClass {

    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $type;

    /** @var string */
    public $image;

    /** @var int */
    public $enable;

    /** @var string */
    public $enablesql;

    /**
     * Function create_by_object
     *
     * @param $item
     *
     * @return local_kopere_dashboard_rcat
     * @throws \coding_exception
     */
    public static function create_by_object($item) {
        $return = new local_kopere_dashboard_rcat();

        $return->id = $item->id;
        $return->title = optional_param("title", $item->title, PARAM_TEXT);
        $return->type = optional_param("type", $item->type, PARAM_TEXT);
        $return->image = optional_param("image", $item->image, PARAM_TEXT);
        $return->enable = optional_param("enable", $item->enable, PARAM_INT);
        $return->enablesql = optional_param("enablesql", $item->enablesql, PARAM_TEXT);

        return $return;
    }

    /**
     * Function create_by_default
     *
     * @return local_kopere_dashboard_rcat
     * @throws \coding_exception
     */
    public static function create_by_default() {
        $return = new local_kopere_dashboard_rcat();

        $return->id = optional_param("id", 0, PARAM_INT);
        $return->title = optional_param("title", '', PARAM_TEXT);
        $return->type = optional_param("type", '', PARAM_TEXT);
        $return->image = optional_param("image", '', PARAM_TEXT);
        $return->enable = optional_param("enable", 1, PARAM_INT);
        $return->enablesql = optional_param("enablesql", '', PARAM_TEXT);

        return $return;
    }
}
