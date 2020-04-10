<?php
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

        $changue_mes = optional_param('changue_mes', date('Y-m'), PARAM_TEXT);

        $form = new form("?classname=useraccess&method=dashboard");
        $form->add_input(input_select::new_instance()
            ->set_title('Selecione o Mês')
            ->set_name('changue_mes')
            ->set_values($this->list_meses())
            ->set_value($changue_mes));
        $form->close();


        echo '<div class="element-box">';

        $table = new data_table();
        $table->add_header('#', 'userid', table_header_item::TYPE_INT, null, 'width: 20px');
        $table->add_header(get_string_kopere('user_table_fullname'), 'fullname');
        $table->add_header(get_string_kopere('user_table_email'), 'email');
        $table->add_header(get_string_kopere('user_table_phone'), 'phone1');
        $table->add_header(get_string_kopere('user_table_celphone'), 'phone2');
        $table->add_header(get_string_kopere('user_table_city'), 'city');

        $table->set_ajax_url("?classname=useraccess&method=load_all_users&changue_mes={$changue_mes}");
        $table->set_click_redirect("?classname=users&method=details&userid={id}", 'id');
        $table->print_header();
        $table->close(true, array("order" => array(array(1, "asc"))));

        echo '</div>';
        dashboard_util::end_page();

        $PAGE->requires->js_call_amd('local_kopere_dashboard/form_exec', 'useraccess_changue_mes');
        echo "<style>.area_changue_mes{display:none}</style>";
    }

    public function load_all_users() {
        $changue_mes = required_param('changue_mes', PARAM_TEXT);

        $columns = array(
            'userid',
            'firstname',
            'username',
            'email',
            'phone1',
            'phone2',
            'city',
            'lastname'
        );
        $search = new datatable_search_util($columns);

        $search->execute_sql_and_return("
               SELECT {[columns]}
                 FROM {logstore_standard_log} l
                 JOIN {user}                  u ON l.userid = u.id
                WHERE action LIKE 'loggedin'
                  AND date_format( from_unixtime(l.timecreated), '%Y-%m' ) LIKE '{$changue_mes}'
            ", 'GROUP BY l.userid', null,
            'local_kopere_dashboard\util\user_util::column_fullname');

    }

    private function list_meses() {
        $ultimosMeses = array();
        $ano = date('Y');
        $mes = date('m');
        for ($i = 0; $i < 24; $i++) {
            if ($mes < 10) {
                $mes = "0" . intval($mes);
            }
            $ultimosMeses[] = array('key' => "{$ano}-{$mes}", 'value' => "{$mes} / {$ano}");
            $mes--;
            if ($mes == 0) {
                $ano--;
                $mes = 12;
            }
        }

        return $ultimosMeses;
    }

}