<?php
/**
 * User: Eduardo Kraus
 * Date: 17/05/17
 * Time: 21:02
 */

namespace local_kopere_dashboard\events;


class EventObservers
{
    public static function process_event ( \core\event\base $event )
    {
        if ( $event->get_data ()[ 'action' ] == 'viewed' )
            return;

        /*   [eventname]        => \core\event\user_password_updated
         *   [component]        => core
         *   [action]           => updated
         *   [target]           => user_password
         *   [objecttable]      =>
         *   [objectid]         =>
         *   [crud]             => u
         *   [edulevel]         => 0
         *   [contextid]        => 55
         *   [contextlevel]     => 30
         *   [contextinstanceid] => 5
         *   [userid]           => 2
         *   [courseid]         => 0
         *   [relateduserid]    => 5
         *   [anonymous]        => 0
         *   [timecreated]      => 1496259366
         */

        // mail ( 'kraus@eduardokraus.com', 'new Event', print_r ( $event->get_data (), 1 ) );
    }
}