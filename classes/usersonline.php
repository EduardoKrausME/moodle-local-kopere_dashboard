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
 * @created    21/05/17 04:39
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_base;
use local_kopere_dashboard\html\inputs\input_checkbox;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\json;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\util\user_util;

/**
 * Class usersonline
 * @package local_kopere_dashboard
 */
class usersonline {

    /**
     *
     */
    public function dashboard() {
        dashboard_util::start_page(get_string_kopere('useronline_title'), -1, 'usersonline::settings', 'Usuários-Online');

        echo '<div class="element-box table-responsive">';

        $table = new data_table();
        $table->add_header('#', 'userid', table_header_item::TYPE_INT);
        $table->add_header(get_string_kopere('useronline_table_fullname'), 'fullname');
        $table->add_header(get_string_kopere('useronline_table_date'), 'servertime', table_header_item::RENDERER_DATE);

        if (get_config('local_kopere_dashboard', 'nodejs-status')) {
            $table->add_header(get_string_kopere('useronline_table_page'), 'page');
            $table->add_header(get_string_kopere('useronline_table_focus'), 'focus', table_header_item::RENDERER_TRUEFALSE);
            $table->add_header(get_string_kopere('useronline_table_screen'), 'screen');
            $table->add_header(get_string_kopere('useronline_table_navigator'), 'navigator');
            $table->add_header(get_string_kopere('useronline_table_os'), 'os');
            $table->add_header(get_string_kopere('useronline_table_device'), 'device');
        }

        $table->set_ajax_url('usersonline::load_all_users');
        $table->print_header();
        $tablename = $table->close(false, 'order:[[1,"asc"]]');

        echo "    <div id=\"user-list-online\" data-tableid=\"$tablename\"></div>
              </div>";
        dashboard_util::end_page();
    }

    /**
     * @param int $time
     */
    public function load_all_users($time = 10) {
        global $DB;

        if (get_config('local_kopere_dashboard', 'nodejs-status')) {
            json::encode(null);
        }

        $onlinestart = strtotime('-' . $time . ' minutes');
        $timefinish = time();

        $result = $DB->get_records_sql("
                SELECT u.id AS userid, firstname, lastname, lastaccess AS servertime,
                       0 AS focus, '' AS page, '' AS title
                  FROM {user} u
                 WHERE u.lastaccess BETWEEN $onlinestart AND $timefinish
              ORDER BY u.timecreated DESC");

        $result = user_util::column_fullname($result, 'fullname');
        json::encode($result);
    }

    /**
     * @param $time
     * @return int
     */
    public static function count($time) {
        global $DB;

        $onlinestart = strtotime('-' . $time . ' minutes');
        $timefinish = time();

        $count = $DB->get_record_sql(
            "SELECT count(u.id) AS num
               FROM {USER} u
          LEFT JOIN {CONTEXT} cx ON cx.instanceid = u.id
              WHERE u.lastaccess    > :onlinestart
                AND u.lastaccess    < :timefinish
                AND cx.contextlevel = :contextlevel
           GROUP BY u.id
           ORDER BY u.timecreated DESC
              LIMIT 1",
            array(
                'contextlevel' => CONTEXT_USER,
                'onlinestart' => $onlinestart,
                'timefinish' => $timefinish
            ));

        if ($count) {
            return $count->num;
        } else {
            return 0;
        }
    }

    /**
     *
     */
    public function settings() {
        ob_clean();
        dashboard_util::start_popup(get_string_kopere('useronline_settings_title'), 'settings::save');

        $form = new form();

        $form->print_row_one('', button::help('Usuários-Online'));

        $form->add_input(
            input_checkbox::new_instance()->set_title(get_string_kopere('useronline_settings_status'))
                ->set_checked_by_config('nodejs-status'));

        echo '<div class="area-status-nodejs">';

        $form->print_spacer(10);

        $form->add_input(
            input_checkbox::new_instance()->set_title(get_string_kopere('useronline_settings_ssl'))
                ->set_checked_by_config('nodejs-ssl'));

        $form->add_input(
            input_text::new_instance()->set_title(get_string_kopere('useronline_settings_url'))
                ->set_value_by_config('nodejs-url'));

        $form->add_input(
            input_text::new_instance()->set_title(get_string_kopere('useronline_settings_port'))
                ->set_value_by_config('nodejs-port')
                ->add_validator(input_base::VAL_INT));

        echo '</div>';

        $form->close();

        ?>
        <script>
            jQuery('#nodejs-status').click(status_node_change);

            function status_node_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }
                console.log('nodejs-status Click');
                if ($('#nodejs-status').is(":checked")) {
                    console.log('nodejs-status');
                    $('.area-status-nodejs').show(delay);
                }
                else {
                    $('.area-status-nodejs').hide(delay);
                }
            }

            status_node_change(0);
        </script>
        <?php

        dashboard_util::end_popup();
    }
}