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
 * pdf.php
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\service\issue_service;

require_once(__DIR__ . "/../../../../config.php");

require_login();

$context = context_system::instance();
$PAGE->set_context($context);

$issueid = optional_param("issueid", 0, PARAM_INT);
$tplid = optional_param("tplid", 0, PARAM_INT);
$courseid = optional_param("courseid", 0, PARAM_INT);
$recreate = optional_param("recreate", 0, PARAM_INT);

$sys = context_system::instance();
require_capability("koperedashboard/attest:view", $sys);

if ($issueid) {
    $issue = $DB->get_record("koperedashboard_attest_issue", ["id" => $issueid], "*", MUST_EXIST);

    if ($issue->userid != $USER->id && !has_capability("koperedashboard/attest:manage", $sys)) {
        throw new moodle_exception("invalidaccess", "error");
    }

    issue_service::user_can_access_course($issue->userid, $issue->courseid);

    issue_service::output_pdf($issue);
}

if (!$tplid || !$courseid) {
    throw new moodle_exception("invalidaccess", "error");
}

require_sesskey();
issue_service::user_can_access_course($USER->id, $courseid);

$tpl = $DB->get_record("koperedashboard_attest_tpl", ["id" => $tplid, "active" => 1], "*", MUST_EXIST);

if (!issue_service::template_is_allowed_for_course($tpl, $courseid)) {
    throw new moodle_exception("invalidaccess", "error");
}

$currentissue = issue_service::get_latest_valid_issue($tplid, $courseid, $USER->id);
if ($currentissue) {
    if (!$recreate || !issue_service::can_recreate_issue($currentissue)) {
        redirect(new moodle_url("/local/kopere_dashboard/plugins/attest/pdf.php", [
            "issueid" => $currentissue->id,
        ]));
    }
}

$rendered = issue_service::build_rendered_html($tpl, $courseid);
$issue = issue_service::create_issue($tplid, $courseid, $USER->id, $rendered, $tpl->validmonths);

issue_service::output_pdf($issue);
