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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\DatatableSearchUtil;
use local_kopere_dashboard\util\TitleUtil;

class Users {
    public function dashboard() {
        DashboardUtil::startPage('Usuários');

        echo '<div class="element-box table-responsive">';
        TitleUtil::printH3('user_title');

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT);
        $table->addHeader(get_string_kopere('user_table_fullname'), 'fullname');
        $table->addHeader(get_string_kopere('user_table_username'), 'username');
        $table->addHeader(get_string_kopere('user_table_email'), 'email');
        $table->addHeader(get_string_kopere('user_table_phone'), 'phone1');
        $table->addHeader(get_string_kopere('user_table_celphone'), 'phone2');
        $table->addHeader(get_string_kopere('user_table_city'), 'city');

        $table->setAjaxUrl('Users::loadAllUsers');
        $table->setClickRedirect('Users::details&userid={id}', 'id');
        $table->printHeader();
        $table->close(true, 'order:[[1,"asc"]]');

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function loadAllUsers() {
        $columnSelect = array(
            'id',
            'concat(firstname, " ", lastname) as fullname',
            'username',
            'email',
            'phone1',
            'phone2',
            'city'
        );
        $columnOrder = array(
            'id',
            array('fullname', 'concat(firstname, \' \', lastname)'),
            'username',
            'email',
            'phone1',
            'phone2',
            'city'
        );

        $search = new DatatableSearchUtil($columnSelect, $columnOrder);

        $search->executeSqlAndReturn("
               SELECT {[columns]}
                 FROM {user} u
                WHERE id > 1 AND deleted = 0 ");
    }

    public function details() {
        $profile = new Profile();
        $profile->details();
    }

    public static function countAll($format = false) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) as num FROM {user} WHERE id > 1 AND deleted = 0');

        if ($format) {
            return number_format($count->num, 0, ',', '.');
        }

        return $count->num;
    }

    public static function countAllLearners($format = false) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) as num FROM {user} WHERE id > 1 AND deleted = 0 AND lastaccess > 0');

        if ($format) {
            return number_format($count->num, 0, ',', '.');
        }

        return $count->num;
    }

}