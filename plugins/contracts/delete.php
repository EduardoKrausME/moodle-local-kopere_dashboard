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
 * delete.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_contracts\audit\logger;

require_once(__DIR__ . "/../../../../config.php");

$id = required_param("id", PARAM_INT);

$context = context_system::instance();
require_login();
require_capability("koperedashboard/contracts:manage", $context);

require_sesskey();

$DB->delete_records("koperedashboard_contract_courses", ["contractid" => $id]);
$DB->delete_records("koperedashboard_contract_accept", ["contractid" => $id]);
$DB->delete_records("koperedashboard_contracts", ["id" => $id]);

logger::contract_deleted($id);

redirect(new moodle_url("/local/kopere_dashboard/plugins/contracts/manage.php"));
