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
 * ajax.php
 *
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_pages\service\webpages_service;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/pages:manage", $context);

$action = required_param("action", PARAM_ALPHAEXT);
$title = optional_param("title", "", PARAM_TEXT);
$id = optional_param("id", 0, PARAM_INT);

header("Content-Type: text/plain; charset=utf-8");

if ($title == "") {
    die("");
}

if ($action == "pageurl") {
    die(webpages_service::unique_page_link($title, $id));
}

if ($action == "menuurl") {
    die(webpages_service::unique_menu_link($title, $id));
}

throw new moodle_exception("invalidrequest");
