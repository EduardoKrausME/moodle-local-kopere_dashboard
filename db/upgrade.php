<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_local_kopere_dashboard_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2017061102) {
        $table = new xmldb_table('kopere_dashboard_events');
        $field1 = new xmldb_field('subject',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            XMLDB_NOTNULL,
            null,
            null,
            'userto');

        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        $field2 = new xmldb_field('status',
            XMLDB_TYPE_INTEGER,
            '1',
            null,
            XMLDB_NOTNULL,
            null,
            1,
            'event');

        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        upgrade_plugin_savepoint(true, 2017061102, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2017071714) {

        if (!$dbman->table_exists('kopere_dashboard_reportcat')) {
            $table = new xmldb_table('kopere_dashboard_reportcat');

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('image', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('enable', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL);
            $table->add_field('enablesql', XMLDB_TYPE_TEXT, 'big');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $table->add_index('type', XMLDB_INDEX_NOTUNIQUE, array('type'));

            $dbman->create_table($table);
        }

        if (!$dbman->table_exists('kopere_dashboard_reports')) {
            $table = new xmldb_table('kopere_dashboard_reports');

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $table->add_field('reportcatid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL);
            $table->add_field('reportkey', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL);
            $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('enable', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL);
            $table->add_field('enablesql', XMLDB_TYPE_TEXT, 'big');
            $table->add_field('reportsql', XMLDB_TYPE_TEXT, 'big');
            $table->add_field('prerequisit', XMLDB_TYPE_CHAR, '60', null, XMLDB_NOTNULL);
            $table->add_field('columns', XMLDB_TYPE_TEXT, 'big');
            $table->add_field('foreach', XMLDB_TYPE_TEXT, 'big');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2017071714, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2017081601) {
        $table = new xmldb_table('kopere_dashboard_menu');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, array('link'));

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_webpages');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, array('link'));

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_events');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, array('module', 'event'));

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_reportcat');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, array('type'));

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_reports');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, array('reportkey'));

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        upgrade_plugin_savepoint(true, 2017081601, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2018032409) {
        $DB->delete_records('kopere_dashboard_reports');
        $DB->delete_records('kopere_dashboard_reportcat');

        upgrade_plugin_savepoint(true, 2018032409, 'local', 'kopere_dashboard');
    }

    \local_kopere_dashboard\install\report_install::create_categores();
    \local_kopere_dashboard\install\report_install::create_reports();

    \local_kopere_dashboard\install\users_import_install::install_or_update();

    return true;
}
