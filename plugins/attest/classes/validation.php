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
 * Attestation and student card token validation provider.
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest;

use core_user;
use koperedashboard_attest\helper\studentcard_helper;
use local_kopere_dashboard\audit\lgpd;
use local_kopere_dashboard\util\userdate;
use moodle_exception;

/**
 * Public token validation provider for the attest subplugin.
 */
class validation {
    /**
     * Validate and render an attestation or student card token.
     *
     * @param string $token Public validation token.
     * @return bool True when this plugin handled the token, otherwise false.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function validate(string $token): bool {
        global $DB, $OUTPUT, $PAGE;

        $issue = $DB->get_record("koperedashboard_attest_issue", ["token" => $token]);

        if ($issue) {
            $course = get_course($issue->courseid);
            $user = core_user::get_user($issue->userid, "*", MUST_EXIST);
        } else {
            $studentcarddata = self::decode_studentcard_token($token);
            if ($studentcarddata === null) {
                return false;
            }

            $user = core_user::get_user($studentcarddata["userid"]);
            if (!$user || !empty($user->deleted)) {
                return false;
            }

            $courses = enrol_get_all_users_courses($user->id, true, "id, fullname");
            $course = reset($courses);
            if (!$course) {
                return false;
            }

            $issue = (object) [
                "timecreated" => time(),
                "validuntil" => time() + (DAYSECS * 180),
                "htmlsha1" => $token,
            ];
        }

        $cpf = self::get_user_cpf((int) $user->id);

        $PAGE->set_title(get_string("verify_title", "koperedashboard_attest"));

        echo $OUTPUT->header();
        echo $OUTPUT->render_from_template("koperedashboard_attest/verify", [
            "coursename" => format_string($course->fullname),
            "username" => fullname($user),
            "useremail" => lgpd::mask_email($user->email),
            "userecpf" => lgpd::mask_cpf($cpf),
            "created" => userdate::format($issue->timecreated),
            "validuntil" => userdate::format($issue->validuntil),
            "htmlsha1" => $issue->htmlsha1,
        ]);
        echo $OUTPUT->footer();

        return true;
    }

    /**
     * Decode a student card token without blocking other validation providers.
     *
     * @param string $token Public validation token.
     * @return array|null Decoded student card data or null when not recognised.
     */
    private static function decode_studentcard_token(string $token): ?array {
        if (strlen($token) < 16 || strlen($token) % 2 !== 0 || !ctype_xdigit($token)) {
            return null;
        }

        try {
            return studentcard_helper::decode_validation_code($token);
        } catch (moodle_exception) {
            return null;
        }
    }

    /**
     * Return the CPF stored in the custom user profile field.
     *
     * @param int $userid User ID.
     * @return string|null CPF value, when available.
     * @throws \dml_exception
     */
    private static function get_user_cpf(int $userid): ?string {
        global $DB;

        $sql = "SELECT uid.data
                  FROM {user_info_data} uid
                  JOIN {user_info_field} uif ON uif.id = uid.fieldid
                 WHERE uif.shortname = :shortname
                   AND uid.userid = :userid";

        $cpf = $DB->get_field_sql($sql, [
            "shortname" => "cpf",
            "userid" => $userid,
        ]);

        return $cpf === false ? null : (string) $cpf;
    }
}
