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
 * student_dashboard.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;

use context_course;
use core_completion\progress;
use core_course_category;
use local_kopere_dashboard\myoverview_helper;
use local_kopere_dashboard\util\userdate;
use moodle_url;
use stdClass;
use Throwable;

/**
 * Class student_dashboard
 */
class student_dashboard {
    /**
     * Build the student dashboard template context.
     *
     * @param int $userid
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \dml_exception
     */
    public function export_for_template(int $userid): array {
        global $USER;

        $courses = $this->get_student_courses($userid);
        $metrics = $this->build_metrics($courses);

        return [
            "student_name" => fullname($USER),
            "kpi_enrolled_courses" => $metrics["enrolled"],
            "kpi_completed_courses" => $metrics["completed"],
            "kpi_in_progress_courses" => $metrics["inprogress"],
            "kpi_tracked_courses" => $metrics["tracked"],
            "hascourses" => !empty($courses),
            "courses" => array_slice($courses, 0, 6),
            "quicklinks" => [
                [
                    "title" => get_string("student_quicklink_mycourses", "local_kopere_dashboard"),
                    "description" => get_string("student_quicklink_mycourses_desc", "local_kopere_dashboard"),
                    "url" => new moodle_url("/my/courses.php"),
                ],
                [
                    "title" => get_string("student_quicklink_calendar", "local_kopere_dashboard"),
                    "description" => get_string("student_quicklink_calendar_desc", "local_kopere_dashboard"),
                    "url" => new moodle_url("/calendar/view.php", ["view" => "month"]),
                ],
                [
                    "title" => get_string("student_quicklink_profile", "local_kopere_dashboard"),
                    "description" => get_string("student_quicklink_profile_desc", "local_kopere_dashboard"),
                    "url" => new moodle_url("/user/profile.php", ["id" => $userid]),
                ],
            ],
        ];
    }

    /**
     * Load the student's enrolled courses with completion and access summaries.
     *
     * @param int $userid
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \dml_exception
     */
    private function get_student_courses(int $userid): array {
        $courses = enrol_get_all_users_courses(
            $userid,
            true,
            "id, fullname, shortname, category, visible, startdate, enddate"
        );
        $courses = myoverview_helper::remove_hidden_courses($courses, $userid);

        if (empty($courses)) {
            return [];
        }

        $courses = array_filter($courses, static function($course): bool {
            return $course->id != SITEID;
        });

        if (empty($courses)) {
            return [];
        }

        $courseids = array_map(static function($course): int {
            return $course->id;
        }, $courses);

        $completionmap = $this->get_completion_map($userid, $courseids);
        $lastaccessmap = $this->get_last_access_map($userid, $courseids);
        $categorymap = $this->get_category_map($courses);

        $result = [];
        foreach ($courses as $course) {
            $courseid = $course->id;
            $coursecontext = context_course::instance($courseid);
            $progress = $this->get_course_progress($course);
            $lastaccess = $lastaccessmap[$courseid] ?? 0;
            $timecompleted = $completionmap[$courseid] ?? 0;

            $statuskey = "student_status_notstarted";
            if ($timecompleted > 0) {
                $statuskey = "student_status_completed";
            } else if (($progress != null && $progress > 0) || $lastaccess > 0) {
                $statuskey = "student_status_inprogress";
            }

            $result[] = [
                "fullname" => format_string($course->fullname, true, ["context" => $coursecontext]),
                "shortname" => format_string($course->shortname, true, ["context" => $coursecontext]),
                "categoryname" => $categorymap[$course->category] ?? "",
                "courseurl" => new moodle_url("/course/view.php", ["id" => $courseid]),
                "progress" => $progress == null ? 0 : round($progress),
                "hasprogress" => $progress != null,
                "progresslabel" => $progress == null
                    ? get_string("student_progress_not_available", "local_kopere_dashboard")
                    : round($progress) . "%",
                "statuskey" => $statuskey,
                "statuslabel" => get_string($statuskey, "local_kopere_dashboard"),
                "lastaccess" => $lastaccess > 0
                    ? userdate::format($lastaccess)
                    : get_string("student_never_accessed", "local_kopere_dashboard"),
            ];
        }

        usort($result, static function(array $a, array $b): int {
            return strcasecmp($a["fullname"], $b["fullname"]);
        });

        return $result;
    }

    /**
     * Build KPI totals from the prepared course list.
     *
     * @param array $courses
     * @return array
     */
    private function build_metrics(array $courses): array {
        $metrics = [
            "enrolled" => count($courses),
            "completed" => 0,
            "inprogress" => 0,
            "tracked" => 0,
        ];

        foreach ($courses as $course) {
            if (($course["statuskey"] ?? "") == "student_status_completed") {
                $metrics["completed"]++;
            }

            if (($course["statuskey"] ?? "") == "student_status_inprogress") {
                $metrics["inprogress"]++;
            }

            if (!empty($course["hasprogress"])) {
                $metrics["tracked"]++;
            }
        }

        return $metrics;
    }

    /**
     * Load completion timestamps indexed by course id.
     *
     * @param int $userid
     * @param int[] $courseids
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private function get_completion_map(int $userid, array $courseids): array {
        global $DB;

        [$insql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, "courseid");
        $params["userid"] = $userid;

        return $DB->get_records_sql_menu(
            "SELECT course, timecompleted
               FROM {course_completions}
              WHERE userid = :userid
                AND course {$insql}",
            $params
        );
    }

    /**
     * Load last access timestamps indexed by course id.
     *
     * @param int $userid
     * @param int[] $courseids
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private function get_last_access_map(int $userid, array $courseids): array {
        global $DB;

        [$insql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, "accesscourseid");
        $params["userid"] = $userid;

        return $DB->get_records_sql_menu(
            "SELECT courseid, timeaccess
               FROM {user_lastaccess}
              WHERE userid = :userid
                AND courseid {$insql}",
            $params
        );
    }

    /**
     * Load course category names indexed by category id.
     *
     * @param array $courses
     * @return array
     */
    private function get_category_map(array $courses): array {
        $categoryids = [];
        foreach ($courses as $course) {
            if (!empty($course->category)) {
                $categoryids[$course->category] = $course->category;
            }
        }

        if (empty($categoryids)) {
            return [];
        }

        $result = [];
        foreach ($categoryids as $categoryid) {
            try {
                $category = core_course_category::get($categoryid, IGNORE_MISSING, true);
                if (!empty($category)) {
                    $result[$categoryid] = $category->get_formatted_name();
                }
            } catch (Throwable) {
                continue;
            }
        }

        return $result;
    }

    /**
     * Read the percentage progress when course completion is enabled.
     *
     * @param \stdClass $course
     * @return float|null
     */
    private function get_course_progress(stdClass $course): ?float {
        if (!class_exists("\\core_completion\\progress")) {
            return null;
        }

        $progress = progress::get_course_progress_percentage($course);
        if ($progress == null) {
            return null;
        }

        return $progress;
    }
}
