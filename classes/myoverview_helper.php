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
 * myoverview_helper.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

/**
 * Helper to respect Moodle My Overview hidden courses.
 */
class myoverview_helper {
    /**
     * Returns hidden course ids from block_myoverview preferences.
     *
     * @param int $userid
     * @return int[]
     * @throws \dml_exception
     */
    public static function get_hidden_course_ids(int $userid): array {
        global $DB;

        $params = [
            "userid" => $userid,
            "namepattern" => "block_myoverview_hidden_course_%",
        ];
        $prefs = $DB->get_records_select(
            "user_preferences", "userid = :userid AND name LIKE :namepattern", $params, "", "id, name, value"
        );

        $hiddencourseids = [];
        foreach ($prefs as $pref) {
            if (empty($pref->value)) {
                continue;
            }

            if (preg_match("/^block_myoverview_hidden_course_(\\d+)$/", $pref->name, $matches)) {
                $hiddencourseids[(int)$matches[1]] = (int)$matches[1];
            }
        }

        return $hiddencourseids;
    }

    /**
     * Removes My Overview hidden courses from a course list.
     *
     * @param array $courses
     * @param int $userid
     * @return array
     * @throws \dml_exception
     */
    public static function remove_hidden_courses(array $courses, int $userid): array {
        $hiddencourseids = self::get_hidden_course_ids($userid);

        if (!$hiddencourseids) {
            return $courses;
        }

        $courses = array_filter($courses, static function($course): bool {
            return !empty($course->visible);
        });

        return array_filter($courses, static function($course) use ($hiddencourseids): bool {
            return empty($hiddencourseids[$course->id]);
        });
    }
}
