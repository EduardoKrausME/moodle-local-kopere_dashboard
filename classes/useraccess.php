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
 * User: Eduardo Kraus
 * Date: 10/04/2020
 * Time: 18:05
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\datatable_search_util;

class useraccess {

    public function dashboard() {
        global $PAGE;

        dashboard_util::add_breadcrumb(get_string_kopere('useraccess_title'));
        dashboard_util::start_page();

        echo '<div class="element-box bloco_changue_mes">';
        $changuemes = optional_param('changue_mes', date('Y-m'), PARAM_TEXT);
        $form = new form("" . local_kopere_dashboard_makeurl("useraccess", "dashboard"));
        $form->add_input(input_select::new_instance()
            ->set_title('Selecione o Mês')
            ->set_name('changue_mes')
            ->set_values($this->list_meses())
            ->set_value($changuemes));
        $form->close();
        echo '</div>';

        echo '<div class="element-box">';
        $table = new data_table();
        $table->add_header('#', 'userid', table_header_item::TYPE_INT, null, 'width: 20px');
        $table->add_header(get_string_kopere('user_table_fullname'), 'fullname');
        $table->add_header(get_string_kopere('user_table_email'), 'email');
        $table->add_header(get_string_kopere('user_table_phone'), 'phone1');
        $table->add_header(get_string_kopere('user_table_celphone'), 'phone2');
        $table->add_header(get_string_kopere('user_table_city'), 'city');

        $table->set_ajax_url(local_kopere_dashboard_makeurl("useraccess", "load_all_users", ["changue_mes" => $changuemes]));
        $table->set_click_redirect(local_kopere_dashboard_makeurl("users", "details", ["userid" => "{id}"]), 'id');
        $table->print_header();
        $table->close(true, ["order" => [[1, "asc"]]]);

        echo '</div>';
        dashboard_util::end_page();

        $PAGE->requires->js_call_amd('local_kopere_dashboard/useraccess', 'useraccess_changue_mes');
        echo "<style>.bloco_changue_mes{display:none}</style>";
    }

    public function load_all_users() {
        $changuemes = required_param('changue_mes', PARAM_TEXT);

        $columns = [
            'userid',
            'firstname',
            'username',
            'email',
            'phone1',
            'phone2',
            'city',
            'lastname'
        ];
        $search = new datatable_search_util($columns);

        $search->execute_sql_and_return("
               SELECT {[columns]}
                 FROM {logstore_standard_log} l
                 JOIN {user}                  u ON l.userid = u.id
                WHERE action LIKE 'loggedin'
                  AND date_format( from_unixtime(l.timecreated), '%Y-%m' ) LIKE '{$changuemes}'
            ", 'GROUP BY l.userid', null,
            'local_kopere_dashboard\util\user_util::column_fullname');

    }

    private function list_meses() {
        $ultimosmeses = [];
        $ano = date('Y');
        $mes = date('m');
        for ($i = 0; $i < 24; $i++) {
            if ($mes < 10) {
                $mes = "0" . intval($mes);
            }
            $ultimosmeses[] = ['key' => "{$ano}-{$mes}", 'value' => "{$mes} / {$ano}"];
            $mes--;
            if ($mes == 0) {
                $ano--;
                $mes = 12;
            }
        }

        return $ultimosmeses;
    }
}
