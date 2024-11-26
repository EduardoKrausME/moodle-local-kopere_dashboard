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
 * Editor functions.
 *
 * @package     local_kopere_dashboard
 * @copyright   2024 Eduardo kraus (http://eduardokraus.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function vvveb__changue_langs
 *
 * @param $html
 *
 * @return mixed
 * @throws coding_exception
 */
function vvveb__changue_langs($html, $component) {
    global $CFG, $SITE;

    $CFG->debug = false;

    $html = str_replace("{wwwroot}", $CFG->wwwroot, $html);
    $html = str_replace("{shortname}", $SITE->shortname, $html);
    $html = str_replace("{fullname}", $SITE->fullname, $html);

    preg_match_all('/{lang:(.*?)}/', $html, $lags);
    foreach ($lags[1] as $key => $identifier) {
        if (strpos($identifier, "|")) {
            list($identifier, $component) = explode("|", $identifier);
            $text = get_string($identifier, $component);
        } else {
            $text = get_string($identifier, $component);
        }

        $html = str_replace($lags[0][$key], $text, $html);
    }

    return $html;
}

/**
 * Function couse_image
 *
 * @param $course
 *
 * @return bool|string
 */
function couse_image($course) {
    global $CFG, $OUTPUT;

    $courseimage = false;

    /** @var \stored_file $file */
    foreach ($course->get_course_overviewfiles() as $file) {
        $isimage = $file->is_valid_image();
        if ($isimage) {
            $courseimage = file_encode_url("{$CFG->wwwroot}/pluginfile.php",
                "/{$file->get_contextid()}/{$file->get_component()}/" .
                "{$file->get_filearea()}{$file->get_filepath()}{$file->get_filename()}", !$isimage);

        }
    }

    if (empty($courseimage)) {
        $courseimage = $OUTPUT->image_url('curso-no-photo', "theme")->out();
    }

    return $courseimage;
}
