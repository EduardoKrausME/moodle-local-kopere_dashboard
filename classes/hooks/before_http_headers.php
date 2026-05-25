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
 * before_http_headers.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\hooks;

use coding_exception;

/**
 * Class before_http_headers
 */
class before_http_headers {
    /**
     * Function callback
     *
     * @param \core\hook\output\before_http_headers $hook
     * @return void
     * @throws coding_exception
     */
    public static function callback(\core\hook\output\before_http_headers $hook): void {
        global $PAGE, $SCRIPT;

        if (empty($PAGE) || empty($SCRIPT)) {
            return;
        }

        if (!isloggedin() || isguestuser()) {
            return;
        }

        // Enforce contract acceptance when accessing a course (course/view.php).
        if ($SCRIPT == "/course/view.php" && !empty($PAGE->course) && !empty($PAGE->course->id)) {
            $gatefqcn = "\\koperedashboard_contractss\\contracts\\gate";
            if (class_exists($gatefqcn)) {
                $gatefqcn::enforce_course_access($PAGE->course->id);
            }
        }
    }
}
