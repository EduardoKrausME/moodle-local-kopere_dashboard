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
 * signatures.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\output\layout;
use local_kopere_dashboard\util\userdate;

require_once(__DIR__ . "/../../../../config.php");

$contractid = optional_param("contractid", 0, PARAM_INT);

$context = context_system::instance();
require_login();
require_capability("koperedashboard/contracts:manage", $context);

$params = [];
if ($contractid) {
    $params["contractid"] = $contractid;
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/contracts/signatures.php", $params));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("signatures_title", "koperedashboard_contracts"));

$sql = "SELECT a.id, a.contractid, a.courseid, a.userid, a.timeaccepted, a.readseconds,
               c.name AS contractname,
               cr.fullname AS coursename,
               u.firstname, u.lastname, u.email
          FROM {koperedashboard_contract_accept} a
          JOIN {koperedashboard_contracts} c ON c.id = a.contractid
          JOIN {course} cr ON cr.id = a.courseid
          JOIN {user} u ON u.id = a.userid
         WHERE 1 = 1";
$queryparams = [];

if ($contractid) {
    $sql .= " AND a.contractid = :contractid";
    $queryparams["contractid"] = $contractid;
}

$sql .= " ORDER BY a.timeaccepted DESC";
$rows = $DB->get_records_sql($sql, $queryparams, 0, 500);

$items = [];
foreach ($rows as $row) {
    $items[] = [
        "contractname" => format_string($row->contractname),
        "coursename" => format_string($row->coursename),
        "username" => fullname($row),
        "useremail" => $row->email,
        "timeaccepted" => userdate::format($row->timeaccepted),
        "readseconds" => $row->readseconds,
        "pdfurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/open_contract.php", ["acceptid" => $row->id]),
    ];
}

$data = [
    "items" => $items,
    "hasitems" => !empty($items),
    "backurl" => new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"),
];

$content = $OUTPUT->render_from_template("koperedashboard_contracts/signatures", $data);
layout::page_render($context, $content, true);
