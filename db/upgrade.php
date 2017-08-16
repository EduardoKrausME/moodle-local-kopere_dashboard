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

function xmldb_local_kopere_dashboard_upgrade($oldversion) {
    global $DB, $CFG;

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

    if ($oldversion < 2017081600) {

        $reportcat = $DB->get_record('kopere_dashboard_reportcat', array('type' => 'badge'));
        $reportcat->enablesql = "SELECT id as status FROM {badge} LIMIT 1";
        $DB->update_record('kopere_dashboard_reportcat', $reportcat);

        $reportcat = $DB->get_record('kopere_dashboard_reportcat', array('type' => 'enrol_cohort'));
        if ($CFG->dbtype == 'pgsql') {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE \"name\" LIKE 'enrol_plugins_enabled' AND \"value\" LIKE '%cohort%' LIMIT 1";
        } else {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE `name` LIKE 'enrol_plugins_enabled' AND `value` LIKE '%cohort%' LIMIT 1";
        }
        $DB->update_record('kopere_dashboard_reportcat', $reportcat);

        $reportcat = $DB->get_record('kopere_dashboard_reportcat', array('type' => 'enrol_guest'));
        if ($CFG->dbtype == 'pgsql') {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE \"name\" LIKE 'enrol_plugins_enabled' AND \"value\" LIKE '%guest%' LIMIT 1";
        } else {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE `name` LIKE 'enrol_plugins_enabled' AND `value` LIKE '%guest%' LIMIT 1";
        }
        $DB->update_record('kopere_dashboard_reportcat', $reportcat);

        // Reports
        $report = $DB->get_record('kopere_dashboard_reports', array('reportkey' => 'badge-2'));
        if ($report) {
            if ($CFG->dbtype == 'pgsql') {
                $report->reportsql = ' SELECT d.id, u.id AS userid, ' . get_all_user_name_fields(true, 'u') . ', u.lastname, b."name" AS badgename, 
                                      t.criteriatype, t.method, d.dateissued,
                                      (SELECT c.shortname FROM {course} c WHERE c.id = b.courseid) as course
                                 FROM {badge_issued}   d
                                 JOIN {badge}          b ON d.badgeid = b.id
                                 JOIN {user}           u ON d.userid  = u.id
                                 JOIN {badge_criteria} t ON b.id      = t.badgeid
                                WHERE t.criteriatype != 0
                              ORDER BY u.username';
            } else {
                $report->reportsql = ' SELECT d.id, u.id AS userid, ' . get_all_user_name_fields(true, 'u') . ', u.lastname, b.name AS badgename, 
                                      t.criteriatype, t.method, d.dateissued,
                                      (SELECT c.shortname FROM {course} c WHERE c.id = b.courseid) as course
                                 FROM {badge_issued}   d
                                 JOIN {badge}          b ON d.badgeid = b.id
                                 JOIN {user}           u ON d.userid  = u.id
                                 JOIN {badge_criteria} t ON b.id      = t.badgeid
                                WHERE t.criteriatype != 0
                              ORDER BY u.username';
            }
            $DB->update_record('kopere_dashboard_reports', $report);
        }

        $report = $DB->get_record('kopere_dashboard_reports', array('reportkey' => 'courses-2'));
        if ($report) {
            if ($CFG->dbtype == 'pgsql') {
                $report->reportsql = ' SELECT concat(c.id,g.id), c.id, c.fullname, c.shortname, g."name", c.visible, c.groupmode
                                 FROM {course} c
                                 JOIN {groups} g ON c.id = g.courseid
                                WHERE c.groupmode > 0';
            } else {
                $report->reportsql = ' SELECT concat(c.id,g.id), c.id, c.fullname, c.shortname, g.name, c.visible, c.groupmode
                                 FROM {course} c
                                 JOIN {groups} g ON c.id = g.courseid
                                WHERE c.groupmode > 0';
            }
            $DB->update_record('kopere_dashboard_reports', $report);
        }

        $report = $DB->get_record('kopere_dashboard_reports', array('reportkey' => 'user-7'));
        if ($report) {
            if ($CFG->dbtype == 'pgsql') {
                $report->reportsql = ' SELECT u.id, ul.timeaccess, ' . get_all_user_name_fields(true, 'u') . ', u.email, u.city, u.lastaccess,
                                      c.fullname, c.shortname,
                                      (SELECT r."name"
                                         FROM {user_enrolments} ue2
                                         JOIN {enrol} e ON e.id = ue2.enrolid
                                         JOIN {role}  r ON e.id = r.id
                                        WHERE ue2.userid = u.id
                                          AND e.courseid = c.id ) AS rolename
                                 FROM {user_enrolments} ue
                                 JOIN {enrol}  e ON e.id = ue.enrolid
                                 JOIN {course} c ON c.id = e.courseid
                                 JOIN {user}   u ON u.id = ue.userid
                                 LEFT JOIN {user_lastaccess} ul ON ul.userid = u.id
                                WHERE ul.timeaccess IS NULL';
            } else {
                $report->reportsql = ' SELECT u.id, ul.timeaccess, ' . get_all_user_name_fields(true, 'u') . ', u.email, u.city, u.lastaccess,
                                      c.fullname, c.shortname,
                                      (SELECT r.name
                                         FROM {user_enrolments} ue2
                                         JOIN {enrol} e ON e.id = ue2.enrolid
                                         JOIN {role}  r ON e.id = r.id
                                        WHERE ue2.userid = u.id
                                          AND e.courseid = c.id ) AS rolename
                                 FROM {user_enrolments} ue
                                 JOIN {enrol}  e ON e.id = ue.enrolid
                                 JOIN {course} c ON c.id = e.courseid
                                 JOIN {user}   u ON u.id = ue.userid
                                 LEFT JOIN {user_lastaccess} ul ON ul.userid = u.id
                                WHERE ul.timeaccess IS NULL';
            }
            $DB->update_record('kopere_dashboard_reports', $report);
        }

        $sql = "SELECT *
                  FROM {config_plugins}
                 WHERE plugin LIKE 'local\_kopere\_dashboard\_hotmoodle'";
        $plugin = $DB->get_records_sql($sql);
        if (!$plugin) {
            $where = array(
                'module' => 'local_kopere_dashboard_hotmoodle'
            );
            $DB->delete_records('kopere_dashboard_events', $where);
        }

        upgrade_plugin_savepoint(true, 2017081600, 'local', 'kopere_dashboard');
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
    }

    \local_kopere_dashboard\install\ReportInstall::createCategores();
    \local_kopere_dashboard\install\ReportInstall::createReports();

    \local_kopere_dashboard\install\UsersImportInstall::installOrUpdate();

    return true;
}
