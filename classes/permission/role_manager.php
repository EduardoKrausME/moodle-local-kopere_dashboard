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
 * role_manager.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\permission;

use coding_exception;
use context_system;
use dml_exception;

/**
 * Class role_manager
 */
class role_manager {

    /**
     * Create (or reuse) a system role that grants exactly one capability.
     *
     * @param string $capability
     * @param array $definition
     * @return int
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function get_or_create_roleid(string $capability, array $definition): int {
        global $DB;

        $shortname = self::shortname_from_capability($capability);
        $role = $DB->get_record("role", ["shortname" => $shortname], "id,shortname");

        if (!$role) {
            $name = !empty($definition["name"]) ? $definition["name"] : $capability;
            $name = "Kopere Dashboard - {$name}";

            $description = "Auto-created by Kopere Dashboard to grant capability:\n{$capability}";

            $roleid = create_role($name, $shortname, $description);
        } else {
            $roleid = $role->id;
        }

        // Ensure capability is allowed at system context for this role.
        $syscontextid = context_system::instance()->id;
        assign_capability($capability, CAP_ALLOW, $roleid, $syscontextid, true);

        return $roleid;
    }

    /**
     * Function shortname_from_capability
     *
     * @param string $capability
     * @return string
     */
    private static function shortname_from_capability(string $capability): string {
        return "acad_" . substr(sha1($capability), 0, 12);
    }
}
