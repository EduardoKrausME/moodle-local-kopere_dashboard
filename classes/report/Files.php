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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\util\BytesUtil;

class Files {
    public static function countAllSpace() {
        global $DB;

        $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files}');

        return $count->space;
    }

    public static function countAllCourseSpace() {
        global $DB;

        $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files} WHERE filearea=\'content\'');

        return $count->space;
    }

    public static function countAllUsersSpace() {
        global $DB;

        $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files} WHERE component=\'user\'');

        return $count->space;
    }

    public static function listSizesCourses() {
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
                array('contextlevel' => CONTEXT_COURSE,
                    'instanceid' => $course->id
                ));

            $modulessize = $DB->get_record_sql("
                    SELECT SUM( f.filesize ) AS modulessize
                      FROM {course_modules} cm, {files} f, {context} ctx
                     WHERE ctx.id = f.contextid
                       AND ctx.instanceid   = cm.id
                       AND ctx.contextlevel = :contextlevel
                       AND cm.course        = :course
                  GROUP BY cm.course",
                array('contextlevel' => CONTEXT_MODULE,
                    'course' => $course->id));

            $coursesizeVal = isset($coursesize->coursesize) ? $coursesize->coursesize : 0;
            $modulessizeVal = isset($modulessize->modulessize) ? $modulessize->modulessize : 0;

            $courses[$course->id]->coursesize = BytesUtil::sizeToByte($coursesizeVal);
            $courses[$course->id]->modulessize = BytesUtil::sizeToByte($modulessizeVal);

            $courses[$course->id]->allsize = $coursesizeVal + $modulessizeVal;
        }

        return $courses;
    }
}