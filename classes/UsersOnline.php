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

use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputBase;
use local_kopere_dashboard\html\inputs\InputCheckbox;
use local_kopere_dashboard\html\inputs\InputText;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Json;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\util\UserUtil;

/**
 * Class UsersOnline
 * @package local_kopere_dashboard
 */
class UsersOnline {

    /**
     *
     */
    public function dashboard() {
        DashboardUtil::startPage(get_string_kopere('useronline_title'), -1, 'UsersOnline::settings', 'Usuários-Online');

        echo '<div class="element-box table-responsive">';

        $table = new DataTable();
        $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT);
        $table->addHeader(get_string_kopere('useronline_table_fullname'), 'fullname');
        $table->addHeader(get_string_kopere('useronline_table_date'), 'servertime', TableHeaderItem::RENDERER_DATE);

        if (get_config('local_kopere_dashboard', 'nodejs-status')) {
            $table->addHeader(get_string_kopere('useronline_table_page'), 'page');
            $table->addHeader(get_string_kopere('useronline_table_focus'), 'focus', TableHeaderItem::RENDERER_TRUEFALSE);
            $table->addHeader(get_string_kopere('useronline_table_screen'), 'screen');
            $table->addHeader(get_string_kopere('useronline_table_navigator'), 'navigator');
            $table->addHeader(get_string_kopere('useronline_table_os'), 'os');
            $table->addHeader(get_string_kopere('useronline_table_device'), 'device');
        }

        $table->setAjaxUrl('UsersOnline::loadAllUsers');
        // $table->setClickRedirect ( 'Users::details&userid={userid}', 'userid' );
        $table->printHeader();
        $tableName = $table->close(false, 'order:[[1,"asc"]]');

        echo "    <div id=\"user-list-online\" data-tableid=\"$tableName\"></div>
              </div>";
        DashboardUtil::endPage();
    }

    /**
     * @param int $time
     */
    public function loadAllUsers($time = 10) {
        global $DB;

        if (get_config('local_kopere_dashboard', 'nodejs-status')) {
            Json::encodeAndReturn(null);
        }

        $onlinestart = strtotime('-' . $time . ' minutes');
        $timefinish = time();

        $result = $DB->get_records_sql("
                SELECT u.id AS userid, firstname, lastname, lastaccess AS servertime,
                       0 AS focus, '' AS page, '' AS title
                  FROM {user} u
                 WHERE u.lastaccess BETWEEN $onlinestart AND $timefinish
              ORDER BY u.timecreated DESC");

        $result = UserUtil::createColumnFullname($result, 'fullname');
        Json::encodeAndReturn($result);
    }

    /**
     * @param $time
     * @return int
     */
    public static function countOnline($time) {
        global $DB;

        $onlinestart = strtotime('-' . $time . ' minutes');
        $timefinish = time();

        $count = $DB->get_record_sql(
            "SELECT count(u.id) as num
               FROM {user} u
          LEFT JOIN {context} cx ON cx.instanceid = u.id
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

        if($count)
            return $count->num;
        else
            return 0;
    }

    /**
     *
     */
    public function settings() {
        ob_clean();
        DashboardUtil::startPopup(get_string_kopere('useronline_settings_title'), 'Settings::settingsSave');

        $form = new Form();

        $form->printRowOne('', Button::help('Usuários-Online'));

        $form->addInput(
            InputCheckbox::newInstance()->setTitle(get_string_kopere('useronline_settings_status'))
                ->setCheckedByConfig('nodejs-status'));

        echo '<div class="area-status-nodejs">';

        $form->printSpacer(10);

        $form->addInput(
            InputCheckbox::newInstance()->setTitle(get_string_kopere('useronline_settings_ssl'))
                ->setCheckedByConfig('nodejs-ssl'));

        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('useronline_settings_url'))
                ->setValueByConfig('nodejs-url'));

        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('useronline_settings_port'))
                ->setValueByConfig('nodejs-port')
                ->addValidator(InputBase::VAL_INT));

        echo '</div>';

        $form->close();

        ?>
        <script>
            jQuery ( '#nodejs-status' ).click ( statusNodeChange );

            function statusNodeChange ( delay ) {
                if ( delay != 0 ) {
                    delay = 400;
                }
                console.log ( 'nodejs-status Click' );
                if ( $ ( '#nodejs-status' ).is ( ":checked" ) ) {
                    console.log ( 'nodejs-status' );
                    $ ( '.area-status-nodejs' ).show ( delay );
                }
                else {
                    $ ( '.area-status-nodejs' ).hide ( delay );
                }
            }

            statusNodeChange ( 0 );
        </script>
        <?php

        DashboardUtil::endPopup();
    }
}