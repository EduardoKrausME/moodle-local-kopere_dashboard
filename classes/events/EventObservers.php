<?php
/**
 * User: Eduardo Kraus
 * Date: 17/05/17
 * Time: 21:02
 */

namespace local_kopere_dashboard\events;

use local_kopere_dashboard\output\events\SendEvents;
use local_kopere_dashboard\vo\kopere_dashboard_events;

class EventObservers
{
    public static function process_event ( \core\event\base $event )
    {
        global $DB;

        if ( $event->get_data ()[ 'action' ] == 'viewed' )
            return;


        $eventname = str_replace ( '\\\\', '\\', $event->eventname );

        $kopere_dashboard_eventss = $DB->get_records ( 'kopere_dashboard_events',
            array(
                'event'  => $eventname,
                'status' => 1
            ) );

        /** @var kopere_dashboard_events $kopere_dashboard_events */
        foreach ( $kopere_dashboard_eventss as $kopere_dashboard_events ) {
            $sendEvents = new SendEvents();
            $sendEvents->setEvent ( $event );
            $sendEvents->setKopereDashboardEvents ( $kopere_dashboard_events );

            $sendEvents->send ();
        }


        /*   [eventname]         => \core\event\user_password_updated
         *   [component]         => core
         *   [action]            => updated
         *   [target]            => user_password
         *   [objecttable]       =>
         *   [objectid]          =>
         *   [crud]              => u
         *   [edulevel]          => 0
         *   [contextid]         => 55
         *   [contextlevel]      => 30
         *   [contextinstanceid] => 5
         *   [userid]            => 2
         *   [courseid]          => 0
         *   [relateduserid]     => 5
         *   [anonymous]         => 0
         *   [timecreated]       => 1496259366
         */
    }
}