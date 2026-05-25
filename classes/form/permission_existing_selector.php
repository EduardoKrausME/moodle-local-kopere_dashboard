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
 * permission_existing_selector.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\form;

use coding_exception;
use dml_exception;
use user_selector_base;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once("{$CFG->dirroot}/user/selector/lib.php");

/**
 * Class permission_existing_selector
 */
class permission_existing_selector extends user_selector_base {

    /** @var int */
    protected $roleid;

    /** @var int */
    protected $contextid;

    /**
     * __construct
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, $options) {
        $this->roleid = $options["roleid"];
        $this->contextid = $options["contextid"];
        $options["includecustomfields"] = true;

        parent::__construct($name, $options);
    }

    /**
     * Existing users (already assigned to the role).
     *
     * @param string $search
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function find_users($search): array {
        global $DB;

        [$wherecondition, $params] = $this->search_sql($search, "u");
        $params = array_merge($params, $this->userfieldsparams);

        $params["roleid"] = $this->roleid;
        $params["contextid"] = $this->contextid;

        $fields = "SELECT u.id, {$this->userfieldsselects}";
        $countfields = "SELECT COUNT(1)";

        $sql = " FROM {user} u
                 JOIN {role_assignments} ra
                   ON (ra.userid = u.id AND ra.roleid = :roleid AND ra.contextid = :contextid)
                 {$this->userfieldsjoin}
                WHERE {$wherecondition}";

        [$sort, $sortparams] = users_order_by_sql("u", $search, $this->accesscontext, $this->userfieldsmappings);
        $order = " ORDER BY {$sort}";

        if (!$this->is_validating()) {
            $currentcount = $DB->count_records_sql($countfields . $sql, $params);
            if ($currentcount > $this->maxusersperpage) {
                return $this->too_many_results($search, $currentcount);
            }
        }

        $assignedusers = $DB->get_records_sql($fields . $sql . $order, array_merge($params, $sortparams));
        if (empty($assignedusers)) {
            return [];
        }

        $groupname = $search ? get_string("currentusersmatching", "cohort", $search) : get_string("currentusers", "cohort");
        return [$groupname => $assignedusers];
    }

    /**
     * get_options
     *
     * @return array
     */
    protected function get_options(): array {
        $options = parent::get_options();
        $options["roleid"] = $this->roleid;
        $options["contextid"] = $this->contextid;
        return $options;
    }
}
