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
 * placeholders.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\service;

use context_course;
use core_course\customfield\course_handler;
use Exception;
use local_kopere_dashboard\api\subplugin_manager;
use local_kopere_dashboard\util\userdate;
use Throwable;

/**
 * Class placeholders
 */
class placeholders {
    /**
     * Return the global catalog plus catalogs provided by subplugins.
     *
     * @return array[]
     */
    public static function catalog(): array {
        $catalog = [
            [
                "group" => "Course",
                "items" => [
                    ["key" => "{{course_id}}", "desc" => "Course ID"],
                    ["key" => "{{course_fullname}}", "desc" => "Course full name"],
                    ["key" => "{{course_shortname}}", "desc" => "Course short name"],
                    ["key" => "{{{course_summary}}}", "desc" => "Course summary (HTML)"],
                    ["key" => "{{course_startdate}}", "desc" => "Course start date"],
                ],
            ],
            [
                "group" => "User",
                "items" => [
                    ["key" => "{{user_id}}", "desc" => "User ID"],
                    ["key" => "{{user_fullname}}", "desc" => "User full name"],
                    ["key" => "{{user_firstname}}", "desc" => "User first name"],
                    ["key" => "{{user_lastname}}", "desc" => "User last name"],
                    ["key" => "{{user_email}}", "desc" => "User email"],
                    ["key" => "{{user_username}}", "desc" => "User username"],

                    ["key" => "{{user_address}}", "desc" => ""],
                    ["key" => "{{user_city}}", "desc" => ""],
                    ["key" => "{{user_country}}", "desc" => ""],
                    ["key" => "{{user_phone1}}", "desc" => ""],
                    ["key" => "{{user_phone2}}", "desc" => ""],

                    ["key" => "{{user_idnumber}}", "desc" => "User idnumber"],
                ],
            ],
            [
                "group" => "Enrollment",
                "items" => [
                    ["key" => "{{enrol_timeenrolled}}", "desc" => "Enrollment time (first manual enrol record)"],
                    ["key" => "{{completion_timecompleted}}", "desc" => "Course completion time (if any)"],
                ],
            ],
            [
                "group" => "User profile fields (custom)",
                "items" => [
                    ["key" => "{{user_profile_<shortname>}}", "desc" => "Example: {{user_profile_cpf}}"],
                ],
            ],
            [
                "group" => "Course custom fields",
                "items" => [
                    ["key" => "{{course_customfield_<shortname>}}", "desc" => "Example: {{course_customfield_area}}"],
                ],
            ],
            [
                "group" => "Outros",
                "items" => [
                    ["key" => "{{full_date}}", "desc" => "Data agora por extenso"],
                ],
            ],
        ];

        foreach (self::get_plugin_placeholder_classes() as $classname) {
            if (!is_callable([$classname, "catalog"])) {
                continue;
            }

            try {
                $plugincatalog = $classname::catalog();
                if (is_array($plugincatalog)) {
                    foreach ($plugincatalog as $group) {
                        $catalog[] = $group;
                    }
                }
            } catch (Throwable $exception) {
                continue;
            }
        }

        return $catalog;
    }

