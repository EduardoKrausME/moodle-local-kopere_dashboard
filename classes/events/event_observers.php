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
 * @created    17/05/17 21:02
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\events;

use local_kopere_dashboard\output\events\send_events;
use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class event_observers
 *
 * @package local_kopere_dashboard\events
 */
class event_observers {
    /**
     * @param \core\event\base $event
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function process_event(\core\event\base $event) {
        global $DB;

        if ($event->get_data()['action'] == 'viewed') {
            return;
        }

        $eventname = str_replace('\\\\', '\\', $event->eventname);

        $kopereeventss = $DB->get_records('kopere_dashboard_events',
            [
                'event' => $eventname,
                'status' => 1
            ]);

        /** @var kopere_dashboard_events $kopereevents */
        foreach ($kopereeventss as $kopereevents) {
            $sendevents = new send_events();
            $sendevents->set_event($event);
            $sendevents->set_kopere_dashboard_events($kopereevents);

            $sendevents->send();
        }
    }
}
