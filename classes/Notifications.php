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
use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\html\inputs\InputText;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\html\TinyMce;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\vo\kopere_dashboard_events;

class Notifications extends NotificationsUtil {
    public function dashboard() {
        global $DB;

        DashboardUtil::startPage(get_string_kopere('notification_title'), null, 'Notifications::settings');

        NotificationsUtil::mensagemNoSmtp();

        echo '<div class="element-box">';
        TitleUtil::printH3('notification_title');

        echo get_string_kopere('notification_subtitle');

        Button::add(get_string_kopere('notification_new'), 'Notifications::add', '', true, false, true);

        $events = $DB->get_records('kopere_dashboard_events');
        $eventsList = array();
        foreach ($events as $event) {
            /** @var base $eventClass */
            $eventClass = $event->event;

            $event->module_name = $this->moduleName($event->module, false);
            $event->event_name = $eventClass::get_name();
            $event->actions
                = "<div class=\"text-center\">
                                    ".Button::icon('edit', "Notifications::addSegundaEtapa&id={$event->id}", false)."&nbsp;&nbsp;&nbsp;
                                    ".Button::icon('delete', "Notifications::delete&id={$event->id}")."
                                </div>";

            $eventsList[] = $event;
        }

        if ($events) {
            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT);
            $table->addHeader(get_string_kopere('notification_table_module'), 'module_name');
            $table->addHeader(get_string_kopere('notification_table_action'), 'event_name');
            $table->addHeader(get_string_kopere('notification_table_subject'), 'subject');
            $table->addHeader(get_string_kopere('notification_table_active'), 'status', TableHeaderItem::RENDERER_VISIBLE);
            $table->addHeader(get_string_kopere('notification_from'), 'userfrom');
            $table->addHeader(get_string_kopere('notification_to'), 'userto');
            $table->addHeader('', 'actions', TableHeaderItem::TYPE_ACTION);

            // $table->setClickRedirect ( 'Users::details&userid={id}', 'id' );
            $table->printHeader();
            $table->setRow($events);
            $table->close();
        } else {
            Mensagem::printWarning(get_string_kopere('notification_table_empty'));
        }

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function add() {
        if (!AJAX_SCRIPT) {
            DashboardUtil::startPage(array(
                array('Notifications::dashboard', get_string_kopere('notification_title')),
                get_string_kopere('notification_new')
            ));
        } else {
            DashboardUtil::startPopup(get_string_kopere('notification_new'));
        }

        echo '<div class="element-box">';

        $events = $this->listEvents();
        $modulesList = array();
        foreach ($events->components as $components) {
            $moduleName = $this->moduleName($components, true);

            if ($moduleName != null) {
                $modulesList[] = array('key' => $components, 'value' => $moduleName);
            }
        }

        $form = new Form('Notifications::addSegundaEtapa');
        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('notification_add_module'))
                ->setName('module')
                ->setValues($modulesList)
                ->setAddSelecione(true)
                ->setDescription(get_string_kopere('notification_add_moduledesc'))
        );
        echo '<div id="restante-form">'.get_string_kopere('notification_add_selectmodule').'</div>';
        $form->close();

        ?>
        <script>
            $('#module').change(function () {
                var data = {
                    module: $(this).val()
                };
                $('#restante-form').load('open-ajax-table.php?NotificationsUtil::addFormExtra', data);
            });
        </script>
        <?php

        echo '</div>';
        if (!AJAX_SCRIPT) {
            DashboardUtil::endPage();
        } else {
            DashboardUtil::endPopup();
        }
    }

    public function addSegundaEtapa() {
        global $CFG, $DB;

        /** @var base $eventClass */
        $eventClass = optional_param('event', '', PARAM_RAW);
        $module = optional_param('module', '', PARAM_RAW);
        $id = optional_param('id', 0, PARAM_INT);

        if ($id) {
            /** @var kopere_dashboard_events $evento */
            $evento = $DB->get_record('kopere_dashboard_events', array('id' => $id));
            Header::notfoundNull($evento, get_string_kopere('notification_notound'));

            $eventClass = $evento->event;
            $module = $evento->module;

            DashboardUtil::startPage(array(
                array('Notifications::dashboard', get_string_kopere('notification_title')),
                get_string_kopere('notification_editing')
            ));
            echo '<div class="element-box">';
            TitleUtil::printH3('notification_editing');
        } else {
            $evento = kopere_dashboard_events::createNew();
            DashboardUtil::startPage(array(
                array('Notifications::dashboard', get_string_kopere('notification_title')),
                get_string_kopere('notification_new')
            ));
            echo '<div class="element-box">';
            TitleUtil::printH3('notification_new');
        }

        $form = new Form('Notifications::addSave');
        $form->createHiddenInput('id', $id);
        $form->createHiddenInput('event', $eventClass);
        $form->createHiddenInput('module', $module);

        $form->printRow(
            get_string_kopere('notification_add_action'),
            $eventClass::get_name());

        if ($id) {
            $status = array(
                array('key' => 1, 'value' => get_string_kopere('notification_status_active')),
                array('key' => 0, 'value' => get_string_kopere('notification_status_inactive'))
            );
            $form->addInput(
                InputSelect::newInstance()->setTitle(get_string_kopere('notification_status'))
                    ->setName('status')
                    ->setValues($status)
                    ->setValue($evento->status)
                    ->setDescription(get_string_kopere('notification_statusdesc')));
        }

        $valueFrom = array(
            array('key' => 'admin', 'value' => get_string_kopere('notification_from_admin'))
        );
        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('notification_from'))
                ->setName('userfrom')
                ->setValues($valueFrom)
                ->setValue($evento->userfrom)
                ->setDescription(get_string_kopere('notification_fromdesc')));

        $valueTo = array(
            array('key' => 'admin', 'value' => get_string_kopere('notification_todesc_admin')),
            array('key' => 'admins', 'value' => get_string_kopere('notification_todesc_admins')),
            array('key' => 'teachers', 'value' => get_string_kopere('notification_todesc_teachers')),
            array('key' => 'student', 'value' => get_string_kopere('notification_todesc_student')),
        );
        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('notification_to'))
                ->setName('userto')
                ->setValues($valueTo)
                ->setValue($evento->userto)
                ->setDescription(get_string_kopere('notification_todesc')));

        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('notification_subject'))
                ->setName('subject')
                ->setValue($evento->subject)
                ->setDescription(get_string_kopere('notification_subjectdesc')));

        $template = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/".get_config('local_kopere_dashboard', 'notificacao-template');
        $templateContent = file_get_contents($template);

        if (!$id) {
            if (strpos($module, 'mod_') === 0) {
                $mailText = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail-text/mod.html";
                $moduleName = get_string('modulename', $module);
            } else {
                $mailText = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail-text/{$module}.html";
                $moduleName = '';
            }

            if (file_exists($mailText)) {
                $htmlText = file_get_contents($mailText);
            } else {
                $htmlText = get_string_kopere('notification_message_html');
            }

            $htmlText = str_replace('{[event.name]}', $eventClass::get_name(), $htmlText);
            $htmlText = str_replace('{[module.name]}', $moduleName, $htmlText);
        } else {
            $htmlText = $evento->message;
        }

        $htmlTextarea = '<textarea name="message" id="message" style="height:500px">'.htmlspecialchars($htmlText).'</textarea>';
        $templateContent = str_replace('{[message]}', $htmlTextarea, $templateContent);
        $form->printPanel(get_string_kopere('notification_message'), $templateContent);
        echo TinyMce::createInputEditor('#message');

        if ($id) {
            $form->createSubmitInput(get_string_kopere('notification_update'));
        } else {
            $form->createSubmitInput(get_string_kopere('notification_create'));
        }

        $form->close();

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function addSave() {
        global $DB;

        $kopere_dashboard_events = kopere_dashboard_events::createNew();

        if ($kopere_dashboard_events->id) {
            $DB->update_record('kopere_dashboard_events', $kopere_dashboard_events);
        } else {
            $DB->insert_record('kopere_dashboard_events', $kopere_dashboard_events);
        }

        Mensagem::agendaMensagemSuccess(get_string_kopere('notification_created'));
        Header::location('Notifications::dashboard');
    }

    public function delete() {
        global $DB;

        $status = optional_param('status', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_events $event */
        $event = $DB->get_record('kopere_dashboard_events', array('id' => $id));
        Header::notfoundNull($event, get_string_kopere('notification_notfound'));

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_events', array('id' => $id));

            Mensagem::agendaMensagemSuccess(get_string_kopere('notification_delete_success'));
            Header::location('Notifications::dashboard');
        }

        DashboardUtil::startPage(array(
            array('Notifications::dashboard', get_string_kopere('notification_title'))
        ));

        echo "<p>".get_string_kopere('notification_delete_yes')."</p>";
        Button::delete(get_string('yes'), 'Notifications::delete&status=sim&id='.$event->id, '', false);
        Button::add(get_string('no'), 'Notifications::dashboard', 'margin-left-10', false);

        DashboardUtil::endPage();
    }

    public function settings() {
        global $CFG;
        ob_clean();
        DashboardUtil::startPopup(get_string_kopere('notification_setting_config'), 'Settings::settingsSave');

        $form = new Form();

        $values = array();
        $templates = glob("{$CFG->dirroot}/local/kopere_dashboard/assets/mail/*.html");
        foreach ($templates as $template) {
            $values[] = array('key' => pathinfo($template, PATHINFO_BASENAME));
        }

        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('notification_setting_template'))
                ->setValues($values, 'key', 'key')
                ->setValueByConfig('notificacao-template'));

        $form->printPanel(get_string_kopere('notification_setting_preview'), "<div id=\"area-mensagem-preview\"></div>");

        $form->printRow(get_string_kopere('notification_setting_templatelocation'), "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/");

        $form->close();

        ?>
        <script>
            $('#notificacao-template').change(notificacaoTemplateChange);

            function notificacaoTemplateChange() {
                var data = {
                    template: $('#notificacao-template').val()
                };
                $('#area-mensagem-preview').load('open-ajax-table.php?Notifications::settingsLoadTemplate', data);
            }

            notificacaoTemplateChange();
        </script>
        <style>
            .table-messagem {
                max-width: 600px;
            }
        </style>
        <?php

        DashboardUtil::endPopup();
    }
}