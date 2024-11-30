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
 * event_observers file
 *
 * introduced 17/05/17 21:02
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\events;

use local_kopere_dashboard\output\events\send_events;
use local_kopere_dashboard\vo\local_kopere_dashboard_event;

/**
 * Class event_observers
 *
 * @package local_kopere_dashboard\events
 */
class event_observers {

    /**
     * Function process_event
     *
     * @param \core\event\base $event
     *
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function process_event(\core\event\base $event) {
        global $DB;

        $eventname = str_replace('\\\\', '\\', $event->eventname);

        switch ($eventname) {
            case '\core\event\course_deleted':
            case '\core\event\course_updated':
            case '\core\event\course_created':
                $cache = \cache::make("local_kopere_dashboard", "courses_all_courses");
                $cache->delete("load_all_courses");
        }

        if ($event->get_data()["action"] == "viewed") {
            return;
        }

        $where = ["event" => $eventname, "status" => 1];
        $kopereeventss = $DB->get_records("local_kopere_dashboard_event", $where);

        /** @var local_kopere_dashboard_event $kopereevents */
        foreach ($kopereeventss as $kopereevents) {
            $sendevents = new send_events();
            $sendevents->set_event($event);
            $sendevents->set_local_kopere_dashboard_event($kopereevents);

            $sendevents->send();
        }
    }
}
