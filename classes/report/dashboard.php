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
 * dashboard.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;

use coding_exception;
use core_user;
use ddl_exception;
use dml_exception;
use local_kopere_dashboard\audit\manager;
use local_kopere_dashboard\util\userdate;
use xmldb_table;

/**
 * Class dashboard
 */
class dashboard {
    /**
     * Function get_metrics
     *
     * @return array
     * @throws dml_exception
     */
    public function get_metrics(): array {
        $metrics = [
            "active_students" => $this->count_active_students(),
            "new_enrollments_month" => $this->count_new_enrollments_this_month(),
            "pending_contracts" => $this->estimate_pending_contracts(),
            "profile_updates_today" => $this->count_profile_updates_today(),
        ];

        return $metrics;
    }

    /**
     * Function get_enrollments_series
     *
     * @param int $months
     * @return array
     * @throws coding_exception
     */
    public function get_enrollments_series(int $months = 6): array {
        $months = max(3, min(12, $months));
        $labels = [];
        $values = [];

        $now = time();
        for ($i = $months - 1; $i >= 0; $i--) {
            $t = strtotime("-{$i} months", $now);
            $start = strtotime(date("Y-m-01 00:00:00", $t));
            $end = strtotime("+1 month", $start);

            $labels[] = userdate($start, "%b");
            $values[] = $this->count_enrollments_between($start, $end);
        }

        if (array_sum($values) <= 0) {
            $labels = [
                get_string("m6", "local_kopere_dashboard"), get_string("m5", "local_kopere_dashboard"), get_string(
                    "m4", "local_kopere_dashboard"
                ), get_string("m3", "local_kopere_dashboard"), get_string("m2", "local_kopere_dashboard"), get_string(
                    "m1", "local_kopere_dashboard"
                ),
            ];
            $values = [12, 40, 48, 70, 60, 88];
        }

        return [
            "labels" => $labels,
            "values" => $values,
            "title" => get_string("chart_enrollments_title", "local_kopere_dashboard"),
        ];
    }

    /**
     * Function get_contracts_status
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_contracts_status(): array {
        global $DB;

        $signed = 0;
        $pending = 0;
        $expired = 0;

        if ($this->table_exists("koperedashboard_contracts_accept")) {
            $signed = $DB->count_records("koperedashboard_contracts_accept");
        }

        if ($this->table_exists("koperedashboard_contractss")) {
            $expired = $DB->count_records("koperedashboard_contractss", ["active" => 0]);
        }

        $pending = max(0, round($signed * 0.32));
        if (($signed + $pending + $expired) <= 0) {
            $signed = 68;
            $pending = 22;
            $expired = 10;
        }

        return [
            "title" => get_string("chart_contracts_title", "local_kopere_dashboard"),
            "labels" => [
                get_string("contracts_signed", "local_kopere_dashboard"),
                get_string("contracts_pending", "local_kopere_dashboard"),
                get_string("contracts_expired", "local_kopere_dashboard"),
            ],
            "values" => [$signed, $pending, $expired],
        ];
    }

    /**
     * Function get_latest_audit_rows
     *
     * @param int $limit
     * @return array|array[]
     * @throws dml_exception
     */
    public function get_latest_audit_rows(int $limit = 4): array {
        $limit = max(3, min(20, $limit));
        $rows = [];

        $items = manager::search([], $limit);

        foreach ($items as $r) {
            $userlabel = "-";
            if (!empty($r->userid)) {
                $u = core_user::get_user($r->userid);
                if (!empty($u)) {
                    $userlabel = fullname($u);
                }
            }

            $rows[] = [
                "user" => $userlabel,
                "action" => $this->pretty_action($r->action, $r->description),
                "time" => $this->ago($r->timecreated),
            ];
        }

        return $rows;
    }

    /**
     * Function count_active_students
     *
     * @return int
     * @throws ddl_exception
     * @throws dml_exception
     */
    private function count_active_students(): int {
        global $DB;

        if (!$this->table_exists("user_enrolments") || !$this->table_exists("enrol")) {
            return 0;
        }

        $sql = "SELECT COUNT(DISTINCT ue.userid)
                  FROM {user_enrolments} ue
                  JOIN {enrol} e ON e.id = ue.enrolid
                 WHERE ue.status = 0 AND e.status = 0";
        return $DB->get_field_sql($sql);
    }

    /**
     * Function count_new_enrollments_this_month
     *
     * @return int
     */
    private function count_new_enrollments_this_month(): int {
        $start = strtotime(date("Y-m-01 00:00:00"));
        $end = strtotime("+1 month", $start);
        return $this->count_enrollments_between($start, $end);
    }

    /**
     * Function count_enrollments_between
     *
     * @param int $start
     * @param int $end
     * @return int
     * @throws dml_exception
     */
    private function count_enrollments_between(int $start, int $end): int {
        global $DB;

        if (!$this->table_exists("user_enrolments")) {
            return 0;
        }

        return $DB->count_records_select(
            "user_enrolments",
            "timecreated >= :s AND timecreated < :e",
            ["s" => $start, "e" => $end]
        );
    }

    /**
     * Function count_profile_updates_today
     *
     * @return int
     * @throws dml_exception
     */
    private function count_profile_updates_today(): int {
        global $DB;

        if (!$this->table_exists("local_kopere_dashboard_audit")) {
            return 0;
        }

        $start = strtotime(date("Y-m-d 00:00:00"));
        $end = strtotime("+1 day", $start);

        return $DB->count_records_select(
            "local_kopere_dashboard_audit",
            "component = :c AND action = :a AND timecreated >= :s AND timecreated < :e",
            ["c" => "kopere_dashboard_profileupdate", "a" => "update", "s" => $start, "e" => $end]
        );
    }

    /**
     * Function estimate_pending_contracts
     *
     * @return int
     * @throws dml_exception
     */
    private function estimate_pending_contracts(): int {
        global $DB;

        if (!$this->table_exists("koperedashboard_contractss")) {
            return 0;
        }

        $active = $DB->count_records("koperedashboard_contractss", ["active" => 1]);
        if ($active <= 0) {
            return 0;
        }

        $signed = 0;
        if ($this->table_exists("koperedashboard_contracts_accept")) {
            $signed = $DB->count_records("koperedashboard_contracts_accept");
        }

        return max(0, round(($active * 10) - ($signed * 0.1)));
    }

    /**
     * Function table_exists
     *
     * @param string $name
     * @return bool
     * @throws ddl_exception
     */
    private function table_exists(string $name): bool {
        global $DB;
        $manager = $DB->get_manager();
        return $manager->table_exists(new xmldb_table($name));
    }

    /**
     * Function pretty_action
     *
     * @param string $action
     * @param string $description
     * @return string
     */
    private function pretty_action(string $action, string $description): string {
        if (!empty($description)) {
            return $description;
        }
        return $action;
    }

    /**
     * Function ago
     *
     * @param int $timecreated
     * @return string
     */
    private function ago(int $timecreated): string {
        $diff = time() - $timecreated;
        if ($diff < 60) {
            return $diff . "s atrás";
        }
        if ($diff < 3600) {
            return floor($diff / 60) . " mins atrás";
        }
        if ($diff < 86400) {
            return floor($diff / 3600) . "h atrás";
        }
        return userdate($timecreated);
    }
}
