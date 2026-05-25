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
 * logger.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_contracts\audit;

use context_course;
use context_system;
use dml_exception;
use local_kopere_dashboard\audit\manager;

/**
 * Class logger
 */
class logger {
    /**
     * Function contract_created
     *
     * @param int $contractid
     * @return void
     * @throws dml_exception
     */
    public static function contract_created(int $contractid): void {
        manager::log(
            "koperedashboard_contracts",
            "create",
            "koperedashboard_contracts",
            $contractid,
            "Contract created.",
            context_system::instance()->id,
            ["contractid" => $contractid]
        );
    }

    /**
     * Function contract_updated
     *
     * @param int $contractid
     * @return void
     * @throws dml_exception
     */
    public static function contract_updated(int $contractid): void {
        manager::log(
            "koperedashboard_contracts",
            "update",
            "koperedashboard_contracts",
            $contractid,
            "Contract updated.",
            context_system::instance()->id,
            ["contractid" => $contractid]
        );
    }

    /**
     * Function contract_deleted
     *
     * @param int $contractid
     * @return void
     * @throws dml_exception
     */
    public static function contract_deleted(int $contractid): void {
        manager::log(
            "koperedashboard_contracts",
            "delete",
            "koperedashboard_contracts",
            $contractid,
            "Contract deleted.",
            context_system::instance()->id,
            ["contractid" => $contractid]
        );
    }

    /**
     * Function contract_accepted
     *
     * @param int $contractid
     * @param int $courseid
     * @param int $readseconds
     * @return void
     */
    public static function contract_accepted(int $contractid, int $courseid, int $readseconds): void {
        manager::log(
            "koperedashboard_contracts",
            "accept",
            "koperedashboard_contract_accept",
            $contractid,
            "User accepted contract for course.",
            context_course::instance($courseid)->id,
            ["contractid" => $contractid, "courseid" => $courseid, "readseconds" => $readseconds]
        );
    }
}
