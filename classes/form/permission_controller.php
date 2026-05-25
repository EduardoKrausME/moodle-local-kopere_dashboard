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
 * permission_controller.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\form;

use coding_exception;
use context_system;
use dml_exception;
use Exception;
use local_kopere_dashboard\audit\manager as audit_manager;
use local_kopere_dashboard\permission\role_manager;
use moodle_url;

/**
 * Class permission_controller
 */
class permission_controller {

    /**
     * Manage a single capability: show selectors + process add/remove.
     *
     * @param string $capability
     * @param array $definition
     * @return array
     * @throws Exception
     */
    public function manage_capability(string $capability, array $definition): array {
        $syscontext = context_system::instance();
        $roleid = role_manager::get_or_create_roleid($capability, $definition);

        $form = new permission_form(null, [
            "capability" => $capability,
            "definition" => $definition,
            "roleid" => $roleid,
            "contextid" => $syscontext->id,
        ]);

        if ($data = $form->get_data()) {
            if ($this->process_users($roleid, $capability)) {
                redirect(new moodle_url("/local/kopere_dashboard/permissions.php", [
                    "actionform" => "edit",
                    "capability" => $capability,
                ]));
            }
        } else {
            $form->set_data([
                "actionform" => "edit",
                "capability" => $capability,
            ]);
        }

        $formhtml = $form->render();

        return [
            "backurl" => new moodle_url("/local/kopere_dashboard/permissions.php"),
            "capability" => $capability,
            "name" => !empty($definition["name"]) ? format_string($definition["name"]) : $capability,
            "description" => !empty($definition["description"]) ? format_text($definition["description"], FORMAT_PLAIN) : "",
            "formhtml" => $formhtml,
        ];
    }

    /**
     * Process add/remove requests coming from selectors.
     *
     * @param int $roleid
     * @param string $capability
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    private function process_users(int $roleid, string $capability): bool {
        global $DB, $USER;

        $syscontextid = context_system::instance()->id;

        // Process incoming user to the capability role.
        if (optional_param("add", false, PARAM_BOOL) && confirm_sesskey()) {
            $users = optional_param_array("addselect", [], PARAM_INT);

            foreach ($users as $userid) {
                $exists = $DB->record_exists("role_assignments", [
                    "roleid" => $roleid,
                    "userid" => $userid,
                    "contextid" => $syscontextid,
                ]);

                if ($exists) {
                    continue;
                }

                $record = (object) [
                    "roleid" => $roleid,
                    "userid" => $userid,
                    "contextid" => $syscontextid,
                    "timemodified" => time(),
                    "modifierid" => $USER->id,
                    "component" => "",
                    "itemid" => 0,
                    "sortorder" => 0,
                ];

                $DB->insert_record("role_assignments", $record);

                audit_manager::log(
                    "local_kopere_dashboard",
                    "permission_add",
                    "role_assignments",
                    $roleid,
                    "User assigned to capability: {$capability}",
                    $syscontextid,
                    ["capability" => $capability, "roleid" => $roleid, "userid" => $userid]
                );
            }

            return true;
        }

        // Process removing user from the capability role.
        if (optional_param("remove", false, PARAM_BOOL) && confirm_sesskey()) {
            $users = optional_param_array("removeselect", [], PARAM_INT);

            foreach ($users as $userid) {
                $DB->delete_records("role_assignments", [
                    "roleid" => $roleid,
                    "userid" => $userid,
                    "contextid" => $syscontextid,
                ]);

                audit_manager::log(
                    "local_kopere_dashboard",
                    "permission_remove",
                    "role_assignments",
                    $roleid,
                    "User removed from capability: {$capability}",
                    $syscontextid,
                    ["capability" => $capability, "roleid" => $roleid, "userid" => $userid]
                );
            }

            return true;
        }

        return false;
    }
}
