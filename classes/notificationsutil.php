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
 * @created    11/06/17 02:25
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use core\event\base;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\util\config;
use local_kopere_dashboard\util\end_util;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\release;

/**
 * Class notificationsutil
 * @package local_kopere_dashboard
 */
class notificationsutil {

    /**
     * @throws \coding_exception
     */
    public function add_form_extra() {
        $module = optional_param('module', '', PARAM_TEXT);

        if (!isset($module[1])) {
            end_util::end_script_show(get_string_kopere('notification_add_selectmodule'));
        }

        $events = $this->list_events();
        $eventslist = [];
        foreach ($events->eventinformation as $eventinformation) {
            if ($eventinformation['component_full'] == $module) {
                $eventslist[] = [
                    'key' => $eventinformation['eventname'],
                    'value' => $eventinformation['fulleventname']
                ];
            }
        }

        $form = new form();
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('notification_add_action'))
                ->set_name('event')
                ->set_values($eventslist)
                ->set_add_selecione(true));
        $form->create_submit_input(get_string_kopere('notification_add_create'));
    }

    /**
     * @return \stdClass
     */
    public function list_events() {
        $eventclasss = array_merge(
            \report_eventlist_list_generator::get_core_events_list(false),
            \report_eventlist_list_generator::get_non_core_event_list(false)
        );

        $components = [];
        $eventinformation = [];

        /** @var base $eventclass */
        foreach ($eventclasss as $eventclass => $file) {
            if ($file == 'base' || $file == 'legacy_logged' || $file === 'manager' || strpos($file, 'tool_') === 0) {
                continue;
            }

            try {
                $ref = new \ReflectionClass($eventclass);
                if (!$ref->isAbstract()) {

                    $data = $eventclass::get_static_info();

                    if ($data['component'] == 'core') {
                        $continue = true;
                        if (strpos($data['target'], 'course') === 0) {
                            $continue = false;
                        } else if (strpos($data['target'], 'user') === 0) {
                            $continue = false;
                        }

                        if ($continue) {
                            continue;
                        }
                    }

                    if ($data['component'] == 'mod_lesson') {
                        continue;
                    }

                    $crud = $data['crud'];
                    if ($crud == 'c' || $crud == 'u' || $crud == 'd') {
                        $tmp = $data;
                        $tmp['fulleventname'] = $eventclass::get_name();
                        $tmp['crudname'] = \report_eventlist_list_generator::get_crud_string($data['crud']);

                        if ($data['component'] == 'core') {
                            $components["{$data['component']}_{$data['target']}"] = "{$data['component']}_{$data['target']}";
                            $tmp['component_full'] = "{$data['component']}_{$data['target']}";
                        } else {
                            $components[$data['component']] = $data['component'];
                            $tmp['component_full'] = $data['component'];
                        }

                        $eventinformation[] = $tmp;
                    }
                }
            } catch (\Exception $e) {
                debugging($e->getMessage());
            }
        }

        $returned = new \stdClass ();
        $returned->components = $components;
        $returned->eventinformation = $eventinformation;

        return $returned;
    }

    /**
     * @throws \coding_exception
     */
    public function settings_load_template() {
        global $CFG, $COURSE;

        $template = optional_param('template', 'blue.html', PARAM_RAW);
        $templatefile = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/{$template}";

        $content = file_get_contents($templatefile);

        $linkmanager
            = "<a href='{$CFG->wwwroot}/message/notificationpreferences.php'
                  target='_blank' style='border-bottom:1px #777777 dotted; text-decoration:none; color:#777777;'>
                   " . get_string_kopere('notification_manager') . "
               </a>";

        $data = [
            'content' => $content,
            'tags' => [
                'moodle_fullname' => $COURSE->fullname,
                'moodle_shortname' => $COURSE->shortname,
                'message' => "<h2>Título</h2><p>Linha 1</p><p>Linha 2</p>",
                'date_year' => userdate(time(), '%Y'),
                'manager' => $linkmanager
            ]
        ];

        ob_clean();
        die(json_encode($data));
    }

    /**
     * @param $component
     * @param $onlyused
     * @return null|string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    protected function module_name($component, $onlyused) {
        global $DB;

        switch ($component) {
            case 'core_course_category':
                return get_string_kopere('notification_core_course_category');
            case 'core_course':
                return get_string_kopere('notification_core_course');
            case 'core_course_completion':
                return 'Conclusão de Cursos';
            case 'course_module_created':
                return 'Criação de novo Módulo em Curso';
            case 'core_course_content':
                return 'Conteúdo de Cursos';
            case 'core_course_module':
                return 'Módulo de cursos';
            case 'core_course_section':
                return 'Sessões de cursos';

            case 'core_user':
                return get_string_kopere('notification_core_user');
            case 'core_user_enrolment':
                return get_string_kopere('notification_core_user_enrolment');
            case 'core_user_password':
                return 'Senhas';

            case 'local_kopere_dashboard':
                return get_string_kopere('notification_local_kopere_dashboard');
            case 'local_kopere_hotmoodle':
                return get_string_kopere('notification_local_kopere_hotmoodle');
            case 'local_kopere_moocommerce':
                return get_string_kopere('notification_local_kopere_moocommerce');
            case 'local_kopere_pay':
                return get_string_kopere('notification_local_kopere_pay');
        }

        if (strpos($component, 'mod_') === 0) {
            $module = substr($component, 4);

            $sql
                = "SELECT COUNT(*) AS num
                     FROM {course_modules} cm
                     JOIN {modules} m ON cm.module = m.id
                    WHERE m.name = :name
                      AND cm.deletioninprogress = 0";
            $count = $DB->get_record_sql($sql, ['name' => $module]);

            if ($count->num || !$onlyused) {
                return get_string('resource') . ': ' . get_string('modulename', $module);
            }
        }

        return null;
    }

    /**
     * @throws \dml_exception
     */
    public static function mensagem_no_smtp() {
        global $CFG;
        if (strlen(get_config('moodle', 'smtphosts')) > 5) {
            return;
        }

        if (release::version() < 3.2) {
            $CFG->mail = 'messagesettingemail';
            mensagem::print_danger(get_string_kopere('notification_error_smtp', $CFG));
        } else {
            $CFG->mail = 'outgoingmailconfig';
            mensagem::print_danger(get_string_kopere('notification_error_smtp', $CFG));
        }
    }

    /**
     * @return string
     */
    public static function get_template_html() {
        global $CFG;

        $notificacaotemplatehtml = config::get_key('notificacao-template-html');
        if ($notificacaotemplatehtml) {
            return $notificacaotemplatehtml;
        }

        $notificacaotemplate = config::get_key('notificacao-template');
        if ($notificacaotemplate) {
            if (file_exists("{$CFG->dirroot}/local/kopere_dashboard/assets/mail/{$notificacaotemplate}")) {
                return file_get_contents("{$CFG->dirroot}/local/kopere_dashboard/assets/mail/{$notificacaotemplate}");
            }
        }

        return file_get_contents("{$CFG->dirroot}/local/kopere_dashboard/assets/mail/Blue.html");
    }
}
