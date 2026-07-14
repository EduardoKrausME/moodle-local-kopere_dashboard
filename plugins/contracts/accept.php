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
 * accept.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_contracts\audit\logger;
use koperedashboard_contracts\contracts\manager;
use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\service\placeholders;

require_once(__DIR__ . "/../../../../config.php");

$contractid = required_param("contractid", PARAM_INT);
$courseid = required_param("courseid", PARAM_INT);
$return = optional_param("return", "", PARAM_RAW_TRIMMED);

$context = context_system::instance();
require_login();

$PAGE->set_url(
    new moodle_url("/local/kopere_dashboard/plugins/contracts/accept.php", ["contractid" => $contractid, "courseid" => $courseid])
);
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("accept_title", "koperedashboard_contracts"));

$PAGE->requires->strings_for_js(["js_processing", "js_completed"], "local_kopere_dashboard");
$PAGE->requires->js_call_amd("koperedashboard_contracts/accept", "init", []);

$contract = $DB->get_record("koperedashboard_contracts", ["id" => $contractid, "active" => 1], "*", MUST_EXIST);

$course = get_course($courseid);
$coursecontext = context_course::instance($courseid);

$already = manager::user_has_accepted($contractid, $courseid, $USER->id);
if ($already) {
    $dest = !empty($return) ? new moodle_url($return) : new moodle_url("/course/view.php", ["id" => $courseid]);
    redirect($dest);
}

if (optional_param("accept", 0, PARAM_INT) == 1 && confirm_sesskey()) {
    $readseconds = optional_param("readseconds", 0, PARAM_INT);

    manager::accept_contract($contractid, $courseid, $USER->id, $readseconds);
    logger::contract_accepted($contractid, $courseid, $readseconds);

    $dest = !empty($return) ? new moodle_url($return) : new moodle_url("/course/view.php", ["id" => $courseid]);
    redirect($dest);
}

if (!class_exists('Mustache_Engine')) {
    class_alias('Mustache\Engine', 'Mustache_Engine');
}
$placeholderdata = placeholders::build_data($courseid);
$engine = new \Mustache_Engine([
    "escape" => static function($value) {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    },
]);

$renderedcontent = $engine->render($contract->content, $placeholderdata);
$content = format_text($renderedcontent, 1, ["context" => $coursecontext, "overflowdiv" => true]);

$data = [
    "contractname" => format_string($contract->name),
    "coursename" => format_string($course->fullname),
    "contenthtml" => $content,
    "posturl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/accept.php", [
        "contractid" => $contractid,
        "courseid" => $courseid,
        "return" => $return,
    ]),
    "sesskey" => sesskey(),
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("koperedashboard_contracts/accept", $data);
echo $OUTPUT->footer();
