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
    public function get_kopere_dashboard_events() {
        return $this->kopere_dashboard_events;
    }

    /**
     * @param kopere_dashboard_events $kopere_events
     */
    public function set_kopere_dashboard_events($kopere_events) {
        $this->kopere_dashboard_events = $kopere_events;
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
     *
     */
    public function send() {
        global $COURSE, $CFG, $DB;

        $this->load_template();

        $this->subject = $this->replace_date($this->subject);
        $this->message = $this->replace_date($this->message);

        $COURSE->wwwroot = $CFG->wwwroot;
        $COURSE->link = "<a href=\"{$CFG->wwwroot}\" target=\"_blank\">{$CFG->wwwroot}</a>";

        // Moodle: {[moodle.???]}.
        $this->subject = $this->replace_tag($this->subject, $COURSE, 'moodle');
        $this->message = $this->replace_tag($this->message, $COURSE, 'moodle');

        // Events: {[event.???]}.
        $this->subject = $this->replace_tag($this->subject, $this->event, 'event');
        $this->message = $this->replace_tag($this->message, $this->event, 'event');

        $courseid = 0;
        if ($this->event->objecttable == 'course') {
            $courseid = $this->event->objectid;
            $course = $DB->get_record('course', array('id' => $courseid));
            if ($course) {
                $course->link = "<a href=\"{$CFG->wwwroot}/course/view.php?id={$courseid}\"
                                    target=\"_blank\">{$CFG->wwwroot}/course/view.php?id={$courseid}</a>";
                $course->url = "{$CFG->wwwroot}/course/view.php?id={$courseid}";

                $this->subject = $this->replace_tag($this->subject, $course, 'course');
                $this->message = $this->replace_tag($this->message, $course, 'course');
            }
        }
        if ($this->event->objecttable == 'user') {
            $usertarget = $DB->get_record('user', array('id' => $this->event->objectid));

            $this->subject = $this->replace_tag_user($this->subject, $usertarget, 'usertarget');
            $this->message = $this->replace_tag_user($this->message, $usertarget, 'usertarget');
        }

        // De: {[from.???]}.
        $user_from = null;
        switch ($this->kopere_dashboard_events->userfrom) {
            case 'admin':
                $user_from = get_admin();
                break;
        }
        if ($user_from == null) {
            $this->mail_error('$user_from NOT FOUND!');

            return;
        }

        $user_from->fullname = fullname($user_from);
        $this->subject = $this->replace_tag_user($this->subject, $user_from, 'from');
        $this->message = $this->replace_tag_user($this->message, $user_from, 'from');

        // Admins: {[admin.???]}.
        $admin = get_admin();
        $this->subject = $this->replace_tag_user($this->subject, $admin, 'admin');
        $this->message = $this->replace_tag_user($this->message, $admin, 'admin');

        // Para: {[to.???]}.
        $users_to = array();
        switch ($this->kopere_dashboard_events->userto) {
            case 'admin':
                $users_to = array(get_admin());
                break;
            case 'admins':
                $users_to = get_admins();
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
                    $users_to = $DB->get_records_sql($sql,
                        array('contextlevel' => CONTEXT_COURSE,
                            'courseid' => $courseid
                        ));
                }
                break;
            case 'student':
                $users_to = $DB->get_records('user', array('id' => $this->event->relateduserid));
                break;
        }

        foreach ($users_to as $user_to) {
            $user_to->fullname = fullname($user_to);

            if (isset($this->event->other['password'])) {
                $user_to->password = $this->event->other['password'];
            } else {
                $user_to->password = '';
            }

            $send_subject = $this->replace_tag_user($this->subject, $user_to, 'to');
            $html_message = $this->replace_tag_user($this->message, $user_to, 'to');

            $magager     = "<a href=\"{$CFG->wwwroot}/message/edit.php?id={$user_to->id}\">Gerenciar mensagens</a>";
            $html_message = str_replace ( '{[manager]}', $magager, $html_message );

            $eventdata = new message();
            $eventdata->courseid = SITEID;
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'local_kopere_dashboard';
            $eventdata->name = 'kopere_dashboard_messages';
            $eventdata->userfrom = $user_from;
            $eventdata->userto = $user_to;
            $eventdata->subject = $send_subject;
            $eventdata->fullmessage = html_to_text($html_message);
            $eventdata->fullmessageformat = FORMAT_HTML;
            $eventdata->fullmessagehtml = $html_message;
            $eventdata->smallmessage = '';

            message_send($eventdata);
        }
    }

    /**
     *
     */
    private function load_template() {
        global $CFG;
        $template = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/" .
            get_config('local_kopere_dashboard', 'notificacao-template');
        $template_content = file_get_contents($template);

        $this->subject = $this->kopere_dashboard_events->subject;
        $this->message = str_replace('{[message]}', $this->kopere_dashboard_events->message, $template_content);
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

        preg_match_all("/\{\[date\.(\w+)\]\}/", $text, $text_keys);

        $itens = array(
            'year' => 'Y',
            'month' => 'm',
            'day' => 'd',
            'hour' => 'H',
            'minute' => 'i',
            'second' => 's'
        );

        foreach ($text_keys[1] as $key => $text_key) {

            if (isset($itens[$text_key])) {
                $data = date($itens[$text_key]);
                $str_search = $text_keys[0][$key];
                $text = str_replace($str_search, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replace_tag($text, $course, $key_name) {
        if (strpos($text, "{[{$key_name}") === false) {
            return $text;
        }

        preg_match_all("/\{\[{$key_name}\.(\w+)\]\}/", $text, $text_keys);

        foreach ($text_keys[1] as $key => $text_key) {

            if (isset($course->$text_key)) {
                $data = $course->$text_key;
                $str_search = $text_keys[0][$key];
                $text = str_replace($str_search, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replace_tag_user($text, $user, $key_name) {
        if (strpos($text, "{[{$key_name}") === false) {
            return $text;
        }

        preg_match_all("/\{\[{$key_name}\.(\w+)\]\}/", $text, $text_keys);

        foreach ($text_keys[1] as $key => $text_key) {

            if (isset($user->$text_key)) {
                $data = $user->$text_key;
                $str_search = $text_keys[0][$key];
                $text = str_replace($str_search, $data, $text);
            }
        }

        return $text;
    }

    /**
     * @param $message
     */
    private static function mail_error( $message) {
    }
}
