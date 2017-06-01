<?php
/**
 * User: Eduardo Kraus
 * Date: 13/05/17
 * Time: 13:28
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;

class Settings
{
    public function settingsSave ()
    {
        foreach ( $_POST as $name => $value ) {
            if ( $name == 'POST' )
                continue;
            if ( $name == 'action' )
                continue;
            if ( $name == 'redirect' )
                continue;

            set_config ( $name, $value, 'local_kopere_dashboard' );
        }

        Mensagem::agendaMensagemSuccess ( 'Configurações salvas!' );

        if ( isset( $_POST[ 'redirect' ] ) )
            Header::location ( '?' . $_POST[ 'redirect' ] );
    }
}