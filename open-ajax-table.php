<?php
/**
 * User: Eduardo Kraus
 * Date: 30/01/17
 * Time: 08:34
 */

ob_start ();
define ( 'AJAX_SCRIPT', true );

require ( '../../config.php' );
require_once 'autoload.php';

global $PAGE, $CFG, $OUTPUT;

ob_clean ();

try {
    require_capability ( 'moodle/site:config', context_system::instance () );
} catch ( Exception $e ) { ?>
    <script>
        location.reload ();
    </script>
    <meta http-equiv="refresh" content="0; url=#">
    <?php
    die();
}

$PAGE->set_url ( new moodle_url( '/local/kopere_dashboard/open-ajax-table.php' ) );
//$PAGE->set_pagetype ( 'admin-setting' );
$PAGE->set_pagetype ( 'reports' );
$PAGE->set_context ( context_system::instance () );

loadByQuery ( $_SERVER[ 'QUERY_STRING' ] );
