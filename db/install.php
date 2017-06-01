<?php


function xmldb_local_kopere_dashboard_install ()
{
    set_config ( 'enablegravatar', 1 );
    set_config ( 'gravatardefaulturl', 'mm' );
    set_config ( 'timezone', 'America/Sao_Paulo' );
    //set_config ( 'autolang', '0' );
    //set_config ( 'langmenu', '0' );
    //set_config ( 'authloginviaemail', '1' );

    set_config ( 'webpages_theme', 'standard', 'local_kopere_dashboard' );
    set_config ( 'notificacao-template', 'Cinza.html', 'local_kopere_dashboard' );

    return true;
}

function changeConfigTable ( $name, $newVale )
{
    global $DB;

    $config = $DB->get_record ( 'config', array( 'name' => $name ) );
    if ( $config ) {
        $config->value = $newVale;
        $DB->update_record ( 'config', $config );
    }
}