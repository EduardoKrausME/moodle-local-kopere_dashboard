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
 * report_service.php
 *
 * @package   koperedashboard_reportcard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_reportcard\service;

use context_course;
use core\exception\moodle_exception;
use grade_grade;
use grade_item;
use grade_scale;
use local_kopere_dashboard\myoverview_helper;
use moodle_url;
use stdClass;

/**
 * Class report_service
 */
class report_service {
    /**
     * Return the current user's visible and not hidden courses.
     *
     * @param int $userid
     * @return array
     * @throws \dml_exception
     */
    public static function get_available_courses(int $userid): array {
        $courses = enrol_get_users_courses($userid, true, "id, fullname, shortname, visible");
        $courses = array_filter($courses, static function(stdClass $course): bool {
            return !empty($course->visible);
        });

        $courses = myoverview_helper::remove_hidden_courses($courses, $userid);
        uasort($courses, static function(stdClass $a, stdClass $b): int {
            return strcmp($a->fullname, $b->fullname);
        });

        return $courses;
    }

    /**
     * Checks if the user has at least one course available.
     *
     * @param int $userid
     * @return bool
     * @throws \dml_exception
     */
    public static function has_available_courses(int $userid): bool {
        return !empty(self::get_available_courses($userid));
    }

    /**
     * Return one course from the user's visible list.
     *
     * @param int $userid
     * @param int $courseid
     * @return stdClass
     * @throws moodle_exception
     * @throws \dml_exception
     */
    public static function get_user_course(int $userid, int $courseid): stdClass {
        $courses = self::get_available_courses($userid);
        if (empty($courses[$courseid])) {
            throw new moodle_exception("invalidcourseid");
        }

        return $courses[$courseid];
    }

    /**
     * Export the course selector data to Mustache.
     *
     * @param int $userid
     * @param int|null $selectedcourseid
     * @return array
     * @throws \dml_exception
     */
    public static function export_selector(int $userid, ?int $selectedcourseid = null): array {
        $courses = self::get_available_courses($userid);
        $rows = [];

        foreach ($courses as $course) {
            $rows[] = [
                "id" => $course->id,
                "fullname" => format_string($course->fullname),
                "selected" => !empty($selectedcourseid) && $selectedcourseid == $course->id,
            ];
        }

        return [
            "hascourses" => !empty($rows),
            "courses" => array_values($rows),
        ];
    }

    /**
     * Export the whole report card for one course.
     *
     * @param int $userid
     * @param stdClass $course
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    public static function export_course_report(int $userid, stdClass $course): array {
        global $CFG;

        require_once($CFG->libdir . "/gradelib.php");
        require_once($CFG->dirroot . "/lib/grade/grade_item.php");
        require_once($CFG->dirroot . "/lib/grade/grade_grade.php");
        require_once($CFG->dirroot . "/lib/grade/grade_scale.php");

        $context = context_course::instance($course->id);
        if (!is_enrolled($context, $userid, "", true)) {
            throw new moodle_exception("nopermissions", "error", "", "moodle/course:view");
        }

        $gradeitems = grade_item::fetch_all(["courseid" => $course->id]) ?: [];
        usort($gradeitems, static function(grade_item $a, grade_item $b): int {
            return ($a->sortorder != $b->sortorder);
        });

        $rows = [];
        $finalgrade = [
            "value" => get_string("notgraded", "koperedashboard_reportcard"),
            "range" => self::format_normalized_grade_range(),
        ];

        $normalizedgrades = [];
        foreach ($gradeitems as $gradeitem) {
            $gradegrade = grade_grade::fetch([
                "itemid" => $gradeitem->id,
                "userid" => $userid,
            ]);

            if (!empty($gradegrade) && method_exists($gradegrade, "is_hidden") && $gradegrade->is_hidden()) {
                continue;
            }

            if ($gradeitem->itemtype === "course") {
                continue;
            }

            if ($gradeitem->itemtype === "category") {
                continue;
            }

            if (!empty($gradeitem->hidden)) {
                continue;
            }

            $rows[] = [
                "itemname" => self::resolve_item_name($gradeitem),
                "itemurl" => self::resolve_item_url($gradeitem, $course->id),
                "itemtype" => self::resolve_item_type($gradeitem),
                "gradevalue" => self::export_grade_value($gradeitem, $gradegrade)["value"],
                "graderange" => self::format_grade_range($gradeitem),
            ];

            $normalizedgrade = self::get_normalized_grade_value($gradeitem, $gradegrade);
            if ($normalizedgrade !== null) {
                $normalizedgrades[] = $normalizedgrade;
            }
        }

        if (!empty($normalizedgrades)) {
            $finalgrade["value"] = self::format_normalized_grade_value(
                array_sum($normalizedgrades) / count($normalizedgrades)
            );
        }

        return [
            "coursename" => format_string($course->fullname),
            "hasgrades" => !empty($rows),
            "grades" => $rows,
            "finalgrade" => $finalgrade["value"],
            "hasfinalrange" => !empty($finalgrade["range"]),
            "finalgraderange" => $finalgrade["range"],
        ];
    }

    /**
     * Return the normalized grade value in a 0-100 scale.
     *
     * @param grade_item $gradeitem
     * @param grade_grade|false $gradegrade
     * @return float|null
     */
    protected static function get_normalized_grade_value(grade_item $gradeitem, $gradegrade): ?float {
        if (empty($gradegrade) || $gradegrade->finalgrade === null) {
            return null;
        }

        if ($gradeitem->gradetype == GRADE_TYPE_TEXT || $gradeitem->gradetype == GRADE_TYPE_NONE) {
            return null;
        }

        $range = (float) $gradeitem->grademax - (float) $gradeitem->grademin;
        if ($range <= 0) {
            return null;
        }

        $normalizedgrade = (($gradegrade->finalgrade - (float) $gradeitem->grademin) / $range) * 100;
        return min(100.0, max(0.0, $normalizedgrade));
    }

