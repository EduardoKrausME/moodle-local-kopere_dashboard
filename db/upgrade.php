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

/**
 * @param $oldversion
 *
 * @return bool
 * @throws Exception
 */
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

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_index('type', XMLDB_INDEX_NOTUNIQUE, ['type']);

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

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2017071714, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2017081601) {
        $table = new xmldb_table('kopere_dashboard_menu');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, ['link']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_webpages');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, ['link']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_events');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, ['module', 'event']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_reportcat');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, ['type']);

        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        $table = new xmldb_table('kopere_dashboard_reports');
        $index = new xmldb_index('unique', XMLDB_INDEX_NOTUNIQUE, ['reportkey']);

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

    if ($oldversion < 2020062002) {
        if (!$dbman->table_exists('kopere_dashboard_performance')) {
            $table = new xmldb_table('kopere_dashboard_performance');

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $table->add_field('time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $table->add_field('type', XMLDB_TYPE_CHAR, '7', null, XMLDB_NOTNULL);

            $field = new xmldb_field('value', XMLDB_TYPE_CHAR, '5', null, XMLDB_NOTNULL);
            $field->setDecimals(3);
            $table->addField($field);

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_index('type', XMLDB_INDEX_NOTUNIQUE, ['time', 'type']);

            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2020062002, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2023010200) {
        $DB->delete_records('kopere_dashboard_reportcat');
        $DB->delete_records('kopere_dashboard_reports');
        upgrade_plugin_savepoint(true, 2023010200, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2023071200) {
        $DB->delete_records('kopere_dashboard_reports');
        $DB->delete_records('kopere_dashboard_reportcat');

        upgrade_plugin_savepoint(true, 2023071200, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2023072700) {
        if (!$dbman->table_exists('kopere_dashboard_reportlogin')) {
            $table = new xmldb_table('kopere_dashboard_reportlogin');

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL);
            $table->add_field('ip', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL);

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2023072700, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2023072704) {
        $DB->delete_records('kopere_dashboard_reports');
        $DB->delete_records('kopere_dashboard_reportcat');

        upgrade_plugin_savepoint(true, 2023072704, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2024013100) {
        if (!$dbman->table_exists('kopere_dashboard_courseacces')) {
            $table = new xmldb_table('kopere_dashboard_courseacces');

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL);
            $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL);
            $table->add_field('context', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL);
            $table->add_field('contagem', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL);

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2024013100, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2024050102) {
        $fonts = "<style>\n@import url('https://fonts.googleapis.com/css2?family=Acme" .
            "&family=Almendra:ital,wght@0,400;0,700;1,400;1,700" .
            "&family=Bad+Script" .
            "&family=Dancing+Script:wght@400..700" .
            "&family=Great+Vibes" .
            "&family=Marck+Script" .
            "&family=Nanum+Pen+Script" .
            "&family=Orbitron:wght@400..900" .
            "&family=Ubuntu+Condensed" .
            "&family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700" .
            "&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');\n</style>";
        set_config('kopere_dashboard_pagefonts', $fonts);
        upgrade_plugin_savepoint(true, 2024050102, 'local', 'kopere_dashboard');
    }

    if ($oldversion < 2024050900) {
        $table = new xmldb_table('kopere_dashboard_menu');
        $field1 = new xmldb_field('menuid', XMLDB_TYPE_INTEGER, 11, null, null, null, null, 'id');

        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        $DB->execute("UPDATE {kopere_dashboard_menu} SET menuid = 0");
    }

    \local_kopere_dashboard\install\report_install::create_categores();
    \local_kopere_dashboard\install\report_install::create_reports();

    \local_kopere_dashboard\install\users_import_install::install_or_update();

    return true;
}
