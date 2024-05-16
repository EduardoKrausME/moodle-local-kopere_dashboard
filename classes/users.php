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
 * @created    31/01/17 05:32
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\datatable_search_util;
use local_kopere_dashboard\util\header;

/**
 * Class users
 * @package local_kopere_dashboard
 */
class users {

    /**
     * @throws \coding_exception
     */
    public function dashboard() {
        dashboard_util::add_breadcrumb(get_string_kopere('user_title'));
        dashboard_util::start_page();

        echo '<div class="element-box table-responsive">';

        $table = new data_table();
        $table->add_header('#', 'id', table_header_item::TYPE_INT);
        $table->add_header(get_string_kopere('user_table_fullname'), 'fullname');
        $table->add_header(get_string_kopere('user_table_username'), 'username');
        $table->add_header(get_string_kopere('user_table_email'), 'email');
        $table->add_header(get_string_kopere('user_table_phone'), 'phone1');
        $table->add_header(get_string_kopere('user_table_celphone'), 'phone2');
        $table->add_header(get_string_kopere('user_table_city'), 'city');

        $table->set_ajax_url(local_kopere_dashboard_makeurl("users", "load_all_users"));
        $table->set_click_redirect(local_kopere_dashboard_makeurl("users", "details", ["userid" => "{id}"]), 'id');
        $table->print_header();
        $table->close(true, ["order" => [[1, "asc"]]]);

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function load_all_users() {
        $columns = [
            'id',
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
                 FROM {user} u
                WHERE id > 1 AND deleted = 0 ", '', null,
            'local_kopere_dashboard\util\user_util::column_fullname');
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function details() {
        global $DB;

        $userid = optional_param('userid', 0, PARAM_INT);

        $user = $DB->get_record('user', ['id' => $userid]);
        header::notfound_null($user, get_string_kopere('profile_notfound'));

        dashboard_util::add_breadcrumb(get_string_kopere('profile_title'), local_kopere_dashboard_makeurl("users", "dashboard"));
        dashboard_util::add_breadcrumb(fullname($user));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        profile::details($user);

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * @param bool $format
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function count_all($format = false) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) AS num FROM {user} WHERE id > 1 AND deleted = 0');

        if ($format) {
            return number_format($count->num, 0, get_string('decsep', 'langconfig'), get_string('thousandssep', 'langconfig'));
        }

        return $count->num;
    }

    /**
     * @param bool $format
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function count_all_learners($format = false) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) AS num FROM {user} WHERE id > 1 AND deleted = 0 AND lastaccess > 0');

        if ($format) {
            return number_format($count->num, 0, get_string('decsep', 'langconfig'), get_string('thousandssep', 'langconfig'));
        }

        return $count->num;
    }
}
