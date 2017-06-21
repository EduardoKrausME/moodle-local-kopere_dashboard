<?php

defined ( 'MOODLE_INTERNAL' ) || die;

if ( !$PAGE->requires->is_head_done () )
    $PAGE->requires->css ( '/local/kopere_dashboard/assets/style.css' );



if ( $hassiteconfig ) {

    if ( !$ADMIN->locate ( 'integracaoroot' ) )
        $ADMIN->add ( 'root', new admin_category( 'integracaoroot', 'Integrações' ) );

    $ADMIN->add ( 'integracaoroot',
        new admin_externalpage(
            'local_kopere_dashboard',
            get_string ( 'modulename', 'local_kopere_dashboard' ),
            $CFG->wwwroot . '/local/kopere_dashboard/open.php'
        )
    );
}