<?php

/**
 * User: Eduardo Kraus
 * Date: 31/01/17
 * Time: 05:32
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\DatatableSearchUtil;

class Users
{
    public function dashboard ()
    {
        DashboardUtil::startPage ( 'Usuários' );

        echo '<div class="element-box table-responsive">';
        echo '<h3>Usuários</h3>';

        $table = new DataTable();
        $table->addHeader ( '#', 'id', TableHeaderItem::TYPE_INT );
        $table->addHeader ( 'Nome', 'nome' );
        $table->addHeader ( 'Username', 'username' );
        $table->addHeader ( 'E-mail', 'email' );
        $table->addHeader ( 'Telefone Fixo', 'phone1' );
        $table->addHeader ( 'Celular', 'phone2' );
        $table->addHeader ( 'Cidade', 'city' );

        $table->setAjaxUrl ( 'Users::loadAllUsers' );
        $table->setClickRedirect ( '?Users::details&userid={id}', 'id' );
        $table->printHeader ();
        //$table->close ();
        $table->close ( true, 'order:[[1,"asc"]]' );

        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function loadAllUsers ()
    {
        $columnSelect = array(
            'id',
            'concat(firstname, " ", lastname) as nome',
            'username',
            'email',
            'phone1',
            'phone2',
            'city'
        );
        $columnOrder  = array(
            'id',
            array( 'nome', 'concat(firstname, \' \', lastname)' ),
            'username',
            'email',
            'phone1',
            'phone2',
            'city'
        );

        $search = new DatatableSearchUtil( $columnSelect, $columnOrder );

        $search->executeSqlAndReturn ( "
               SELECT {[columns]}
                 FROM {user} u
                WHERE id > 1 AND deleted = 0 " );
    }

    public function details ()
    {
        $profile = new Profile();
        $profile->details ();
    }

    public static function countAll ( $format = false )
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT count(*) as num FROM {user} WHERE id > 1 AND deleted = 0' );

        if ( $format )
            return number_format ( $count->num, 0, ',', '.' );

        return $count->num;
    }

    public static function countAllLearners ( $format = false )
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT count(*) as num FROM {user} WHERE id > 1 AND deleted = 0 AND lastaccess > 0' );

        if ( $format )
            return number_format ( $count->num, 0, ',', '.' );

        return $count->num;
    }


}