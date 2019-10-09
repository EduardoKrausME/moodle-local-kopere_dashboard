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
 * @created    13/05/17 13:27
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

defined('MOODLE_INTERNAL') || die();

use core\event\base;
use core\message\message;
use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\html\tinymce;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class notifications
 * @package local_kopere_dashboard
 */
class notifications extends notificationsutil {

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $DB;

        dashboard_util::add_breadcrumb(get_string_kopere('notification_title'));
        dashboard_util::start_page('?classname=notifications&method=settings');

        notificationsutil::mensagem_no_smtp();

        echo '<div class="element-box">';

        echo get_string_kopere('notification_subtitle');

        button::add(get_string_kopere('notification_new'), '?classname=notifications&method=add', '', true, false, true);
        button::info(get_string_kopere('notification_testsmtp'), '?classname=notifications&method=test_smtp');

        $events = $DB->get_records('kopere_dashboard_events');
        $eventslist = array();
        foreach ($events as $event) {
            /** @var base $eventclass */
            $eventclass = $event->event;

            $event->module_name = $this->module_name($event->module, false);
            if (method_exists($eventclass, "get_name")) {
                $event->event_name = $eventclass::get_name();
            } else {
                $event->event_name = "";
            }
            $event->actions
                = "<div class=\"text-center\">
                    " . button::icon('edit', "?classname=notifications&method=add_segunda_etapa&id={$event->id}", false) . "&nbsp;&nbsp;&nbsp;
                    " . button::icon_popup_table('delete', "?classname=notifications&method=delete&id={$event->id}") . "
                   </div>";

            $eventslist[] = $event;
        }

        if ($events) {
            $table = new data_table();
            $table->add_header('#', 'id', table_header_item::TYPE_INT);
            $table->add_header(get_string_kopere('notification_table_module'), 'module_name');
            $table->add_header(get_string_kopere('notification_table_action'), 'event_name');
            $table->add_header(get_string_kopere('notification_table_subject'), 'subject');
            $table->add_header(get_string_kopere('notification_table_active'),
                'status', table_header_item::RENDERER_VISIBLE);
            $table->add_header(get_string_kopere('notification_from'), 'userfrom');
            $table->add_header(get_string_kopere('notification_to'), 'userto');
            $table->add_header('', 'actions', table_header_item::TYPE_ACTION);

            $table->print_header();
            $table->set_row($events);
            $table->close();
        } else {
            mensagem::print_warning(get_string_kopere('notification_table_empty'));
        }

