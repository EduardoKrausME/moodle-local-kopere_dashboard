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

use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputBase;
use local_kopere_dashboard\html\inputs\InputCheckbox;
use local_kopere_dashboard\html\inputs\InputText;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Json;

class UsersOnline {

    public function dashboard() {
        DashboardUtil::startPage('Usuários Online', null, 'UsersOnline::settings', 'Usuários-Online');

        echo '<div class="element-box table-responsive">';
        echo '<h3>Abas abertas com o Moodle</h3>';

        $table = new DataTable();
        $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT);
        $table->addHeader('Nome', 'fullname');
        $table->addHeader('Data', 'servertime', TableHeaderItem::RENDERER_DATE);

        if (get_config('local_kopere_dashboard', 'nodejs-status')) {
            $table->addHeader('Página', 'page');
            $table->addHeader('Foco', 'focus', TableHeaderItem::RENDERER_TRUEFALSE);
            $table->addHeader('Monitor', 'screen');
            $table->addHeader('Navegador', 'navigator');
            $table->addHeader('Sistema Operacional', 'os');
            $table->addHeader('Device', 'device');
        }

        $table->setAjaxUrl('UsersOnline::loadAllUsers');
        // $table->setClickRedirect ( 'Users::details&userid={userid}', 'userid' );
        $table->printHeader();
        $tableName = $table->close(false, 'order:[[1,"asc"]]');

        echo "    <div id=\"user-list-online\" data-tableid=\"$tableName\"></div>
              </div>";
        DashboardUtil::endPage();
    }

    public function loadAllUsers($time = 10) {
        global $DB;

        if (get_config('local_kopere_dashboard', 'nodejs-status')) {
            Json::encodeAndReturn(null);
        }

        $onlinestart = strtotime('-' . $time . ' minutes');
        $timefinish = time();

        $data = $DB->get_records_sql("
                SELECT u.id AS userid, concat(firstname, ' ', lastname) AS fullname, lastaccess AS servertime,
                       0 AS focus, '' AS page, '' AS title
                  FROM {user} u
                 WHERE u.lastaccess BETWEEN $onlinestart AND $timefinish
              ORDER BY u.timecreated DESC");

        Json::encodeAndReturn($data);
    }

    public static function countOnline($time) {
        global $DB;

        $onlinestart = strtotime('-' . $time . ' minutes');
        $timefinish = time();

        $count = $DB->get_record_sql(
            "SELECT count(*) as num
               FROM {user} u
          LEFT JOIN {context} cx ON cx.instanceid = u.id
                                AND cx.contextlevel = :contextlevel
              WHERE u.lastaccess BETWEEN $onlinestart
                AND $timefinish
           ORDER BY u.timecreated DESC LIMIT 10",
            array('contextlevel' => CONTEXT_USER));

        return $count->num;
    }

    public function settings() {
        ob_clean();
        DashboardUtil::startPopup('Configurações do servidor sincronização de Usuários On-line', 'Settings::settingsSave');

        $form = new Form();

        $form->printRowOne('', Botao::help('Usuários-Online'));

        $form->addInput(
            InputCheckbox::newInstance()->setTitle('Habilitar Servidor de sincronização de Usuários On-line')
                ->setCheckedByConfig('nodejs-status'));

        echo '<div class="area-status-nodejs">';

        $form->printSpacer(10);

        $form->addInput(
            InputCheckbox::newInstance()->setTitle('Habilitar SSL?')
                ->setCheckedByConfig('nodejs-ssl'));

        $form->addInput(
            InputText::newInstance()->setTitle('URL do servidor')
                ->setValueByConfig('nodejs-url'));

        $form->addInput(
            InputText::newInstance()->setTitle('Porta do servidor')
                ->setValueByConfig('nodejs-port')
                ->addValidator(InputBase::VAL_INT));

        echo '</div>';

        $form->close();

        ?>
        <script>
            $('#nodejs-status').click(statusNodeChange);

            function statusNodeChange(delay) {
                if (delay != 0) {
                    delay = 400;
                }
                if ($('#nodejs-status').is(":checked")) {
                    $('.area-status-nodejs').show(delay);
                }
                else {
                    $('.area-status-nodejs').hide(delay);
                }
            }

            statusNodeChange(0);
        </script>
        <?php

        DashboardUtil::endPopup();
    }
}