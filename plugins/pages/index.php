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
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_pages\service\webpages_service;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/pages:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->add_body_class("kopere_dashboard-pages-admin");
$PAGE->set_context($context);
$PAGE->set_title(get_string("page_manage_title", "koperedashboard_pages"));

$rows = webpages_service::admin_rows();

$templatecontext = [
    "flash" => webpages_service::flash(),
    "rows" => $rows,
    "hasrows" => !empty($rows),
    "createmenuurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/menu_edit.php"))->out(false),
    "createpageurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php"))->out(false),
    "settingsurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/settings.php"))->out(false),
    "publicurl" => webpages_service::public_index_url()->out(false),
];

$content = $OUTPUT->render_from_template("koperedashboard_pages/manage", $templatecontext);
layout::page_render($context, $content, true);
