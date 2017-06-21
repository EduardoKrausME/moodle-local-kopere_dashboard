<?php

function xmldb_local_kopere_dashboard_upgrade ( $oldversion )
{
    global $DB;

    $dbman = $DB->get_manager ();

    if ( $oldversion < 2017061102 ) {
        $table  = new xmldb_table( 'kopere_dashboard_events' );
        $field1 = new xmldb_field( 'subject',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            XMLDB_NOTNULL,
            null,
            null,
            'userto' );

        if ( !$dbman->field_exists ( $table, $field1 ) )
            $dbman->add_field ( $table, $field1 );

        $field2 = new xmldb_field( 'status',
            XMLDB_TYPE_INTEGER,
            '1',
            null,
            XMLDB_NOTNULL,
            null,
            1,
            'event' );

        if ( !$dbman->field_exists ( $table, $field2 ) )
            $dbman->add_field ( $table, $field2 );

        upgrade_plugin_savepoint ( true, 2017061102, 'local', 'kopere_dashboard' );
    }


    return true;
}
