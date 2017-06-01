<?php


function local_kopere_dashboard_extends_navigation ( global_navigation $nav )
{
    local_kopere_dashboard_extend_navigation ( $nav );
}

function local_kopere_dashboard_extend_navigation ( global_navigation $nav )
{
    global $CFG, $PAGE, $USER, $DB;

    try {
        $menus = $DB->get_records ( 'kopere_dashboard_menu' );
        /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
        foreach ( $menus as $menu ) {
            $nav->add (
                $menu->title,
                new moodle_url( $CFG->wwwroot . '/local/kopere_dashboard/?menu=' . $menu->link ),
                navigation_node::TYPE_CUSTOM,
                null, null,
                new pix_icon( 'webpages', $menu->title, 'local_kopere_dashboard' )
            );
        }
    } catch ( Exception $e ) {
    }

    $context = context_system::instance ();
    if ( isloggedin () && has_capability ( 'moodle/site:config', $context ) ) {
        $nav->add (
            get_string ( 'pluginname', 'local_kopere_dashboard' ),
            new moodle_url( $CFG->wwwroot . '/local/kopere_dashboard/open.php' ),
            navigation_node::TYPE_CUSTOM,
            null, null,
            new pix_icon( 'icon', get_string ( 'pluginname', 'local_kopere_dashboard' ), 'local_kopere_dashboard' )
        );
    }

    if ( get_config ( 'local_kopere_dashboard', 'nodejs-status' ) ) {

        $PAGE->requires->jquery ();
        $PAGE->requires->js ( new moodle_url( $CFG->wwwroot . '/local/kopere_dashboard/node/socket.io.js' ), true );
        $PAGE->requires->js ( new moodle_url( $CFG->wwwroot . '/local/kopere_dashboard/node/app-v1.js' ), true );

        if ( get_config ( 'local_kopere_dashboard', 'nodejs-ssl' ) )
            $url = "https://" . get_config ( 'local_kopere_dashboard', 'nodejs-url' ) . ':' . get_config ( 'local_kopere_dashboard', 'nodejs-port' );
        else
            $url = get_config ( 'local_kopere_dashboard', 'nodejs-url' ) . ':' . get_config ( 'local_kopere_dashboard', 'nodejs-port' );

        $userid     = intval ( $USER->id );
        $fullname   = '"' . fullname ( $USER ) . '"';
        $serverTime = time ();
        $urlNode    = '"' . $url . '"';

        $PAGE->requires->js_init_code ( "startServer( $userid, $fullname, $serverTime, $urlNode )" );
    }
}
