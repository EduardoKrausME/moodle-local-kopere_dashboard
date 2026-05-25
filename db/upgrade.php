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
 * upgrade file
 *
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link https://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\install\event_install;

/**
 * Function xmldb_local_kopere_dashboard_upgrade
 *
 * @param $oldversion
 *
 * @return bool
 * @throws Exception
 * @throws ddl_change_structure_exception
 * @throws ddl_exception
 * @throws ddl_table_missing_exception
 * @throws Exception
 * @throws downgrade_exception
 * @throws upgrade_exception
 * @throws Exception
 */
function xmldb_local_kopere_dashboard_upgrade($oldversion) {
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026051002) {
        $table = new xmldb_table("local_kopere_dashboard_audit");

        $table->add_field("id", XMLDB_TYPE_INTEGER, "10", null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field("timecreated", XMLDB_TYPE_INTEGER, "10", null, XMLDB_NOTNULL, null, null);
        $table->add_field("userid", XMLDB_TYPE_INTEGER, "10", null, null, null, null);
        $table->add_field("component", XMLDB_TYPE_CHAR, "100", null, XMLDB_NOTNULL, null, null);
        $table->add_field("action", XMLDB_TYPE_CHAR, "50", null, XMLDB_NOTNULL, null, null);
        $table->add_field("objecttable", XMLDB_TYPE_CHAR, "100", null, null, null, null);
        $table->add_field("objectid", XMLDB_TYPE_INTEGER, "10", null, null, null, null);
        $table->add_field("description", XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field("contextid", XMLDB_TYPE_INTEGER, "10", null, null, null, null);
        $table->add_field("ip", XMLDB_TYPE_CHAR, "45", null, null, null, null);
        $table->add_field("useragent", XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field("detailsjson", XMLDB_TYPE_TEXT, null, null, null, null, null);

        $table->add_key("primary", XMLDB_KEY_PRIMARY, ["id"]);

        $table->add_index("idx_time", XMLDB_INDEX_NOTUNIQUE, ["timecreated"]);
        $table->add_index("idx_component", XMLDB_INDEX_NOTUNIQUE, ["component"]);
        $table->add_index("idx_user", XMLDB_INDEX_NOTUNIQUE, ["userid"]);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2026051002, "local", "kopere_dashboard");
    }

    return true;
}
