<?php

use local_kopere_dashboard\util\DashboardUtil;

ob_start ();
define ( 'AJAX_SCRIPT', false );

define ( 'BENCHSTART', microtime ( true ) );
require ( '../../config.php' );
define ( 'BENCHSTOP', microtime ( true ) );

require_once 'autoload.php';

global $PAGE, $CFG, $OUTPUT;

require_login ();
require_capability ( 'moodle/site:config', context_system::instance () );

$PAGE->set_url ( new moodle_url( '/local/kopere_dashboard/open-dashboard.php' ) );
$PAGE->set_pagetype ( 'admin-setting' );
$PAGE->set_context ( context_system::instance () );

if ( !strlen ( $_SERVER[ 'QUERY_STRING' ] ) )
    $_SERVER[ 'QUERY_STRING' ] = 'Dashboard::start';

if ( isset( $_POST[ 'action' ] ) ) {
    loadByQuery ( $_POST[ 'action' ] );
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link  href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/css/dashboard.css" rel="stylesheet">
    <link  href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/bootstrap/bootstrap.css" rel="stylesheet">
    <link  href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/jquery.dataTables.css" rel="stylesheet">

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/jquery-3.2.1.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/dashboard.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/bootstrap/bootstrap.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-numeric-comma.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-currency.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-date-uk.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-file-size.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/renderer-visible.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/moment.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/daterangepicker.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/select2.full.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/validator.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/jquery.maskedinput.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/jquery.validate-v1.15.0.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/custom.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/node/socket.io.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/node/app-v1.js"></script>

    <?php
    if ( get_config ( 'local_kopere_dashboard', 'nodejs-status' ) ) {
        if ( get_config ( 'local_kopere_dashboard', 'nodejs-ssl' ) )
            $url = "https://" . get_config ( 'local_kopere_dashboard', 'nodejs-url' ) . ':' . get_config ( 'local_kopere_dashboard', 'nodejs-port' );
        else
            $url = get_config ( 'local_kopere_dashboard', 'nodejs-url' ) . ':' . get_config ( 'local_kopere_dashboard', 'nodejs-port' );

        $userid     = intval ( $USER->id );
        $fullname   = '"' . fullname ( $USER ) . '"';
        $serverTime = time ();
        $urlNode    = '"' . $url . '"';

        echo "<script type=\"text/javascript\">
                  startServer ( $userid, $fullname, $serverTime, $urlNode, 'z35admin' );
              </script>";
    }
    ?>
    <link rel="icon" href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/favicon.png"/>

</head>
<body>
<script>
    if ( window != window.top ) {
        document.body.className += " in-iframe";
    }
</script>
<div class="all-wrapper">

    <div class="layout-w">

        <div class="menu-w hidden-print">
            <div class="logo-w">
                <img class="normal"
                     src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/logo.svg"
                     alt="Kopere DashBoard">
                <img class="mobile"
                     src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/logo-notext.svg"
                     alt="Kopere DashBoard">
            </div>
            <div class="menu-and-user">
                <ul class="main-menu">
                    <?php

                    DashboardUtil::addMenu ( 'Dashboard::start', 'dashboard', 'Dashboard' );
                    DashboardUtil::addMenu ( 'Users::dashboard', 'users', 'Usuários', array(
                        array( 'UsersOnline::dashboard', 'Usuários Online', 'users-online' )
                    ) );
                    DashboardUtil::addMenu ( 'Courses::dashboard', 'courses', 'Cursos' );

                    $plugins = $DB->get_records_sql ( "SELECT plugin FROM {config_plugins} WHERE plugin LIKE 'local\_kopere\_dashboard\_%' AND name LIKE 'version'" );
                    foreach ( $plugins as $plugin ) {
                        $className = $plugin->plugin . '\\Menu';
                        $class     = new $className();
                        $class->showMenu ();
                    }

                    DashboardUtil::addMenu ( 'Reports::dashboard', 'report', 'Relatórios',
                        \local_kopere_dashboard\Reports::globalMenus () );

                    //DashboardUtil::addMenu ( 'Gamification::dashboard', 'gamification', 'Gamificação' );

                    //DashboardUtil::addMenu ( 'Notifications::dashboard', 'notifications', 'Notificações' );

                    DashboardUtil::addMenu ( 'WebPages::dashboard', 'webpages', 'Paginas estáticas' );
                    DashboardUtil::addMenu ( 'Benchmark::test', 'performace', 'Performace' );
                    DashboardUtil::addMenu ( 'Backup::dashboard', 'data', 'Backup' );
                    DashboardUtil::addMenu ( 'About::dashboard', 'about', 'Sobre' );
                    ?>
                </ul>
            </div>
        </div>

        <div class="content-w">
            <?php
            $queryString = $_SERVER[ 'QUERY_STRING' ];
            loadByQuery ( $queryString );
            ?>
        </div>


    </div>
</div>

<div class="modal fade" id="modal-edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loader"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-details" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="loader"></div>
        </div>
    </div>
</div>

</body>
</html>