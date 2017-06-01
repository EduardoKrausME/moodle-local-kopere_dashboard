<?php
/**
 * User: Eduardo Kraus
 * Date: 21/05/17
 * Time: 04:39
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Json;

class UsersOnline
{

    public function dashboard ()
    {
        DashboardUtil::startPage ( 'Usuários Online', null, 'UsersOnline::settings', 'Usuários-Online' );

        echo '<div class="element-box table-responsive">';
        echo '<h3>Abas abertas com o Moodle</h3>';


        $table = new DataTable();
        $table->addHeader ( '#', 'userid', TableHeaderItem::TYPE_INT );
        $table->addHeader ( 'Nome', 'fullname' );
        $table->addHeader ( 'Data', 'servertime', TableHeaderItem::RENDERER_DATE );

        if ( get_config ( 'local_kopere_dashboard', 'nodejs-status' ) ) {
            $table->addHeader ( 'Página', 'page' );
            $table->addHeader ( 'Foco', 'focus', TableHeaderItem::RENDERER_TRUEFALSE );
            $table->addHeader ( 'Monitor', 'screen' );
            $table->addHeader ( 'Navegador', 'navigator' );
            $table->addHeader ( 'Sistema Operacional', 'os' );
            $table->addHeader ( 'Device', 'device' );
        }

        $table->setAjaxUrl ( 'UsersOnline::loadAllUsers' );
        //$table->setClickRedirect ( '?Users::details&userid={userid}', 'userid' );
        $table->printHeader ();
        $tableName = $table->close ( false, 'order:[[1,"asc"]]' );

        echo "    <div id=\"user-list-online\" data-tableid=\"$tableName\"></div>
              </div>";
        DashboardUtil::endPage ();
    }

    public function loadAllUsers ( $time = 10 )
    {
        global $DB;

        if ( get_config ( 'local_kopere_dashboard', 'nodejs-status' ) )
            Json::encodeAndReturn ( null );

        $onlinestart = strtotime ( '-' . $time . ' minutes' );
        $timefinish  = time ();

        $data = $DB->get_records_sql ( "
                SELECT u.id AS userid, concat(firstname, ' ', lastname) AS fullname, lastaccess AS servertime, 
                       0 AS focus, '' AS page, '' AS title
                  FROM {user} u
                 WHERE u.lastaccess BETWEEN $onlinestart AND $timefinish
              ORDER BY u.timecreated DESC" );

        Json::encodeAndReturn ( $data );
    }

    public static function countOnline ( $time )
    {
        global $DB;

        $onlinestart = strtotime ( '-' . $time . ' minutes' );
        $timefinish  = time ();

        $count = $DB->get_record_sql ( "SELECT count(*) as num
								FROM {user} u
                                LEFT JOIN {context} cx ON cx.instanceid = u.id AND cx.contextlevel = 30
                                WHERE u.lastaccess BETWEEN $onlinestart AND $timefinish  ORDER BY u.timecreated DESC LIMIT 10" );

        return $count->num;
    }

    public function settings ()
    {
        ob_clean ();
        DashboardUtil::startPopup ( 'Configurações do servidor sincronização de Usuários On-line', 'Settings::settingsSave' );

        $form = new Form();

        $form->printRowOne ( '', Botao::help ( 'Usuários-Online' ) );

        $form->createCheckboxInput ( 'Habilitar Servidor de sincronização de Usuários On-line', 'nodejs-status', 1,
            get_config ( 'local_kopere_dashboard', 'nodejs-status' ) );

        echo '<div class="area-status-nodejs">';

        $form->printSpacer ( 10 );
        $form->createCheckboxInput ( 'Habilitar SSL?', 'nodejs-ssl', 1,
            get_config ( 'local_kopere_dashboard', 'nodejs-ssl' ) );

        $form->createTextInput ( 'URL do servidor', 'nodejs-url',
            get_config ( 'local_kopere_dashboard', 'nodejs-url' ) );

        $form->createTextInput ( 'Porta do servidor', 'nodejs-port',
            get_config ( 'local_kopere_dashboard', 'nodejs-port' ), 'int' );

        echo '</div>';

        $form->close ();

        ?>
        <script>
            $ ( '#nodejs-status' ).click ( statusNodeChange );

            function statusNodeChange ( delay ) {
                if ( delay != 0 )
                    delay = 400;
                if ( $ ( '#nodejs-status' ).is ( ":checked" ) )
                    $ ( '.area-status-nodejs' ).show ( delay );
                else
                    $ ( '.area-status-nodejs' ).hide ( delay );
            }

            statusNodeChange ( 0 );
        </script>
        <?php

        DashboardUtil::endPopup ();
    }
}