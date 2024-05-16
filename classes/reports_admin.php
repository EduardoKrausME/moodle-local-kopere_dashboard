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
 * @created    13/05/17 13:29
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_checkbox_select;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\html\inputs\input_textarea;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\string_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\vo\kopere_dashboard_reports;

/**
 * Class reports_admin
 * @package local_kopere_dashboard
 */
class reports_admin {
    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function editar() {
        global $DB;

        dashboard_util::add_breadcrumb(get_string_kopere('reports_settings_title'));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $report = optional_param('report', 0, PARAM_INT);
        if ($report == -1) {
            $reportcat = optional_param('reportcat', 0, PARAM_INT);

            $koperereports = new kopere_dashboard_reports();
            $koperereports->reportcatid = $reportcat;

        } else {
            /** @var kopere_dashboard_reports $koperereports */
            $koperereports = $DB->get_record('kopere_dashboard_reports',
                ['id' => $report]);
            header::notfound_null($koperereports, get_string_kopere('reports_notfound'));
        }

        $form = new form(local_kopere_dashboard_makeurl("reports", "save", ["report" => $report]));

        $form->create_hidden_input('reportcatid', $koperereports->reportcatid);

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('reports_settings_form_title'))
                ->set_name('title')
                ->set_value($koperereports->title));

        $form->add_input(
            input_checkbox_select::new_instance()
                ->set_title(get_string_kopere('reports_settings_form_enable'))
                ->set_name('enable')
                ->set_checked($koperereports->enable));

        $columns = json_decode($koperereports->columns);
        if ($report == -1) {
            $columns->columns[] = $this->add_header();
            $columns->columns[] = $this->add_header();
            $columns->columns[] = $this->add_header();
            $columns->columns[] = $this->add_header();
            $columns->columns[] = $this->add_header();
            $columns->columns[] = $this->add_header();
        } else {
            $columns->columns[] = $this->add_header(1);
            $columns->columns[] = $this->add_header();
            $columns->columns[] = $this->add_header();
        }

        echo '</div></div><div class="content-box"><div class="element-box">';
        title_util::print_h2("reports_settings_form_colunas");
        foreach ($columns->columns as $id => $colum) {

            if (isset($colum->description)) {
                mensagem::print_info(get_string_kopere($colum->description));
            }
            echo "<div class='row'><div class='col-5'>";
            $form->add_input(
                input_text::new_instance()
                    ->set_title(get_string_kopere('reports_settings_form_colunas_title'))
                    ->set_name("columns[{$id}][title]")
                    ->set_value($colum->title));

            echo "</div><div class='col-4'>";

            $form->add_input(
                input_text::new_instance()
                    ->set_title(get_string_kopere('reports_settings_form_colunas_chave'))
                    ->set_name("columns[{$id}][chave]")
                    ->set_value($colum->chave));

            echo "</div><div class='col-3'>";

            $form->add_input(
                input_select::new_instance()
                    ->set_title(get_string_kopere('reports_settings_form_colunas_type'))
                    ->set_name("columns[{$id}][type]")
                    ->set_values([
                        ['key' => '',
                            'value' => get_string_kopere('reports_settings_form_none')],
                        [
                            'key' => table_header_item::TYPE_INT,
                            'value' => get_string_kopere('reports_settings_form_colunas_type_int'),
                        ],
                        [
                            'key' => table_header_item::TYPE_DATE,
                            'value' => get_string_kopere('reports_settings_form_colunas_type_date'),
                        ],
                        [
                            'key' => table_header_item::TYPE_CURRENCY,
                            'value' => get_string_kopere('reports_settings_form_colunas_type_currency'),
                        ],
                        ['key' => table_header_item::TYPE_TEXT,
                            'value' => get_string_kopere('reports_settings_form_colunas_type_text'),
                        ],
                        ['key' => table_header_item::TYPE_BYTES,
                            'value' => get_string_kopere('reports_settings_form_colunas_type_bytes'),
                        ],
                    ])
                    ->set_value($colum->type));

            echo "</div></div>";

            $form->create_hidden_input("columns[{$id}][funcao]", $colum->funcao);
            $form->create_hidden_input("columns[{$id}][style_header]", $colum->style_header);
            $form->create_hidden_input("columns[{$id}][style_col]", $colum->style_col);
        };
        echo '</div></div><div class="content-box"><div class="element-box">';

        foreach ($columns->header as $id => $header) {
            $form->create_hidden_input("header[{$id}][funcao]", $header->funcao);
            $form->create_hidden_input("header[{$id}][title]", $header->title);
            $form->create_hidden_input("header[{$id}][type]", $header->type);
            $form->create_hidden_input("header[{$id}][chave]", $header->chave);
            $form->create_hidden_input("header[{$id}][class]", $header->class);
            $form->create_hidden_input("header[{$id}][styleheader]", $header->styleheader);
            $form->create_hidden_input("header[{$id}][stylecol]", $header->stylecol);
            $form->create_hidden_input("header[{$id}][cols]", $header->cols);
        }

        $form->add_input(
            input_textarea::new_instance()
                ->set_title(get_string_kopere('reports_settings_form_reportsql'))
                ->set_name('reportsql')
                ->set_value($koperereports->reportsql));

        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('reports_settings_form_prerequisit'))
                ->set_name('prerequisit')
                ->set_values([
                    [
                        'key' => '',
                        'value' => get_string_kopere('reports_settings_form_none'),
                    ],
                    [
                        'key' => 'listCourses',
                        'value' => get_string_kopere('reports_settings_form_prerequisit_listCourses'),
                    ],
                ])
                ->set_value($koperereports->prerequisit));

        $koperereports->foreach = str_replace('local_kopere_dashboard\report\report_foreach::', '', $koperereports->foreach);
        $values = [
            ['key' => '', 'value' => get_string_kopere('reports_settings_form_none')],
            array(
                'key' => 'badge_status_text',
                'value' => get_string_kopere('reports_settings_form_prerequisit_badge_status_text'),
            ),
            array(
                'key' => 'badge_criteria_type',
                'value' => get_string_kopere('reports_settings_form_prerequisit_badge_criteria_type'),
            ),
            array(
                'key' => 'userfullname',
                'value' => get_string_kopere('reports_settings_form_prerequisit_userfullname'),
            ),
            array(
                'key' => 'courses_group_mode',
                'value' => get_string_kopere('reports_settings_form_prerequisit_courses_group_mode'),
            ),
        ];
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('reports_settings_form_foreach'))
                ->set_name('foreach')
                ->set_values($values)
                ->set_value($koperereports->foreach)
                ->set_description("<a href='https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/" .
                    "blob/master/classes/report/report_foreach.php' target='_blank'>Code report_foreach.php</a>"));

        $form->create_submit_input(get_string_kopere('reports_settings_form_save'));

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function save() {
        global $DB;

        if (form::check_post()) {

            $report = optional_param('report', 0, PARAM_INT);
            if ($report == -1) {

                $koperereports = new kopere_dashboard_reports();
                $koperereports->reportcatid = optional_param('reportcat', 0, PARAM_INT);
                $koperereports->reportkey = string_util::generate_random_string(12);
                $koperereports->title = required_param('title', PARAM_TEXT);
                $koperereports->reportsql = required_param('reportsql', PARAM_TEXT);
                $koperereports->prerequisit = required_param('prerequisit', PARAM_TEXT);
                $koperereports->foreach = required_param('foreach', PARAM_TEXT);
                $koperereports->columns = $this->columns();

                $id = $DB->insert_record('kopere_dashboard_reports', $koperereports);

                mensagem::agenda_mensagem_success(get_string_kopere('reports_settings_savesuccess'));
                header::location(local_kopere_dashboard_makeurl("reports", "load_report", ["report" => $id]));

            } else {
                /** @var kopere_dashboard_reports $koperereports */
                $koperereports = $DB->get_record('kopere_dashboard_reports',
                    ['id' => $report]);
                header::notfound_null($koperereports, get_string_kopere('reports_notfound'));

                $koperereports->title = required_param('title', PARAM_TEXT);
                $koperereports->reportsql = required_param('reportsql', PARAM_TEXT);
                $koperereports->prerequisit = required_param('prerequisit', PARAM_TEXT);
                $koperereports->foreach = required_param('foreach', PARAM_TEXT);
                $koperereports->columns = $this->columns();

                $DB->update_record('kopere_dashboard_reports', $koperereports);
                mensagem::agenda_mensagem_success(get_string_kopere('reports_settings_savesuccess'));

                header::location(local_kopere_dashboard_makeurl("reports", "load_report", ["report" => $koperereports->id]));
            }
        }
    }

    private function columns() {
        $columns = [];
        foreach ($_POST['columns'] as $colum) {
            if (isset($colum['title'][1])) {
                $columns[] = $colum;
            }
        }
        return json_encode(array(
            'columns' => $columns,
            'header' => $_POST['header']
        ));
    }

    private function add_header($adddescription = false) {
        $column = new \stdClass();
        $column->chave = "";
        $column->type = "";
        $column->title = "";
        $column->funcao = "";
        $column->style_header = "";
        $column->style_col = "";
        if ($adddescription) {
            $column->description = 'reports_settings_form_colunas_extra';
        }

        return $column;
    }
}
