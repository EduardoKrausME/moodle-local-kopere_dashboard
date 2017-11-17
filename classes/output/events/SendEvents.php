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
 * @created    14/06/17 05:21
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\output\events;

defined('MOODLE_INTERNAL') || die();

use core\message\message;
use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class SendEvents
 *
 * @package local_kopere_dashboard\output\events
 */
class SendEvents {
    /** @var kopere_dashboard_events */
    private $kopere_events;

    /** @var \core\event\base */
    private $event;

    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $message;

    /**
     * @return kopere_dashboard_events
     */
    public function getKopereDashboardEvents() {
        return $this->kopere_dashboard_events;
    }

    /**
     * @param kopere_dashboard_events $kopere_events
     */
    public function setKopereDashboardEvents($kopere_events) {
        $this->kopere_dashboard_events = $kopere_events;
    }

    /**
     * @return \core\event\base
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * @param \core\event\base $event
     */
    public function setEvent($event) {
        $this->event = $event;
    }

    /**
     *
     */
    public function send() {
        global $COURSE, $CFG, $DB;

        $this->loadTemplate();

        $this->subject = $this->replaceDate($this->subject);
        $this->message = $this->replaceDate($this->message);

        $COURSE->wwwroot = $CFG->wwwroot;
        $COURSE->link = "<a href=\"{$CFG->wwwroot}\" target=\"_blank\">{$CFG->wwwroot}</a>";

        // Moodle - {[moodle.???]}
        $this->subject = $this->replaceTag($this->subject, $COURSE, 'moodle');
        $this->message = $this->replaceTag($this->message, $COURSE, 'moodle');

        // Events - {[event.???]}
        $this->subject = $this->replaceTag($this->subject, $this->event, 'event');
        $this->message = $this->replaceTag($this->message, $this->event, 'event');

        $courseid = 0;
        if ($this->event->objecttable == 'course') {
            $courseid = $this->event->objectid;
            $course = $DB->get_record('course', array('id' => $courseid));
            if ($course) {
                $course->link = "<a href=\"{$CFG->wwwroot}/course/view.php?id={$courseid}\" target=\"_blank\">{$CFG->wwwroot}/course/view.php?id={$courseid}</a>";
                $course->url = "{$CFG->wwwroot}/course/view.php?id={$courseid}";

                $this->subject = $this->replaceTag($this->subject, $course, 'course');
                $this->message = $this->replaceTag($this->message, $course, 'course');
            }
        }
        if ($this->event->objecttable == 'user') {
            $usertarget = $DB->get_record('user', array('id' => $this->event->objectid));

            $this->subject = $this->replaceTagUser($this->subject, $usertarget, 'usertarget');
            $this->message = $this->replaceTagUser($this->message, $usertarget, 'usertarget');
        }

        // de - {[from.???]}
        $userFrom = null;
        switch ($this->kopere_dashboard_events->userfrom) {
            case 'admin':
                $userFrom = get_admin();
                break;
        }
        if ($userFrom == null) {
            $this->mailError('$userFrom NOT FOUND!');

            return;
        }

        $userFrom->fullname = fullname($userFrom);
        $this->subject = $this->replaceTagUser($this->subject, $userFrom, 'from');
        $this->message = $this->replaceTagUser($this->message, $userFrom, 'from');

        // admins - {[admin.???]}
        $admin = get_admin();
        $this->subject = $this->replaceTagUser($this->subject, $admin, 'admin');
        $this->message = $this->replaceTagUser($this->message, $admin, 'admin');

        // Para - {[to.???]}
        $usersTo = array();
        switch ($this->kopere_dashboard_events->userto) {
            case 'admin':
                $usersTo = array(get_admin());
                break;
            case 'admins':
                $usersTo = get_admins();
                break;
            case 'teachers':
                if ($courseid) {
                    $sql
                        = "SELECT u.*
                                  FROM {course} c
                                  JOIN {context} cx ON c.id = cx.instanceid
                                  JOIN {role_assignments} ra ON cx.id = ra.contextid
                                                            AND ra.roleid = '3'
                                  JOIN {user} u ON ra.userid = u.id
                                 WHERE cx.contextlevel = :contextlevel
                                   AND c.id            = :courseid ";
                    $usersTo = $DB->get_records_sql($sql,
                        array('contextlevel' => CONTEXT_COURSE,
                            'courseid' => $courseid
                        ));
                }
                break;
            case 'student':
                $usersTo = $DB->get_records('user', array('id' => $this->event->relateduserid));
                break;
        }

        foreach ($usersTo as $userTo) {
            $userTo->fullname = fullname($userTo);

            if (isset($this->event->other['password'])) {
                $userTo->password = $this->event->other['password'];
            } else {
                $userTo->password = '';
            }

            $sendSubject = $this->replaceTagUser($this->subject, $userTo, 'to');
            $htmlMessage = $this->replaceTagUser($this->message, $userTo, 'to');

            $magager     = "<a href=\"{$CFG->wwwroot}/message/edit.php?id={$userTo->id}\">Gerenciar mensagens</a>";
            $htmlMessage = str_replace ( '{[manager]}', $magager, $htmlMessage );

            $eventdata = new message();
            $eventdata->courseid = SITEID;
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_kopere_dashboard';
            $eventdata->name = 'kopere_dashboard_messages';
            $eventdata->userfrom = $userFrom;
            $eventdata->userto = $userTo;
            $eventdata->subject = $sendSubject;
            $eventdata->fullmessage = html_to_text($htmlMessage);
            $eventdata->fullmessageformat = FORMAT_HTML;
            $eventdata->fullmessagehtml = $htmlMessage;
            $eventdata->smallmessage = '';

            message_send($eventdata);
        }
    }

    /**
     *
     */
    private function loadTemplate() {
        global $CFG;
        $template = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/" . get_config('local_kopere_dashboard', 'notificacao-template');
        $templateContent = file_get_contents($template);

        $this->subject = $this->kopere_dashboard_events->subject;
        $this->message = str_replace('{[message]}', $this->kopere_dashboard_events->message, $templateContent);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replaceDate($text) {
        if (strpos($text, '{[date') === false) {
            return $text;
        }

        preg_match_all("/\{\[date\.(\w+)\]\}/", $text, $textKeys);

        $itens = array(
            'year' => 'Y',
            'month' => 'm',
            'day' => 'd',
            'hour' => 'H',
            'minute' => 'i',
            'second' => 's'
        );

        foreach ($textKeys[1] as $key => $textKey) {

            if (isset($itens[$textKey])) {
                $data = date($itens[$textKey]);
                $strSearch = $textKeys[0][$key];
                $text = str_replace($strSearch, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replaceTag($text, $course, $keyName) {
        if (strpos($text, "{[{$keyName}") === false) {
            return $text;
        }

        preg_match_all("/\{\[{$keyName}\.(\w+)\]\}/", $text, $textKeys);

        foreach ($textKeys[1] as $key => $textKey) {

            if (isset($course->$textKey)) {
                $data = $course->$textKey;
                $strSearch = $textKeys[0][$key];
                $text = str_replace($strSearch, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replaceTagUser($text, $user, $keyName) {
        if (strpos($text, "{[{$keyName}") === false) {
            return $text;
        }

        preg_match_all("/\{\[{$keyName}\.(\w+)\]\}/", $text, $textKeys);

        foreach ($textKeys[1] as $key => $textKey) {

            if (isset($user->$textKey)) {
                $data = $user->$textKey;
                $strSearch = $textKeys[0][$key];
                $text = str_replace($strSearch, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param $message
     */
    private static function mailError( $message) {
    }
}
