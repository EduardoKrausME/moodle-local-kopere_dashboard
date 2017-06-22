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
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputSelect;

class NotificationsUtil {
    public function addFormExtra() {
        $module = optional_param('module', '', PARAM_TEXT);

        if (!isset($module[1])) {
            die('Selecione o Módulo!');
        }

        $events = $this->listEvents();
        $eventsList = array();
        foreach ($events->eventinformation as $eventinformation) {
            if ($eventinformation['component_full'] == $module) {
                $eventsList[] = array(
                    'key' => $eventinformation['eventname'],
                    'value' => $eventinformation['fulleventname']
                );
            }
        }

        $form = new Form();
        $form->addInput(
            InputSelect::newInstance()->setTitle('De qual ação deseja receber notificações?')
                ->setName('event')
                ->setValues($eventsList)
                ->setAddSelecione(true));
        $form->createSubmitInput('Criar Mensagem');
    }

    public function listEvents() {
        $eventClasss = array_merge(
            \report_eventlist_list_generator::get_core_events_list(false),
            \report_eventlist_list_generator::get_non_core_event_list(false)
        );

        $components = array();
        $eventinformation = array();

        /** @var base $eventClass */
        foreach ($eventClasss as $eventClass => $file) {
            if ($file == 'base' || $file == 'legacy_logged' || $file === 'manager' || strpos($file, 'tool_') === 0) {
                continue;
            }

            try {
                $ref = new \ReflectionClass($eventClass);
                if (!$ref->isAbstract()) {

                    $data = $eventClass::get_static_info();

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
                        $tmp['fulleventname'] = $eventClass::get_name();
                        $tmp['crudname'] = \report_eventlist_list_generator::get_crud_string($data['crud']);

                        if ($data['component'] == 'core') {
                            $components[$data['component'] . '_' . $data['target']] = $data['component'] . '_' . $data['target'];
                            $tmp['component_full'] = $data['component'] . '_' . $data['target'];
                        } else {
                            $components[$data['component']] = $data['component'];
                            $tmp['component_full'] = $data['component'];
                        }

                        $eventinformation[] = $tmp;
                    }
                }
            } catch (\Exception $e) {
                // Se der erro, ignora
            }
        }

        $returned = new \stdClass ();
        $returned->components = $components;
        $returned->eventinformation = $eventinformation;

        return $returned;
    }

    public function settingsLoadTemplate() {
        global $CFG, $COURSE;

        $template = optional_param('template', 'blue.html', PARAM_RAW);
        $templateFile = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/{$template}";

        $content = file_get_contents($templateFile);

        $linkManager
            = "<a href=\"{$CFG->wwwroot}/local/kopere_dashboard/open-dashboard.php?Notifications::dashboard\"
                  target=\"_blank\" style=\"border-bottom:1px #777777 dotted; text-decoration:none; color:#777777;\">
                            Gerenciar Mensagens
                        </a>";

        $content = str_replace('{[moodle.fullname]}', $COURSE->fullname, $content);
        $content = str_replace('{[moodle.shortname]}', $COURSE->shortname, $content);
        $content = str_replace('{[message]}', "<h2>Título</h2><p>Linha 1</p><p>Linha 2</p>", $content);
        $content = str_replace('{[date.year]}', userdate(time(), '%Y'), $content);
        $content = str_replace('{[manager]}"', $linkManager, $content);

        echo $content;
    }

    protected function moduleName($component, $onlyUsed) {
        global $DB;

        switch ($component) {
            case 'core_course_category':
                return 'Categoria de Cursos';
            case 'core_course':
                return 'Cursos';
            // case 'core_course_completion':
            // return 'Conclusão de Cursos';
            // case 'core_course_content':
            // return 'Conteúdo de Cursos';
            // case 'core_course_module':
            // return 'Módulo de cursos';
            // case 'core_course_section':
            // return 'Sessões de cursos';

            case 'core_user':
                return 'Usuários';
            case 'core_user_enrolment':
                return 'Matrículas de Usuários';
            // case 'core_user_password':
            // return 'Senhas';

            case 'local_kopere_dashboard_hotmoodle':
                return 'Kopere HotMoodle';
            case 'local_kopere_dashboard_moocommerce':
                return 'Kopere MooCommerce';
            case 'local_kopere_dashboard_pagamento':
                return 'Kopere Pagamento';
        }

        if (strpos($component, 'mod_') === 0) {
            $module = substr($component, 4);

            $sql
                = "SELECT COUNT(*) as num
                     FROM {course_modules} cm
                     JOIN {modules} m ON cm.module = m.id
                    WHERE m.name = :name";
            $count = $DB->get_record_sql($sql, array('name' => $module));

            if ($count->num || !$onlyUsed) {
                return get_string('resource') . ': ' . get_string('modulename', $module);
            }
        }

        return null;
    }
}