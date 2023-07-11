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
 * Date: 15/08/17
 * Time: 09:54
 */

namespace local_kopere_dashboard\install;

use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class users_import_install
 *
 * @package local_kopere_dashboard\install
 */
class users_import_install {
    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function install_or_update() {

        $event = kopere_dashboard_events::create(
            'local_kopere_dashboard',
            '\\local_kopere_dashboard\\event\\import_course_enrol',
            'admin',
            'student',
            get_string('userimport_event_import_course_enrol_subject', 'local_kopere_dashboard'),
            get_string('userimport_event_import_course_enrol_message', 'local_kopere_dashboard')
        );
        $event->status = 0;
        self::insert($event);

        $event = kopere_dashboard_events::create(
            'local_kopere_dashboard',
            '\\local_kopere_dashboard\\event\\import_user_created',
            'admin',
            'student',
            get_string('userimport_event_import_user_created_subject', 'local_kopere_dashboard'),
            get_string('userimport_event_import_user_created_message', 'local_kopere_dashboard')
        );
        $event->status = 0;
        self::insert($event);

        $event = kopere_dashboard_events::create(
            'local_kopere_dashboard',
            '\\local_kopere_dashboard\\event\\import_user_created_and_enrol',
            'admin',
            'student',
            get_string('userimport_event_import_user_created_and_enrol_subject', 'local_kopere_dashboard'),
            get_string('userimport_event_import_user_created_and_enrol_message', 'local_kopere_dashboard')
        );
        $event->status = 0;
        self::insert($event);
    }

    /**
     * @param $event
     * @throws \dml_exception
     */
    public static function insert($event) {
        global $DB;

        $evento = $DB->record_exists('kopere_dashboard_events', ['module' => $event->module, 'event' => $event->event]);
        if (!$evento) {
            $DB->insert_record('kopere_dashboard_events', $event);
        }
    }
}
