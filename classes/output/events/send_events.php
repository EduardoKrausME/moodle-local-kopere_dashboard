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
 * Class send_events
 *
 * @package local_kopere_dashboard\output\events
 */
class send_events {
    /** @var kopere_dashboard_events */
    private $kopere_dashboard_events;

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
    public function get_kopere_dashboard_events() {
        return $this->kopere_dashboard_events;
    }

    /**
     * @param kopere_dashboard_events $kopereevents
     */
    public function set_kopere_dashboard_events($kopereevents) {
        $this->kopere_dashboard_events = $kopereevents;
    }

    /**
     * @return \core\event\base
     */
    public function get_event() {
        return $this->event;
    }

    /**
     * @param \core\event\base $event
     */
    public function set_event($event) {
        $this->event = $event;
    }


    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function send() {
        global $COURSE, $CFG, $DB;

        $this->load_template();

        $this->subject = $this->replace_date($this->subject);
        $this->message = $this->replace_date($this->message);

        $COURSE->wwwroot = $CFG->wwwroot;
        $COURSE->link = "<a href=\"{$CFG->wwwroot}\" target=\"_blank\">{$CFG->wwwroot}</a>";
        $COURSE->url = $CFG->wwwroot;

        // Moodle: {[moodle.???]}.
        $this->subject = $this->replace_tag($this->subject, $COURSE, 'moodle');
        $this->message = $this->replace_tag($this->message, $COURSE, 'moodle');

        // Events: {[event.???]}.
        $this->subject = $this->replace_tag($this->subject, $this->event, 'event');
        $this->message = $this->replace_tag($this->message, $this->event, 'event');

        $courseid = 0;
        if ($this->event->objecttable == 'course') {
            $courseid = $this->event->objectid;
        } else if (isset($this->event->other) && isset($this->event->other['courseid'])) {
            $courseid = $this->event->other['courseid'];
        }

        if ($this->event->objecttable == 'user') {
            $usertarget = $DB->get_record('user', array('id' => $this->event->objectid));

            $this->subject = $this->replace_tag_user($this->subject, $usertarget, 'usertarget');
            $this->message = $this->replace_tag_user($this->message, $usertarget, 'usertarget');
        }

        if ($courseid) {
            $course = $DB->get_record('course', array('id' => $courseid));

            $course->link
                = "<a href=\"{$CFG->wwwroot}/course/view.php?id={$courseid}\"
                                    target=\"_blank\">{$CFG->wwwroot}/course/view.php?id={$courseid}</a>";
            $course->url = "{$CFG->wwwroot}/course/view.php?id={$courseid}";

            $this->subject = $this->replace_tag($this->subject, $course, 'course');
            $this->message = $this->replace_tag($this->message, $course, 'course');
        }

        // De: {[from.???]}.
        $userfrom = null;
        switch ($this->kopere_dashboard_events->userfrom) {
            case 'admin':
                $userfrom = get_admin();
                break;
        }
        if ($userfrom == null) {
            return;
        }

        $userfrom->fullname = fullname($userfrom);
        $this->subject = $this->replace_tag_user($this->subject, $userfrom, 'from');
        $this->message = $this->replace_tag_user($this->message, $userfrom, 'from');

        // Admins: {[admin.???]}.
        $admin = get_admin();
        $this->subject = $this->replace_tag_user($this->subject, $admin, 'admin');
        $this->message = $this->replace_tag_user($this->message, $admin, 'admin');

        // Para: {[to.???]}.
        $usersto = array();
        switch ($this->kopere_dashboard_events->userto) {
            case 'admin':
                $usersto = array(get_admin());
                break;
            case 'admins':
                $usersto = get_admins();
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
                    $usersto = $DB->get_records_sql($sql,
                        array('contextlevel' => CONTEXT_COURSE,
                            'courseid' => $courseid
                        ));
                }
                break;
            case 'student':
                $usersto = $DB->get_records('user', array('id' => $this->event->relateduserid));
                break;
        }

        foreach ($usersto as $userto) {
            $userto->fullname = fullname($userto);

            if (isset($this->event->other['password'])) {
                $userto->password = $this->event->other['password'];
            } else {
                $userto->password = '';
            }

            $sendsubject = $this->replace_tag_user($this->subject, $userto, 'to');
            $htmlmessage = $this->replace_tag_user($this->message, $userto, 'to');

            $magager = "<a href=\"{$CFG->wwwroot}/message/edit.php?id={$userto->id}\">Gerenciar mensagens</a>";
            $htmlmessage = str_replace('{[manager]}', $magager, $htmlmessage);

            $eventdata = new message();
            $eventdata->courseid = SITEID;
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_kopere_dashboard';
            $eventdata->name = 'kopere_dashboard_messages';
            $eventdata->userfrom = $userfrom;
            $eventdata->userto = $userto;
            $eventdata->subject = $sendsubject;
            $eventdata->fullmessage = html_to_text($htmlmessage);
            $eventdata->fullmessageformat = FORMAT_HTML;
            $eventdata->fullmessagehtml = $htmlmessage;
            $eventdata->smallmessage = '';

            message_send($eventdata);
        }
    }


    /**
     * @throws \dml_exception
     */
    private function load_template() {
        global $CFG;
        $template = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/" .
            get_config('local_kopere_dashboard', 'notificacao-template');
        $templatecontent = file_get_contents($template);

        $this->subject = $this->kopere_dashboard_events->subject;
        $this->message = str_replace('{[message]}', $this->kopere_dashboard_events->message, $templatecontent);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replace_date($text) {
        if (strpos($text, '{[date') === false) {
            return $text;
        }

        preg_match_all("/\{\[date\.(\w+)\]\}/", $text, $textkeys);

        $itens = array(
            'year' => 'Y',
            'month' => 'm',
            'day' => 'd',
            'hour' => 'H',
            'minute' => 'i',
            'second' => 's'
        );

        foreach ($textkeys[1] as $key => $textkey) {

            if (isset($itens[$textkey])) {
                $data = date($itens[$textkey]);
                $strsearch = $textkeys[0][$key];
                $text = str_replace($strsearch, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param $text
     * @param $course
     * @param $keyname
     * @return mixed
     */
    private function replace_tag($text, $course, $keyname) {
        if (strpos($text, "{[{$keyname}") === false) {
            return $text;
        }

        preg_match_all("/\{\[{$keyname}\.(\w+)\]\}/", $text, $textkeys);

        foreach ($textkeys[1] as $key => $textkey) {

            if (isset($course->$textkey)) {
                $data = $course->$textkey;
                $strsearch = $textkeys[0][$key];
                $text = str_replace($strsearch, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param $text
     * @param $user
     * @param $keyname
     * @return mixed
     */
    private function replace_tag_user($text, $user, $keyname) {
        if (strpos($text, "{[{$keyname}") === false) {
            return $text;
        }

        preg_match_all("/\{\[{$keyname}\.(\w+)\]\}/", $text, $textkeys);

        foreach ($textkeys[1] as $key => $textkey) {

            if (isset($user->$textkey)) {
                $data = $user->$textkey;
                $strsearch = $textkeys[0][$key];
                $text = str_replace($strsearch, $data, $text);
            }
        }

        return $text;
    }
}