    /**
     * Format the normalized final grade value.
     *
     * @param float $gradevalue
     * @return string
     */
    protected static function format_normalized_grade_value(float $gradevalue): string {
        return format_float($gradevalue, 2);
    }

    /**
     * Format the normalized final grade range.
     *
     * @return string
     */
    protected static function format_normalized_grade_range(): string {
        return format_float(0, 2) . " - " . format_float(100, 2);
    }

    /**
     * Resolve the activity URL for the grade item.
     *
     * @param grade_item $gradeitem
     * @param int $courseid
     * @return string|null
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    protected static function resolve_item_url(grade_item $gradeitem, int $courseid): ?string {
        if ($gradeitem->itemtype !== "mod" || empty($gradeitem->itemmodule) || empty($gradeitem->iteminstance)) {
            return null;
        }

        $cm = get_coursemodule_from_instance($gradeitem->itemmodule, $gradeitem->iteminstance, $courseid);

        if (empty($cm)) {
            return null;
        }

        return (new moodle_url("/mod/{$gradeitem->itemmodule}/view.php", ["id" => $cm->id]))->out(false);
    }

    /**
     * Return the grade value and range formatted for display.
     *
     * @param grade_item $gradeitem
     * @param grade_grade|false $gradegrade
     * @return array
     * @throws \coding_exception
     */
    protected static function export_grade_value(grade_item $gradeitem, $gradegrade): array {
        $value = get_string("notgraded", "koperedashboard_reportcard");
        if (!empty($gradegrade) && $gradegrade->finalgrade !== null) {
            $value = grade_format_gradevalue($gradegrade->finalgrade, $gradeitem);
        }

        return [
            "value" => $value,
            "range" => self::format_grade_range($gradeitem),
        ];
    }

    /**
     * Resolve the human name for the grade item.
     *
     * @param grade_item $gradeitem
     * @return string
     * @throws \coding_exception
     */
    protected static function resolve_item_name(grade_item $gradeitem): string {
        if (!empty($gradeitem->itemname)) {
            return format_string($gradeitem->itemname);
        }

        if ($gradeitem->itemtype === "mod" && !empty($gradeitem->itemmodule)) {
            $component = "mod_{$gradeitem->itemmodule}";
            if (get_string_manager()->string_exists("pluginname", $component)) {
                return get_string("pluginname", $component);
            }
        }

        return get_string("unnameditem", "koperedashboard_reportcard");
    }

    /**
     * Resolve the type label for the row.
     *
     * @param grade_item $gradeitem
     * @return string
     * @throws \coding_exception
     */
    protected static function resolve_item_type(grade_item $gradeitem): string {
        if ($gradeitem->itemtype === "mod" && !empty($gradeitem->itemmodule)) {
            $component = "mod_{$gradeitem->itemmodule}";
            if (get_string_manager()->string_exists("pluginname", $component)) {
                return get_string("pluginname", $component);
            }

            return ucfirst($gradeitem->itemmodule);
        }

        if ($gradeitem->itemtype === "manual") {
            return get_string("manualitem", "koperedashboard_reportcard");
        }

        return ucfirst($gradeitem->itemtype);
    }

    /**
     * Format the grade range. For scales, display the scale text.
     *
     * @param grade_item $gradeitem
     * @return string
     */
    protected static function format_grade_range(grade_item $gradeitem): string {
        if ($gradeitem->gradetype === GRADE_TYPE_SCALE && !empty($gradeitem->scaleid)) {
            $scale = grade_scale::fetch(["id" => $gradeitem->scaleid]);
            if (!empty($scale) && !empty($scale->scale)) {
                $items = array_map("trim", explode(",", $scale->scale));
                $items = array_filter($items, static function(string $item): bool {
                    return $item !== "";
                });

                return implode(" / ", $items);
            }
        }

        if ($gradeitem->gradetype == GRADE_TYPE_TEXT || $gradeitem->gradetype == GRADE_TYPE_NONE) {
            return "-";
        }

        $min = grade_format_gradevalue($gradeitem->grademin, $gradeitem);
        $max = grade_format_gradevalue($gradeitem->grademax, $gradeitem);

        return $min . " - " . $max;
    }
}
