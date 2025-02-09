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
 * Notification file
 *
 * introduced 13/05/17 13:27
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use core\event\base;
use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_htmleditor;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\config;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\release;
use local_kopere_dashboard\vo\local_kopere_dashboard_event;

/**
 * Class notifications
 *
 * @package local_kopere_dashboard
 */
class notifications extends notificationsutil {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $DB;

        dashboard_util::add_breadcrumb(get_string_kopere("notification_title"));
        dashboard_util::start_page();

        notificationsutil::message_no_smtp();

        echo '<div class="element-box">';

        echo get_string_kopere("notification_subtitle");

        button::add(get_string_kopere("notification_new"),
            local_kopere_dashboard_makeurl("notifications", "add"), "", true, false);
        button::info(get_string_kopere("notification_testsmtp"),
            local_kopere_dashboard_makeurl("notifications", "test_smtp"));

        $events = $DB->get_records("local_kopere_dashboard_event");
        $eventslist = [];
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
                = "<div class='text-center'>
                    " .
                button::icon("edit",
                    local_kopere_dashboard_makeurl("notifications", "add_second_stage", ["id" => $event->id])) .
                "&nbsp;&nbsp;&nbsp;
                    " . button::icon_popup_table("delete",
                    local_kopere_dashboard_makeurl("notifications", "delete", ["id" => "{$event->id}"])) . "
                   </div>";

            $eventslist[] = $event;
        }

        if ($events) {
            $table = new data_table();
            $table->add_header("#", "id", table_header_item::TYPE_INT);
            $table->add_header(get_string_kopere("notification_table_module"), "module_name");
            $table->add_header(get_string_kopere("notification_table_action"), "event_name");
            $table->add_header(get_string_kopere("notification_table_subject"), "subject");
            $table->add_header(get_string_kopere("notification_table_active"),
                "status", table_header_item::RENDERER_VISIBLE);
            $table->add_header(get_string_kopere("notification_from"), "userfrom");
            $table->add_header(get_string_kopere("notification_to"), "userto");
            $table->add_header("", "actions", table_header_item::TYPE_ACTION);

            $table->print_header();
            $table->set_row($events);
            $table->close();
        } else {
            message::print_warning(get_string_kopere("notification_table_empty"));
        }

        echo "</div>";
        dashboard_util::end_page();
    }

    /**
     * Function add
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function add() {
        global $PAGE;

        dashboard_util::add_breadcrumb(get_string_kopere("notification_title"),
            local_kopere_dashboard_makeurl("notifications", "dashboard"));
        dashboard_util::add_breadcrumb(get_string_kopere("notification_new"));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $events = $this->list_events();
        $moduleslist = [];
        foreach ($events->components as $components) {
            $modulename = $this->module_name($components, true);

            if ($modulename != null) {
                $moduleslist[] = ["key" => $components, "value" => $modulename];
            }
        }

        $form = new form(local_kopere_dashboard_makeurl("notifications", "add_second_stage"));
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere("notification_add_module"))
                ->set_name("module")
                ->set_values($moduleslist)
                ->set_add_selecione(true)
                ->set_description(get_string_kopere("notification_add_moduledesc"))
        );
        echo '<div id="restante-form">' . get_string_kopere("notification_add_selectmodule") . "</div>";

        $form->close();

        $PAGE->requires->js_call_amd("local_kopere_dashboard/notifications", "notifications_add_form_extra");

        echo "</div>";
        dashboard_util::end_page();
    }

    /**
     * Function add_second_stage
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function add_second_stage() {
        global $CFG, $DB;

        /** @var base $eventclass */
        $eventclass = optional_param("event", "", PARAM_RAW);
        $module = optional_param("module", "", PARAM_RAW);
        $id = optional_param("id", 0, PARAM_INT);

        if ($id) {
            /** @var local_kopere_dashboard_event $saveevent */
            $saveevent = $DB->get_record("local_kopere_dashboard_event", ["id" => $id]);
            header::notfound_null($saveevent, get_string_kopere("notification_notound"));

            $eventclass = $saveevent->event;
            $module = $saveevent->module;

            dashboard_util::add_breadcrumb(
                get_string_kopere("notification_title"), local_kopere_dashboard_makeurl("notifications", "dashboard"));
            dashboard_util::add_breadcrumb(
                get_string_kopere("notification_editing"));
        } else {
            $saveevent = local_kopere_dashboard_event::create_by_default();

            dashboard_util::add_breadcrumb(
                get_string_kopere("notification_title"), local_kopere_dashboard_makeurl("notifications", "dashboard"));
            dashboard_util::add_breadcrumb(
                get_string_kopere("notification_new"));
        }
        dashboard_util::start_page();
        echo '<div class="element-box">';

        $form = new form(local_kopere_dashboard_makeurl("notifications", "add_save"));
        $form->create_hidden_input("id", $id);
        $form->create_hidden_input("event", $eventclass);
        $form->create_hidden_input("module", $module);

        $form->print_row(
            get_string_kopere("notification_add_action"),
            $eventclass::get_name());

        if ($id) {
            $status = [
                ["key" => 1, "value" => get_string_kopere("active")],
                ["key" => 0, "value" => get_string_kopere("inactive")],
            ];
            $form->add_input(
                input_select::new_instance()
                    ->set_title(get_string_kopere("notification_status"))
                    ->set_name("status")
                    ->set_values($status)
                    ->set_value($saveevent->status)
                    ->set_description(get_string_kopere("notification_statusdesc")));
        }

        $valuefrom = [
            ["key" => "admin", "value" => get_string_kopere("notification_from_admin")],
        ];
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere("notification_from"))
                ->set_name("userfrom")
                ->set_values($valuefrom)
                ->set_value($saveevent->userfrom)
                ->set_description(get_string_kopere("notification_fromdesc")));

        $valueto = [
            ["key" => "admin", "value" => get_string_kopere("notification_todesc_admin")],
            ["key" => "admins", "value" => get_string_kopere("notification_todesc_admins")],
            ["key" => "teachers", "value" => get_string_kopere("notification_todesc_teachers")],
            ["key" => "student", "value" => get_string_kopere("notification_todesc_student")],
        ];
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere("notification_to"))
                ->set_name("userto")
                ->set_values($valueto)
                ->set_value($saveevent->userto)
                ->set_description(get_string_kopere("notification_todesc")));

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere("notification_subject"))
                ->set_name("subject")
                ->set_value($saveevent->subject)
                ->set_description(get_string_kopere("notification_subjectdesc")));

        if (!$id) {
            if (strpos($module, "mod_") === 0) {
                $htmltext = get_string_kopere("notification_message_html_mod");
                $modulename = get_string("modulename", $module);
            } else {
                $modules = ["core_course", "core_course_category", "core_user", "core_user_enrolment"];

                if (in_array($module, $modules)) {
                    $htmltext = get_string_kopere("notification_message_html_{$module}");
                } else {
                    $htmltext = get_string_kopere("notification_message_html");
                }
                $modulename = "";
            }

            $htmltext = str_replace("{[event.name]}", $eventclass::get_name(), $htmltext);
            $htmltext = str_replace("{[module.name]}", $modulename, $htmltext);
        } else {
            $htmltext = $saveevent->message;
        }

        $form->print_row(null,
            button::help("TAGS-substituídas-nas-mensagens-de-Notificações", get_string_kopere("notification_tags")));

        $htmltexteditor = input_htmleditor::new_instance()
            ->set_name("event_message")
            ->set_value($htmltext)
            ->to_string();
        $templatecontent = config::get_key("notification-template");

        $href = "{$CFG->wwwroot}/local/kopere_dashboard/_editor/?page=settings&id=notification-template";
        $edittemplate = "<a href='{$href}' class='btn btn-info mt-2'>" .
            get_string_kopere("notification_message_edit_template") .
            "</a>";
        if (strpos($templatecontent, "{[message]}") === false) {
            message::print_danger(get_string_kopere("notification_message_template_error") . "<br>" . $edittemplate);
        } else {
            $templatecontent = str_replace("{[message]}", $htmltexteditor, $templatecontent);
            $form->print_panel(get_string_kopere("notification_message"), $templatecontent . $edittemplate);
        }

        if ($id) {
            $form->create_submit_input(get_string_kopere("notification_update"));
        } else {
            $form->create_submit_input(get_string_kopere("notification_create"));
        }

        $form->close();

        echo "</div>";
        dashboard_util::end_page();
    }

    /**
     * Function add_save
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function add_save() {
        global $DB;

        $event = local_kopere_dashboard_event::create_by_default();

        if ($event->id) {
            $eventexist = $DB->record_exists_select("local_kopere_dashboard_event",
                "module = :module AND event = :event AND id != :id",
                ["module" => $event->module, "event" => $event->event, "id" => $event->id]);
            if ($eventexist) {
                message::schedule_message_danger(get_string_kopere("notification_duplicate"));
            } else {
                try {
                    $DB->update_record("local_kopere_dashboard_event", $event);

                    message::schedule_message_success(get_string_kopere("notification_created"));
                    header::location(local_kopere_dashboard_makeurl("notifications", "add_second_stage",
                        ["id" => $event->id]));
                } catch (\dml_exception $e) {
                    message::print_danger($e->getMessage());
                }
            }
        } else {
            $eventexist = $DB->get_record("local_kopere_dashboard_event",
                ["module" => $event->module, "event" => $event->event]);
            if ($eventexist) {
                message::schedule_message_danger(get_string_kopere("notification_duplicate"));
                header::location(local_kopere_dashboard_makeurl("notifications", "add_second_stage",
                    ["id" => $eventexist->id]));

            } else {
                try {
                    $eventid = $DB->insert_record("local_kopere_dashboard_event", $event);

                    message::schedule_message_success(get_string_kopere("notification_created"));
                    header::location(local_kopere_dashboard_makeurl("notifications", "add_second_stage",
                        ["id" => $eventid]));
                } catch (\dml_exception $e) {
                    message::print_danger($e->getMessage());
                }
            }
        }
    }

    /**
     * Function delete
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function delete() {
        global $DB;

        $status = optional_param("status", "", PARAM_TEXT);
        $id = optional_param("id", 0, PARAM_INT);
        /** @var local_kopere_dashboard_event $event */
        $event = $DB->get_record("local_kopere_dashboard_event", ["id" => $id]);
        header::notfound_null($event, get_string_kopere("notification_notfound"));

        if ($status == "sim") {
            $DB->delete_records("local_kopere_dashboard_event", ["id" => $id]);

            message::schedule_message_success(get_string_kopere("notification_delete_success"));
            header::location(local_kopere_dashboard_makeurl("notifications", "dashboard"));
        }

        dashboard_util::add_breadcrumb(get_string_kopere("notification_delete_yes"));
        dashboard_util::start_page();

        echo "<p>" . get_string_kopere("notification_delete_yes") . "</p>";
        button::delete(get_string("yes"),
            local_kopere_dashboard_makeurl("notifications", "delete", ["status" => "sim", "id" => $event->id]), "", false);
        button::add(get_string("no"),
            local_kopere_dashboard_makeurl("notifications", "dashboard"), "margin-left-10", false);

        dashboard_util::end_page();
    }

    /**
     * Function test_smtp
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function test_smtp() {
        global $CFG, $USER;

        dashboard_util::add_breadcrumb(get_string_kopere("notification_title"),
            local_kopere_dashboard_makeurl("notifications", "dashboard"));
        dashboard_util::add_breadcrumb(get_string_kopere("notification_testsmtp"));
        dashboard_util::start_page();

        if (!$CFG->debugdisplay || $CFG->debug == 0) {
            message::print_danger("Você precisa ativar o Modo desenvolvedor e Mostrar as mensagens de debug");
        }

        notificationsutil::message_no_smtp();
        $CFG->debugsmtp = true;

        $htmlmessage = get_string_kopere("notification_testsmtp_message") . date("d/m/Y H:i");

        $eventdata = new \core\message\message();
        if (release::version() >= 3.2) {
            $eventdata->courseid = SITEID;
            $eventdata->modulename = "moodle";
        }
        $eventdata->component = "local_kopere_dashboard";
        $eventdata->name = "kopere_dashboard_messages";
        $eventdata->userfrom = get_admin();
        $eventdata->userto = $USER;
        $eventdata->subject = get_string_kopere("notification_testsmtp_subject") . date("d/m/Y H:i");
        $eventdata->fullmessage = html_to_text($htmlmessage);
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml = $htmlmessage;
        $eventdata->smallmessage = "";

        message_send($eventdata);

        dashboard_util::end_page();
    }
}