        echo '</div>';
        dashboard_util::end_page();
    }


    /**
     *
     */
    public function add() {
        dashboard_util::add_breadcrumb(get_string_kopere('notification_title'), '?classname=notifications&method=dashboard');
        dashboard_util::add_breadcrumb(get_string_kopere('notification_new'));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $events = $this->list_events();
        $moduleslist = array();
        foreach ($events->components as $components) {
            $modulename = $this->module_name($components, true);

            if ($modulename != null) {
                $moduleslist[] = array('key' => $components, 'value' => $modulename);
            }
        }

        $form = new form('?classname=notifications&method=add_segunda_etapa');
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('notification_add_module'))
                ->set_name('module')
                ->set_values($moduleslist)
                ->set_add_selecione(true)
                ->set_description(get_string_kopere('notification_add_moduledesc'))
        );
        echo '<div id="restante-form">' . get_string_kopere('notification_add_selectmodule') . '</div>';

        $form->close();

        ?>
        <script>
            $('#module').change(function() {
                var data = {
                    module : $(this).val()
                };
                $('#restante-form').load('load-ajax.php?classname=notificationsutil&method=add_form_extra', data);
            });
        </script>
        <?php

        echo '</div>';
        dashboard_util::end_page();
    }


    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function add_segunda_etapa() {
        global $CFG, $DB;

        /** @var base $eventclass */
        $eventclass = optional_param('event', '', PARAM_RAW);
        $module = optional_param('module', '', PARAM_RAW);
        $id = optional_param('id', 0, PARAM_INT);

        if ($id) {
            /** @var kopere_dashboard_events $evento */
            $evento = $DB->get_record('kopere_dashboard_events', array('id' => $id));
            header::notfound_null($evento, get_string_kopere('notification_notound'));

            $eventclass = $evento->event;
            $module = $evento->module;

            dashboard_util::add_breadcrumb(get_string_kopere('notification_title'), '?classname=notifications&method=dashboard');
            dashboard_util::add_breadcrumb(get_string_kopere('notification_editing'));
        } else {
            $evento = kopere_dashboard_events::create_by_default();

            dashboard_util::add_breadcrumb(get_string_kopere('notification_title'), '?classname=notifications&method=dashboard');
            dashboard_util::add_breadcrumb(get_string_kopere('notification_new'));
        }
        dashboard_util::start_page();
        echo '<div class="element-box">';

        $form = new form('?classname=notifications&method=add_save');
        $form->create_hidden_input('id', $id);
        $form->create_hidden_input('event', $eventclass);
        $form->create_hidden_input('module', $module);

        $form->print_row(
            get_string_kopere('notification_add_action'),
            $eventclass::get_name());

        if ($id) {
            $status = array(
                array('key' => 1, 'value' => get_string_kopere('notification_status_active')),
                array('key' => 0, 'value' => get_string_kopere('notification_status_inactive'))
            );
            $form->add_input(
                input_select::new_instance()
                    ->set_title(get_string_kopere('notification_status'))
                    ->set_name('status')
                    ->set_values($status)
                    ->set_value($evento->status)
                    ->set_description(get_string_kopere('notification_statusdesc')));
        }

        $valuefrom = array(
            array('key' => 'admin', 'value' => get_string_kopere('notification_from_admin'))
        );
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('notification_from'))
                ->set_name('userfrom')
                ->set_values($valuefrom)
                ->set_value($evento->userfrom)
                ->set_description(get_string_kopere('notification_fromdesc')));

        $valueto = array(
            array('key' => 'admin', 'value' => get_string_kopere('notification_todesc_admin')),
            array('key' => 'admins', 'value' => get_string_kopere('notification_todesc_admins')),
            array('key' => 'teachers', 'value' => get_string_kopere('notification_todesc_teachers')),
            array('key' => 'student', 'value' => get_string_kopere('notification_todesc_student')),
        );
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('notification_to'))
                ->set_name('userto')
                ->set_values($valueto)
                ->set_value($evento->userto)
                ->set_description(get_string_kopere('notification_todesc')));

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('notification_subject'))
                ->set_name('subject')
                ->set_value($evento->subject)
                ->set_description(get_string_kopere('notification_subjectdesc')));

        $template = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/" .
            get_config('local_kopere_dashboard', 'notificacao-template');
        $templatecontent = file_get_contents($template);

        if (!$id) {
            if (strpos($module, 'mod_') === 0) {
                $mailtext = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail-text/mod.html";
                $modulename = get_string('modulename', $module);
            } else {
                $mailtext = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail-text/{$module}.html";
                $modulename = '';
            }

            if (file_exists($mailtext)) {
                $htmltext = file_get_contents($mailtext);
            } else {
                $htmltext = get_string_kopere('notification_message_html');
            }

            $htmltext = str_replace('{[event.name]}', $eventclass::get_name(), $htmltext);
            $htmltext = str_replace('{[module.name]}', $modulename, $htmltext);
        } else {
            $htmltext = $evento->message;
        }

        $form->print_row(null, button::help('TAGS-substituídas-nas-mensagens-de-Notificações', 'Quais as TAGS substituídas nas mensagens?'));

        $htmltextarea = '<textarea name="message" id="message" style="height:500px">' .
            htmlspecialchars($htmltext) . '</textarea>';
        $templatecontent = str_replace('{[message]}', $htmltextarea, $templatecontent);
        $form->print_panel(get_string_kopere('notification_message'), $templatecontent);
        echo tinymce::create_input_editor('#message');

        if ($id) {
            $form->create_submit_input(get_string_kopere('notification_update'));
        } else {
            $form->create_submit_input(get_string_kopere('notification_create'));
        }

        $form->close();

        echo '</div>';
        dashboard_util::end_page();
    }


    /**
     * @throws \coding_exception
     */
    public function add_save() {
        global $DB;

        $kopereevents = kopere_dashboard_events::create_by_default();

        if ($kopereevents->id) {
            try {
                $DB->update_record('kopere_dashboard_events', $kopereevents);

                mensagem::agenda_mensagem_success(get_string_kopere('notification_created'));
                header::location('?classname=notifications&method=dashboard');
            } catch (\dml_exception $e) {
                mensagem::print_danger($e->getMessage());
            }
        } else {
            try {
                $DB->insert_record('kopere_dashboard_events', $kopereevents);

                mensagem::agenda_mensagem_success(get_string_kopere('notification_created'));
                header::location('?classname=notifications&method=dashboard');
            } catch (\dml_exception $e) {
                mensagem::print_danger($e->getMessage());
            }
        }
    }


    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function delete() {
        global $DB;

        $status = optional_param('status', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_events $event */
        $event = $DB->get_record('kopere_dashboard_events', array('id' => $id));
        header::notfound_null($event, get_string_kopere('notification_notfound'));

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_events', array('id' => $id));

            mensagem::agenda_mensagem_success(get_string_kopere('notification_delete_success'));
            header::location('?classname=notifications&method=dashboard');
        }

        dashboard_util::add_breadcrumb(get_string_kopere('notification_delete_yes'));
        dashboard_util::start_page();

        echo "<p>" . get_string_kopere('notification_delete_yes') . "</p>";
        button::delete(get_string('yes'), '?classname=notifications&method=delete&status=sim&id=' . $event->id, '', false);
        button::add(get_string('no'), '?classname=notifications&method=dashboard', 'margin-left-10', false);

        dashboard_util::end_page();
    }


    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function settings() {
        global $CFG;
        ob_clean();
        $redirect = urlencode("classname=notifications&method=dashboard");
        dashboard_util::add_breadcrumb(get_string_kopere('notification_setting_config'));
        dashboard_util::start_page();

        $form = new form("?classname=settings&method=save&redirect={$redirect}");

        $values = array();
        $templates = glob("{$CFG->dirroot}/local/kopere_dashboard/assets/mail/*.html");
        foreach ($templates as $template) {
            $values[] = array('key' => pathinfo($template, PATHINFO_BASENAME));
        }

        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('notification_setting_template'))
                ->set_values($values, 'key', 'key')
                ->set_value_by_config('notificacao-template'));

        $form->print_panel(get_string_kopere('notification_setting_preview'), "<div id=\"area-mensagem-preview\"></div>");

        $form->print_row(get_string_kopere('notification_setting_templatelocation'),
            "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/");

        $form->create_submit_input(get_string('savechanges'));

        $form->close();

        ?>
        <script>
            $('#notificacao-template').change(notificacao_template_change);

            function notificacao_template_change() {
                var data = {
                    template : $('#notificacao-template').val()
                };
                $('#area-mensagem-preview').load('load-ajax.php?classname=notifications&method=settings_load_template', data);
            }

            notificacao_template_change();
        </script>
        <style>
            .table-messagem {
                max-width: 600px;
            }
        </style>
        <?php

        dashboard_util::end_page();
    }


    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function test_smtp() {
        global $CFG, $USER;

        dashboard_util::add_breadcrumb(get_string_kopere('notification_title'), '?classname=notifications&method=dashboard');
        dashboard_util::add_breadcrumb(get_string_kopere('notification_testsmtp'));
        dashboard_util::start_page();

        notificationsutil::mensagem_no_smtp();
        $CFG->debugsmtp = true;
        $CFG->debugdisplay = true;
        $CFG->debug = 32767;

        $htmlmessage = get_string_kopere('notification_testsmtp_message') . date('d/m/Y H:m');
        $admin = get_admin();
        if ($admin->id == $USER->id) {
            mensagem::print_danger(get_string_kopere('notification_testsmtp_error'));
        }

        $eventdata = new message();
        $eventdata->courseid = SITEID;
        $eventdata->modulename = 'moodle';
        $eventdata->component = 'local_kopere_dashboard';
        $eventdata->name = 'kopere_dashboard_messages';
        $eventdata->userfrom = get_admin();
        $eventdata->userto = $USER;
        $eventdata->subject = get_string_kopere('notification_testsmtp_subject') . date('d/m/Y H:m');
        $eventdata->fullmessage = html_to_text($htmlmessage);
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml = $htmlmessage;
        $eventdata->smallmessage = '';

        message_send($eventdata);
        dashboard_util::end_page();
    }
}