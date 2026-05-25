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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest\audit;

use coding_exception;
use context_course;
use context_system;
use dml_exception;
use local_kopere_dashboard\audit\manager;

/**
 * Class logger
 */
class logger {
    /**
     * Function template_created
     *
     * @param int $tplid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function template_created(int $tplid): void {
        manager::log(
            "koperedashboard_attest",
            "create",
            "koperedashboard_attest_tpl",
            $tplid,
            get_string("audit_tpl_create", "koperedashboard_attest"),
            context_system::instance()->id,
            ["tplid" => $tplid]
        );
    }

    /**
     * Function template_updated
     *
     * @param int $tplid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function template_updated(int $tplid): void {
        manager::log(
            "koperedashboard_attest",
            "update",
            "koperedashboard_attest_tpl",
            $tplid,
            get_string("audit_tpl_update", "koperedashboard_attest"),
            context_system::instance()->id,
            ["tplid" => $tplid]
        );
    }

    /**
     * Function template_deleted
     *
     * @param int $tplid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function template_deleted(int $tplid): void {
        manager::log(
            "koperedashboard_attest",
            "delete",
            "koperedashboard_attest_tpl",
            $tplid,
            get_string("audit_tpl_delete", "koperedashboard_attest"),
            context_system::instance()->id,
            ["tplid" => $tplid]
        );
    }

    /**
     * Function issue_created
     *
     * @param int $issueid
     * @param int $tplid
     * @param int $courseid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function issue_created(int $issueid, int $tplid, int $courseid): void {
        manager::log(
            "koperedashboard_attest",
            "issue",
            "koperedashboard_attest_issue",
            $issueid,
            get_string("audit_issue_create", "koperedashboard_attest"),
            context_course::instance($courseid)->id,
            ["issueid" => $issueid, "tplid" => $tplid, "courseid" => $courseid]
        );
    }
}
