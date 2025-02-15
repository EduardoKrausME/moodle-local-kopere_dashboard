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
 * course_observers file
 *
 * @package   local_kopere_dashboard
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\events;

/**
 * Class course_observers
 *
 * @package local_kopere_dashboard\events
 */
class course_observers {
    /**
     * Function process_event
     *
     * @param \core\event\base $event
     */
    public static function process_event(\core\event\base $event) {
        $eventname = str_replace("\\\\", "\\", $event->eventname);
        switch ($eventname) {
            case "\\core\\event\\course_deleted":
            case "\\core\\event\\course_updated":
            case "\\core\\event\\course_created":
            case "\\core\\event\\config_log_created":
                \cache::make("local_kopere_dashboard", "courses_all_courses")->purge();
                break;
        }
    }
}
