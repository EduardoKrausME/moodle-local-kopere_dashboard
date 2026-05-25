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
 * open_contract.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_contracts\contracts\manager;
use koperedashboard_contracts\contracts\pdf_service;

require_once(__DIR__ . "/../../../../config.php");

$acceptid = required_param("acceptid", PARAM_INT);

$context = context_system::instance();
require_login();

$accept = manager::get_acceptance_by_id($acceptid);
if (!$accept) {
    throw new moodle_exception("invalidrecord", "error");
}

$canmanage = has_capability("koperedashboard/contracts:manage", $context);
if ((int) $accept->userid !== (int) $USER->id && !$canmanage) {
    require_capability("koperedashboard/contracts:manage", $context);
}

pdf_service::send_signed_pdf($acceptid);
