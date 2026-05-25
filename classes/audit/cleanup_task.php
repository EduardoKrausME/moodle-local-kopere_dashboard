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
 * cleanup_task.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\audit;

use coding_exception;
use core\task\scheduled_task;
use dml_exception;

/**
 * Class cleanup_task
 */
class cleanup_task extends scheduled_task {
    /**
     * Function get_name
     *
     * @return string
     * @throws coding_exception
     */
    public function get_name(): string {
        return get_string("audit", "local_kopere_dashboard") . ": cleanup";
    }

    /**
     * Function execute
     *
     * @return void
     * @throws dml_exception
     */
    public function execute(): void {
        global $DB;

        $days = get_config("local_kopere_dashboard", "auditretentiondays");
        if ($days <= 0) {
            return;
        }

        $threshold = time() - ($days * DAYSECS);
        $DB->delete_records_select("local_kopere_dashboard_audit", "timecreated < :t", ["t" => $threshold]);
    }
}
