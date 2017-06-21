<?php
/**
 * User: Eduardo Kraus
 * Date: 30/01/17
 * Time: 08:34
 */

require ( '../../config.php' );

global $PAGE, $CFG, $OUTPUT;

require_once ( $CFG->libdir . '/adminlib.php' );

require_login ();
require_capability ( 'moodle/site:config', context_system::instance () );

$PAGE->set_url ( new moodle_url( '/local/kopere_dashboard/open.php' ) );
$PAGE->set_context ( context_system::instance () );
$PAGE->set_pagetype ( 'admin-setting' );
$PAGE->set_pagelayout ( 'admin' );
$PAGE->set_title ( 'Abrir Dashboard' );
$PAGE->set_heading ( 'Abrir Dashboard' );

$PAGE->requires->jquery ();
$PAGE->requires->css ( '/local/kopere_dashboard/assets/style.css' );
$PAGE->requires->js  ( '/local/kopere_dashboard/assets/popup.js' );

echo $OUTPUT->header ();
echo $OUTPUT->heading ( 'Abrir Dashboard' );

?>
    <div class="text-center" style="text-align: center;">
        <a type="button" target="_blank"
           class="dashboard-load-popup btn btn-lg btn-primary"
           href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/open-dashboard.php?Dashboard::start"
           id="open-startup">Abrir Dashboard</a>
    </div>

    <div id="modalWindow" style="display:none">
        <div id="base-popup">
            <a href="#" id="base-popup-close" role="button">X</a>
            <div id="operations">
                <iframe src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/open-dashboard.php?Dashboard::start"
                        frameborder="0" width="100%" height="100%"></iframe>
            </div>
        </div>
        <div class="ui-widget-overlay"></div>
    </div>
<?php


echo $OUTPUT->footer ();



