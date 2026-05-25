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
 * permission.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_requests\service;

use coding_exception;
use context_course;
use context_system;
use dml_exception;
use stdClass;

/**
 * Class permission
 */
class permission {
    /**
     * Function can_view_request
     *
     * @param stdClass $request
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function can_view_request(stdClass $request): bool {
        global $USER;

        if ($request->userid == $USER->id) {
            return true;
        }

        $context = context_system::instance();
        if (has_capability("koperedashboard/requests:manage", $context)) {
            return true;
        }

        return self::can_respond_request($request);
    }

    /**
     * Function can_respond_request
     *
     * @param stdClass $request
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function can_respond_request(stdClass $request): bool {
        $context = context_system::instance();

        if (has_capability("koperedashboard/requests:manage", $context)) {
            return true;
        }

        $category = self::get_category($request->categoryid);
        if (!$category || empty($category->active)) {
            return false;
        }

        if (!empty($category->allowteacher) && !empty($request->courseid)) {
            $coursecontext = context_course::instance($request->courseid);

            // Teacher heuristic: editing teachers/managers in the course.
            if (has_capability("moodle/course:update", $coursecontext) ||
                has_capability("moodle/course:manageactivities", $coursecontext)) {
                return true;
            }
        }

        $caps = self::parse_caps($category->responsiblecaps);
        foreach ($caps as $cap) {
            if (has_capability($cap, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Function parse_caps
     *
     * @param string $raw
     * @return array
     */
    public static function parse_caps(string $raw): array {
        $raw = trim($raw);
        if ($raw == "") {
            return [];
        }

        $lines = preg_split("/\r\n|\r|\n/", $raw);
        $caps = [];
        foreach ($lines as $line) {
            $cap = trim($line);
            if ($cap != "") {
                $caps[] = $cap;
            }
        }
        return array_values(array_unique($caps));
    }

    /**
     * Function get_category
     *
     * @param int $id
     * @return stdClass|null
     * @throws dml_exception
     */
    private static function get_category(int $id): ?stdClass {
        global $DB;
        if ($id <= 0) {
            return null;
        }
        return $DB->get_record("koperedashboard_req_category", ["id" => $id]);
    }
}
