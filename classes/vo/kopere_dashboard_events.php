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
 * Class kopere_dashboard_events
 * @package local_kopere_dashboard\vo
 */
class kopere_dashboard_events extends \stdClass {
    /** @var int */
    public $id;

    /** @var string */
    public $module;

    /** @var string */
    public $event;

    /** @var int */
    public $status = 1;

    /** @var string */
    public $userfrom;

    /** @var string */
    public $userto;

    /** @var string */
    public $subject;

    /** @var string */
    public $message;

    /**
     * @param $module
     * @param $eventname
     * @param $userfrom
     * @param $userto
     * @param $subject
     * @param null $message
     * @return kopere_dashboard_events
     */
    public static function create($module, $eventname, $userfrom, $userto, $subject, $message = null) {
        $event = new kopere_dashboard_events();
        $event->module = $module;
        $event->event = $eventname;
        $event->userfrom = $userfrom;
        $event->userto = $userto;
        $event->subject = $subject;
        if ($message) {
            $event->message = $message;
        }
        return $event;
    }

    /**
     * @param $item
     * @return kopere_dashboard_events
     * @throws \coding_exception
     */
    public static function create_by_object($item) {
        $return = new kopere_dashboard_events();

        $return->id = $item->id;
        $return->module = optional_param('module', $item->module, PARAM_TEXT);
        $return->event = optional_param('event', $item->event, PARAM_TEXT);
        $return->status = optional_param('status', $item->status, PARAM_INT);
        $return->userfrom = optional_param('userfrom', $item->userfrom, PARAM_TEXT);
        $return->userto = optional_param('userto', $item->userto, PARAM_TEXT);
        $return->subject = optional_param('subject', $item->subject, PARAM_TEXT);
        $return->message = optional_param('message', $item->message, PARAM_RAW);

        return $return;
    }

    /**
     * @return kopere_dashboard_events
     * @throws \coding_exception
     */
    public static function create_by_default() {
        $return = new kopere_dashboard_events();

        $return->id = optional_param('id', 0, PARAM_INT);
        $return->module = optional_param('module', '', PARAM_TEXT);
        $return->event = optional_param('event', '', PARAM_TEXT);
        $return->status = optional_param('status', 1, PARAM_INT);
        $return->userfrom = optional_param('userfrom', '', PARAM_TEXT);
        $return->userto = optional_param('userto', '', PARAM_TEXT);
        $return->subject = optional_param('subject', '', PARAM_TEXT);
        $return->message = optional_param('message', '', PARAM_RAW);

        return $return;
    }
}
