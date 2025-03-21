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
 * useronline file
 *
 * introduced 21/05/17 04:39
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_base;
use local_kopere_dashboard\html\inputs\input_checkbox_select;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\json;
use local_kopere_dashboard\util\node;
use local_kopere_dashboard\util\url_util;
use local_kopere_dashboard\util\user_util;

/**
 * Class useronline
 *
 * @package local_kopere_dashboard
 */
class useronline {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        dashboard_util::add_breadcrumb(get_string("useronline_title", "local_kopere_dashboard"));
        dashboard_util::start_page(null, "Usuários-Online");

        echo '<div class="element-box table-responsive">';

        $table = new data_table();
        $table->add_header("#", "userid", table_header_item::TYPE_INT);
        $table->add_header(get_string("useronline_table_fullname", "local_kopere_dashboard"), "fullname");
        $table->add_header(get_string("useronline_table_date", "local_kopere_dashboard"),
            "servertime", table_header_item::RENDERER_DATE);

        $table->set_ajax_url(url_util::makeurl("useronline", "load_all_users"));
        $table->print_header();
        $tablename = $table->close(false, ["order" => [[1, "asc"]]]);

        echo "    <div id='user-list-online' data-tableid='{$tablename}'></div>
              </div>";

        dashboard_util::end_page();
    }

    /**
     * Function load_all_users
     *
     * @param int $time
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function load_all_users($time = 10) {
        global $DB;

        $onlinestart = strtotime("-{$time} minutes");
        $timefinish = time();

        $result = $DB->get_records_sql("
                SELECT u.id AS userid, firstname, lastname, lastaccess AS servertime,
                       0 AS focus, '' AS page, '' AS title
                  FROM {user} u
                 WHERE u.lastaccess BETWEEN $onlinestart AND $timefinish
              ORDER BY u.timecreated DESC");

        $result = user_util::column_fullname($result, "fullname");
        json::encode($result);
    }

    /**
     * Function count
     *
     * @param $time
     *
     * @return int
     * @throws \dml_exception
     */
    public static function count($time) {
        global $DB;

        $onlinestart = strtotime("-{$time} minutes");

        $count = $DB->get_record_sql(
            "SELECT count(u.id) AS num
               FROM {user} u
              WHERE u.lastaccess > :onlinestart
           GROUP BY u.id
           ORDER BY u.timecreated DESC
              LIMIT 1",
            ["onlinestart" => $onlinestart]);

        if ($count) {
            return $count->num;
        } else {
            return 0;
        }
    }
}
