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
 * profile file
 *
 * introduced 15/05/17 03:13
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\url_util;

/**
 * Class profile
 *
 * @package local_kopere_dashboard
 */
class profile {

    /**
     * Function details
     *
     * @param $user
     * @param bool $echo
     *
     * @return
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function details($user, $echo = true) {
        global $OUTPUT;

        $data = [
            "user_data" => self::user_data($user, 110, false),
            "list_courses" => self::list_courses($user->id),
            "get_user_info" => self::get_user_info($user),
        ];
        $details = $OUTPUT->render_from_template("local_kopere_dashboard/profile_details", $data);
        if ($echo) {
            echo $details;
        } else {
            return $details;
        }
    }

    /**
     * Function details
     *
     * @param $user
     * @param bool $echo
     *
     * @return
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function details2($user, $echo = true) {
        global $OUTPUT;

        $data = [
            "user_data" => self::user_data($user, 110, false),
            "list_courses" => self::list_courses($user->id),
            "get_user_info" => self::get_user_info($user),
        ];
        $details = $OUTPUT->render_from_template("local_kopere_dashboard/profile_details_2", $data);
        if ($echo) {
            echo $details;
        } else {
            return $details;
        }
    }

    /**
     * Function user_data
     *
     * @param $user
     * @param $imgheight
     * @param bool $echo
     *
     * @return string
     */
    public static function user_data($user, $imgheight, $echo = true) {
        global $PAGE, $OUTPUT;

        $userpicture = new \user_picture($user);
        $userpicture->size = 110;
        $user->profileimageurl = $userpicture->get_url($PAGE)->out(false);
        $user->fullname = fullname($user);

        $data = [
            "user" => $user,
            "imgheight" => $imgheight,
        ];
        $userdata = $OUTPUT->render_from_template("local_kopere_dashboard/profile_user_data", $data);
        if ($echo) {
            echo $userdata;
        } else {
            return $userdata;
        }
    }

    /**
     * Function list_courses
     *
     * @param $userid
     *
     * @return null|string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function list_courses($userid) {
        global $DB, $OUTPUT;

        $courses = enrol_get_all_users_courses($userid);

        if (!count($courses)) {
            return message::warning(get_string("profile_notenrol", "local_kopere_dashboard"));
        }

        $html = "";
        foreach ($courses as $course) {
            $sql
                = "SELECT ue.*
                     FROM {user_enrolments} ue
                     JOIN {enrol} e ON ( e.id = ue.enrolid AND e.courseid = :courseid )
                    WHERE ue.userid = :userid";
            $params = [
                "userid" => $userid,
                "courseid" => $course->id,
            ];

            $enrolment = $DB->get_record_sql($sql, $params, IGNORE_MULTIPLE);
            if ($enrolment->timeend == 0) {
                $expirationend = get_string("profile_enrol_notexpires", "local_kopere_dashboard");
            } else {
                $expirationend = "<br>" . get_string("profile_enrol_expires", "local_kopere_dashboard") . " <em>" .
                    userdate($enrolment->timeend, get_string("dateformat", "local_kopere_dashboard")) . "</em>";
            }

            $roleassignments = $DB->get_records("role_assignments",
                [
                    "contextid" => $course->ctxid,
                    "userid" => $userid,
                ], "", "DISTINCT roleid");

            $rolehtml = "";
            foreach ($roleassignments as $roleassignment) {
                $role = $DB->get_record("role", ["id" => $roleassignment->roleid]);
                $rolehtml .= '<span class="badge badge-primary">' . role_get_name($role) . "</span>";
            }

            $matriculastatus = '<span class="btn-dangerpadding-0-8 border-radius-5 text-nowrap">' .
                get_string("profile_enrol_inactive", "local_kopere_dashboard") . "</span>";
            if ($enrolment->status == 0) {
                $matriculastatus = '<span class="btn-successpadding-0-8 border-radius-5 text-nowrap">' .
                    get_string("profile_enrol_active", "local_kopere_dashboard") . "</span>";
            }

            $url = url_util::makeurl("userenrolment", "mathedit",
                ["courseid" => $course->id, "ueid" => $enrolment->id], "view");
            $data = [
                "course" => $course,
                "url" => $url,
                "rolehtml" => $rolehtml,
                "expirationend" => $expirationend,
                "matriculastatus" => $matriculastatus,
                "date" => userdate($enrolment->timestart, get_string("dateformat", "local_kopere_dashboard")),
            ];
            $html .= $OUTPUT->render_from_template("local_kopere_dashboard/profile_list_courses", $data);
        }
        return $html;
    }

    /**
     * Function get_user_info
     *
     * @param $user
     *
     * @return string
     */
    public static function get_user_info($user) {
        global $OUTPUT;

        return $OUTPUT->render_from_template("local_kopere_dashboard/profile_get_user_info", ["user" => $user]);
    }
}
