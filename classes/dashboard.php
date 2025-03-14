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
 * Dashboard file
 *
 * introduced 30/01/17 09:39
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\report\files;
use local_kopere_dashboard\server\performancemonitor;
use local_kopere_dashboard\util\bytes_util;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\url_util;

/**
 * Class dashboard
 *
 * @package local_kopere_dashboard
 */
class dashboard {

    /**
     * Function start
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function start() {
        global $PAGE, $OUTPUT;

        dashboard_util::add_breadcrumb("Kopere Dashboard");
        dashboard_util::start_page();

        echo performancemonitor::load_monitor();

        echo $OUTPUT->render_from_template("local_kopere_dashboard/dashboard_start", []);
        $PAGE->requires->js_call_amd('local_kopere_dashboard/dashboard', "start");
        dashboard_util::end_page();
    }

    /**
     * Function monitor
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function monitor() {
        global $OUTPUT;

        $data = [
            "users_link" => url_util::makeurl("users", "dashboard"),
            "users_count" => users::count_all(true) . ' / ' . users::count_all_learners(true),

            "useronline_link" => url_util::makeurl("useronline", "dashboard"),
            "useronline_count_5" => useronline::count(5),
            "useronline_count_60" => useronline::count(60),

            "courses_link" => url_util::makeurl("courses", "dashboard"),
            "courses_count_all" => courses::count_all(true),
            "courses_count_all_visibles" => courses::count_all_visibles(true),

            "disk_link" => url_util::makeurl("reports", "dashboard", ["type" => "server"]),
            "disk_size" => bytes_util::size_to_byte(files::count_all_space()),
        ];
        echo $OUTPUT->render_from_template("local_kopere_dashboard/dashboard_monitor", $data);
    }

    /**
     * Function last_grades
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function last_grades() {
        global $DB, $PAGE, $OUTPUT;

        $grade = new grade();
        $lastgrades = $grade->get_last_grades();

        foreach ($lastgrades as $grade) {

            $user = $DB->get_record("user", ["id" => $grade->userid]);

            $userpicture = new \user_picture($user);
            $userpicture->size = 1;
            $profileimageurl = $userpicture->get_url($PAGE)->out(false);

            if ($grade->itemtype == "mod") {
                $evaluation = get_string("dashboard_grade_inmod", "local_kopere_dashboard", $grade);
            } else if ($grade->itemtype == "course") {
                $evaluation = get_string("dashboard_grade_incourse", "local_kopere_dashboard", $grade);
            } else {
                continue;
            }

            $gradetext = number_format($grade->finalgrade, 1, get_string("decsep", "langconfig"), "") . " " .
                get_string("dashboard_grade_of", "local_kopere_dashboard") . " " . intval($grade->rawgrademax);

            $data = [
                "user_fullname" => fullname($user),
                "profileimageurl" => $profileimageurl,
                "users_details" => url_util::makeurl("users", "details", ["userid" => $user->id]),
                "users_details_ajax" => url_util::makeurl("users", "details", ["userid" => $user->id], "view-ajax"),
                "dashboard_grade_text" =>
                    get_string("dashboard_grade_text", "local_kopere_dashboard",
                        ["grade" => $gradetext, "evaluation" => $evaluation]),
                "grade_date" => userdate($grade->timemodified, get_string("dateformat", "local_kopere_dashboard")),
            ];
            echo $OUTPUT->render_from_template("local_kopere_dashboard/last_grades", $data);
        }
        die();
    }

    /**
     * Function last_enroll
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function last_enroll() {
        global $DB, $PAGE, $OUTPUT;

        $enrol = new enroll();
        $lastenroll = $enrol->last_enroll();

        foreach ($lastenroll as $enrol) {

            $user = $DB->get_record("user", ["id" => $enrol->userid]);
            if ($user) {

                $userpicture = new \user_picture($user);
                $userpicture->size = 1;
                $profileimageurl = $userpicture->get_url($PAGE)->out(false);

                $statusmatricula = '<span class="btn-dangerpadding-0-8 border-radius-5 text-nowrap">' .
                    get_string("dashboard_enrol_inactive", "local_kopere_dashboard") . "</span>";
                if ($enrol->status == 0) {
                    $statusmatricula = '<span class="btn-successpadding-0-8 border-radius-5 text-nowrap">' .
                        get_string("dashboard_enrol_active", "local_kopere_dashboard") . "</span>";
                }

                $data = [
                    "user_fullname" => fullname($user),
                    "profileimageurl" => $profileimageurl,
                    "users_details" => url_util::makeurl("users", "details", ["userid" => $user->id]),
                    "dashboard_enrol_text" => get_string("dashboard_enrol_text", "local_kopere_dashboard", $enrol),
                    "matricula_status" => $statusmatricula,
                    "matricula_date" => userdate($enrol->timemodified, get_string("dateformat", "local_kopere_dashboard")),
                ];
                echo $OUTPUT->render_from_template("local_kopere_dashboard/last_enroll", $data);
            }
        }
    }
}
