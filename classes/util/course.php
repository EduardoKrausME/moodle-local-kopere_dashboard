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
 * Course util class
 *
 * @package   local_kopere_dashboard
 * @copyright 2024 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_kopere_dashboard\util;

use core_course_list_element;

/**
 * Class course
 *
 * @package local_kopere_dashboard
 */
class course {
    /**
     * Function course_image
     *
     * @param $courseid
     *
     * @return bool|string
     * @throws \dml_exception
     */
    public static function overview_image($courseid) {
        global $CFG, $OUTPUT;

        $courseimage = false;
        if ($courseid[0] != 'c') {
            $course = new core_course_list_element(get_course($courseid));

            /** @var \stored_file $file */
            foreach ($course->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                if ($isimage) {
                    $courseimage =
                        file_encode_url("{$CFG->wwwroot}/pluginfile.php",
                        "/{$file->get_contextid()}/local_kopere_dashboard/" .
                        "{$file->get_filearea()}{$file->get_filepath()}{$file->get_filename()}", !$isimage);

                }
            }
        }

        if (empty($courseimage)) {
            $courseimage = $OUTPUT->image_url("course-default", "local_kopere_dashboard")->out();
        }

        return $courseimage;
    }
}
