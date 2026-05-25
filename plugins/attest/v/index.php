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
 * index.php
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.RequireLogin.Missing
use koperedashboard_attest\helper\studentcard_helper;
use local_kopere_dashboard\audit\lgpd;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../../config.php");

$token = required_param("token", PARAM_ALPHANUMEXT);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/attest/v/", ["token" => $token]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string("verify_title", "koperedashboard_attest"));

if (isset($token[15])) {
    $decoded = studentcard_helper::decode_validation_code($token);
    $user = core_user::get_user($decoded["userid"], "*", MUST_EXIST);
    $issue = (object) [
        "timecreated" => time(),
        "validuntil" => time() + (DAYSECS * 180),
        "htmlsha1" => $token,
    ];
    $courses = enrol_get_my_courses();
    $course = reset($courses);
} else {
    $issue = $DB->get_record("koperedashboard_attest_issue", ["token" => $token]);
    if (!$issue) {
        throw new moodle_exception("invalidaccess", "error");
    }

    $course = get_course($issue->courseid);
    $user = core_user::get_user($issue->userid, "*", MUST_EXIST);
}

$sql = "
    SELECT uid.data
      FROM mdl_user_info_data AS uid
      JOIN mdl_user_info_field AS uif ON uif.id = uid.fieldid
     WHERE uif.shortname = 'cpf'
       AND uid.userid    = :userid";
$cpf = $DB->get_field_sql($sql, ["userid" => $user->id]);

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
