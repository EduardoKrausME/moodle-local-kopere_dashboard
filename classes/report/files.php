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
 * @created    15/05/17 23:50
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;

use local_kopere_dashboard\util\bytes_util;

/**
 * Class files
 *
 * @package local_kopere_dashboard\report
 */
class files {
    /**
     * @return mixed
     * @throws \dml_exception
     */
    public static function count_all_space() {
        global $DB;

        $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files}');

        return $count->space;
    }

    /**
     * @return mixed
     * @throws \dml_exception
     */
    public static function count_all_course_space() {
        global $DB;

        $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files} WHERE filearea=\'content\'');

        return $count->space;
    }

    /**
     * @return mixed
     * @throws \dml_exception
     */
    public static function count_all_user_space() {
        global $DB;

        $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files} WHERE component=\'user\'');

        return $count->space;
    }

    /**
     * @return array
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function list_sizes_courses() {
        global $DB;

        $courses = $DB->get_records_sql('SELECT id, fullname, shortname, visible, timecreated FROM {course} WHERE id > 1');

        foreach ($courses as $course) {

            $coursesize = $DB->get_record_sql("
                    SELECT SUM( f.filesize ) AS coursesize
                      FROM {files} f, {context} ctx
                     WHERE ctx.id           = f.contextid
                       AND ctx.contextlevel = :contextlevel
                       AND ctx.instanceid   = :instanceid
                  GROUP BY ctx.instanceid",
                [
                    'contextlevel' => CONTEXT_COURSE,
                    'instanceid' => $course->id
                ]);

            $modulessize = $DB->get_record_sql("
                    SELECT SUM( f.filesize ) AS modulessize
                      FROM {course_modules} cm, {files} f, {context} ctx
                     WHERE ctx.id = f.contextid
                       AND ctx.instanceid   = cm.id
                       AND ctx.contextlevel = :contextlevel
                       AND cm.course        = :course
                       AND cm.deletioninprogress = 0
                  GROUP BY cm.course",
                [
                    'contextlevel' => CONTEXT_MODULE,
                    'course' => $course->id
                ]);

            $coursesizeval = isset($coursesize->coursesize) ? $coursesize->coursesize : 0;
            $modulessizeval = isset($modulessize->modulessize) ? $modulessize->modulessize : 0;

            $courses[$course->id]->coursesize = bytes_util::size_to_byte($coursesizeval);
            $courses[$course->id]->modulessize = bytes_util::size_to_byte($modulessizeval);

            $courses[$course->id]->allsize = $coursesizeval + $modulessizeval;
        }

        return $courses;
    }
}
