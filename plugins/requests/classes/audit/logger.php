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
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_requests\audit;

use coding_exception;
use context_system;
use dml_exception;
use local_kopere_dashboard\audit\manager;

/**
 * Class logger
 */
class logger {
    /**
     * Function category_created
     *
     * @param int $categoryid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function category_created(int $categoryid): void {
        manager::log(
            "koperedashboard_requests",
            "category_create",
            "koperedashboard_req_category",
            $categoryid,
            get_string("audit_category_create", "koperedashboard_requests"),
            context_system::instance()->id,
            ["categoryid" => $categoryid]
        );
    }

    /**
     * Function category_updated
     *
     * @param int $categoryid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function category_updated(int $categoryid): void {
        manager::log(
            "koperedashboard_requests",
            "category_update",
            "koperedashboard_req_category",
            $categoryid,
            get_string("audit_category_update", "koperedashboard_requests"),
            context_system::instance()->id,
            ["categoryid" => $categoryid]
        );
    }

    /**
     * Function request_created
     *
     * @param int $requestid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function request_created(int $requestid): void {
        manager::log(
            "koperedashboard_requests",
            "request_create",
            "koperedashboard_req_request",
            $requestid,
            get_string("audit_request_create", "koperedashboard_requests"),
            context_system::instance()->id,
            ["requestid" => $requestid]
        );
    }

    /**
     * Function request_replied
     *
     * @param int $requestid
     * @param bool $isstaff
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function request_replied(int $requestid, bool $isstaff): void {
        manager::log(
            "koperedashboard_requests",
            "request_reply",
            "koperedashboard_req_message",
            $requestid,
            get_string("audit_request_reply", "koperedashboard_requests"),
            context_system::instance()->id,
            ["requestid" => $requestid, "isstaff" => $isstaff ? 1 : 0]
        );
    }

    /**
     * Function request_closed
     *
     * @param int $requestid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function request_closed(int $requestid): void {
        manager::log(
            "koperedashboard_requests",
            "request_close",
            "koperedashboard_req_request",
            $requestid,
            get_string("audit_request_close", "koperedashboard_requests"),
            context_system::instance()->id,
            ["requestid" => $requestid]
        );
    }

    /**
     * Function request_reopened
     *
     * @param int $requestid
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function request_reopened(int $requestid): void {
        manager::log(
            "koperedashboard_requests",
            "request_reopen",
            "koperedashboard_req_request",
            $requestid,
            get_string("audit_request_reopen", "koperedashboard_requests"),
            context_system::instance()->id,
            ["requestid" => $requestid]
        );
    }
}
