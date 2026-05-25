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
 * gate.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_contracts\contracts;

use coding_exception;
use context_system;
use dml_exception;
use moodle_exception;
use moodle_url;

/**
 * Class gate
 */
class gate {
    /**
     * Function enforce_course_access
     *
     * @param int $courseid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     * @throws \core\exception\moodle_exception
     */
    public static function enforce_course_access(int $courseid): void {
        global $USER, $FULLME;

        if (empty($USER) || empty($USER->id)) {
            return;
        }

        $sys = context_system::instance();

        // Teachers/managers can bypass if they have contract management capability.
        if (has_capability("koperedashboard/contracts:manage", $sys)) {
            return;
        }

        if (manager::is_course_cached_as_accepted($courseid)) {
            return;
        }

        $contracts = manager::get_active_contracts_for_course($courseid);
        if (empty($contracts)) {
            manager::mark_course_as_accepted_in_session($courseid);
            return;
        }

        foreach ($contracts as $c) {
            if (manager::is_contract_cached_as_accepted($c->id, $courseid)) {
                continue;
            }

            if (!manager::user_has_accepted($c->id, $courseid, $USER->id)) {
                $returnurl = new moodle_url($FULLME);
                $accepturl = new moodle_url("/local/kopere_dashboard/plugins/contracts/accept.php", [
                    "contractid" => $c->id,
                    "courseid" => $courseid,
                    "return" => $returnurl->out(false),
                ]);
                redirect($accepturl);
            }
        }

        manager::mark_course_as_accepted_in_session($courseid);
    }
}