    /**
     * Build placeholder data using global values and subplugin contributions.
     *
     * @param int $courseid
     * @param array $enroldata
     * @param array $custom
     * @return array
     * @throws Exception
     */
    public static function build_data(int $courseid): array {
        global $CFG, $DB, $USER;

        $course = get_course($courseid);
        $coursecontext = context_course::instance($courseid);

        $enroldata = [
            "timeenrolled" => "",
            "timecompleted" => "",
        ];

        $sql = "
            SELECT ue.timecreated
              FROM {user_enrolments} ue
              JOIN {enrol} e ON e.id = ue.enrolid
             WHERE ue.userid  = :userid
               AND e.courseid = :courseid
          ORDER BY ue.timecreated ASC";
        $first = $DB->get_records_sql($sql, [
            "userid" => $USER->id,
            "courseid" => $courseid,
        ], 0, 1);

        if ($first) {
            $record = reset($first);
            $enroldata["timeenrolled"] = userdate::format($record->timecreated);
        }

        $completion = $DB->get_record("course_completions", [
            "userid" => $USER->id,
            "course" => $courseid,
        ]);
        if ($completion && !empty($completion->timecompleted)) {
            $enroldata["timecompleted"] = userdate::format($completion->timecompleted);
        }

        require_once("{$CFG->dirroot}/lib/filelib.php");
        $description = file_rewrite_pluginfile_urls(
            $course->summary, 'pluginfile.php',
            $coursecontext->id, 'course', 'summary', null
        );
        $custom = [
            "course_summary_html" => format_text($description, $course->summaryformat, ["context" => $coursecontext]),
        ];

        require_once("{$CFG->dirroot}/user/profile/lib.php");
        $profile = profile_user_record($USER->id);
        if (is_object($profile)) {
            foreach ($profile as $key => $value) {
                $custom["user_profile_{$key}"] = is_scalar($value) ? $value : "";
            }
        }

        if (class_exists("\\core_course\\customfield\\course_handler")) {
            $handler = course_handler::create();
            $datas = $handler->get_instance_data($courseid, true);
            foreach ($datas as $data) {
                $shortname = $data->get_field()->get("shortname");
                $custom["course_customfield_" . $shortname] = $data->export_value();
            }
        }

        $data = [
            "course_id" => $course->id,
            "course_fullname" => format_string($course->fullname),
            "course_shortname" => format_string($course->shortname),
            "course_summary" => $custom["course_summary_html"] ?? "",
            "course_startdate" => !empty($course->startdate) ? userdate::format($course->startdate) : "",

            "user_id" => $USER->id,
            "user_fullname" => fullname($USER),
            "user_firstname" => $USER->firstname,
            "user_lastname" => $USER->lastname,
            "user_email" => $USER->email,
            "user_username" => $USER->username,
            "user_idnumber" => $USER->idnumber,

            "user_address" => $USER->address,
            "user_city" => $USER->city,
            "user_country" => $USER->country,
            "user_phone1" => $USER->phone1,
            "user_phone2" => $USER->phone2,

            "enrol_timeenrolled" => $enroldata["timeenrolled"] ?? "",
            "completion_timecompleted" => $enroldata["timecompleted"] ?? "",

            "full_date" => userdate::format(time()),
        ];

        foreach ($custom as $key => $value) {
            $data[$key] = $value;
        }

        foreach (self::get_plugin_placeholder_classes() as $classname) {
            if (!is_callable([$classname, "build_data"])) {
                continue;
            }

            try {
                $plugindata = $classname::build_data($course, $enroldata, $data);
                if (is_array($plugindata)) {
                    foreach ($plugindata as $key => $value) {
                        $data[$key] = $value;
                    }
                }
            } catch (Throwable) {
                continue;
            }
        }

        return $data;
    }

    /**
     * Return all subplugin placeholder classes that exist.
     *
     * @return array
     */
    private static function get_plugin_placeholder_classes(): array {
        $classes = [];

        foreach (subplugin_manager::get_installed_subplugins() as $pluginname => $path) {
            $classname = "\\koperedashboard_{$pluginname}\\placeholders";
            if (class_exists($classname)) {
                $classes[] = $classname;
            }
        }

        return $classes;
    }

    /**
     * Convert one timestamp to a Portuguese long date.
     *
     * @param int $timestamp
     * @return string
     */
    private static function format_date_words(int $timestamp): string {
        $months = [
            1 => "janeiro",
            2 => "fevereiro",
            3 => "março",
            4 => "abril",
            5 => "maio",
            6 => "junho",
            7 => "julho",
            8 => "agosto",
            9 => "setembro",
            10 => "outubro",
            11 => "novembro",
            12 => "dezembro",
        ];

        $day = (int) userdate($timestamp, "j");
        $month = (int) userdate($timestamp, "n");
        $year = (int) userdate($timestamp, "Y");

        return "{$day} de {$months[$month]} de {$year}";
    }
}
