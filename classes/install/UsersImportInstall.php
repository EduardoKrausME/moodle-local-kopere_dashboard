<?php
/**
 * User: Eduardo Kraus
 * Date: 15/08/17
 * Time: 09:54
 */

namespace local_kopere_dashboard\install;


use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class UsersImportInstall
 *
 * @package local_kopere_dashboard\install
 */
class UsersImportInstall {
    /**
     *
     */
    public static function installOrUpdate() {

        $event = new kopere_dashboard_events();
        $event->module = 'local_kopere_dashboard';
        $event->event = '\\local_kopere_dashboard\\event\\import_course_enrol';
        $event->userfrom = 'admin';
        $event->userto = 'student';
        $event->subject = get_string('userimport_event_import_course_enrol_subject', 'local_kopere_dashboard');
        $event->status = 0;
        $event->message = get_string('userimport_event_import_course_enrol_message', 'local_kopere_dashboard');
        self::insert($event);


        $event = new \local_kopere_dashboard\vo\kopere_dashboard_events();
        $event->module = 'local_kopere_dashboard';
        $event->event = '\\local_kopere_dashboard\\event\\import_user_created';
        $event->userfrom = 'admin';
        $event->userto = 'student';
        $event->subject = get_string('userimport_event_import_user_created_subject', 'local_kopere_dashboard');
        $event->status = 0;
        $event->message = get_string('userimport_event_import_user_created_message', 'local_kopere_dashboard');
        self::insert($event);


        $event = new \local_kopere_dashboard\vo\kopere_dashboard_events();
        $event->module = 'local_kopere_dashboard';
        $event->event = '\\local_kopere_dashboard\\event\\import_user_created_and_enrol';
        $event->userfrom = 'admin';
        $event->userto = 'student';
        $event->subject = get_string('userimport_event_import_user_created_and_enrol_subject', 'local_kopere_dashboard');
        $event->status = 0;
        $event->message = get_string('userimport_event_import_user_created_and_enrol_message', 'local_kopere_dashboard');
        self::insert($event);
    }

    /**
     * @param $event
     */
    public static function insert( $event) {
        global $DB;

        $evento = $DB->get_record('kopere_dashboard_events',
            array(
                'module' => $event->module,
                'event' => $event->event
            ));
        if (!$evento)
            $DB->insert_record('kopere_dashboard_events', $event);
    }
}