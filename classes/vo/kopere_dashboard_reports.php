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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\vo;

/**
 * Class kopere_dashboard_reports
 * @package local_kopere_dashboard\vo
 */
class kopere_dashboard_reports extends \stdClass {

    /** @var int */
    public $id;

    /** @var int */
    public $reportcatid;

    /** @var string */
    public $reportkey;

    /** @var string */
    public $title;

    /** @var int */
    public $enable;

    /** @var string */
    public $enablesql;

    /** @var string */
    public $reportsql;

    /** @var string */
    public $prerequisit;

    /** @var string */
    public $columns;

    /** @var string */
    public $foreach;

    /**
     * @param $item
     * @return kopere_dashboard_reports
     * @throws \coding_exception
     */
    public static function create_by_object($item) {
        $return = new kopere_dashboard_reports();

        $return->id = $item->id;
        $return->reportcatid = optional_param('reportcatid', $item->reportcatid, PARAM_INT);
        $return->reportkey = optional_param('reportkey', $item->reportkey, PARAM_TEXT);
        $return->title = optional_param('title', $item->title, PARAM_TEXT);
        $return->enable = optional_param('enable', $item->enable, PARAM_INT);
        $return->enablesql = optional_param('enablesql', $item->enablesql, PARAM_TEXT);
        $return->reportsql = optional_param('reportsql', $item->reportsql, PARAM_TEXT);
        $return->prerequisit = optional_param('prerequisit', $item->prerequisit, PARAM_TEXT);
        $return->columns = optional_param('columns', $item->columns, PARAM_TEXT);
        $return->foreach = optional_param('foreach', $item->foreach, PARAM_TEXT);

        return $return;
    }

    /**
     * @return kopere_dashboard_reports
     * @throws \coding_exception
     */
    public static function create_by_default() {
        $return = new kopere_dashboard_reports();

        $return->id = optional_param('id', 0, PARAM_INT);
        $return->reportcatid = optional_param('reportcatid', 0, PARAM_INT);
        $return->reportkey = optional_param('reportkey', '', PARAM_TEXT);
        $return->title = optional_param('title', '', PARAM_TEXT);
        $return->enable = optional_param('enable', 1, PARAM_INT);
        $return->enablesql = optional_param('enablesql', '', PARAM_TEXT);
        $return->reportsql = optional_param('reportsql', '', PARAM_TEXT);
        $return->prerequisit = optional_param('prerequisit', '', PARAM_TEXT);
        $return->columns = optional_param('columns', '', PARAM_TEXT);
        $return->foreach = optional_param('foreach', '', PARAM_TEXT);

        return $return;
    }
}
