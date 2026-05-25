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
 * page_delete.php
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

$id = required_param("id", PARAM_INT);
$page = webpages_service::get_page($id);
if (!$page) {
    throw new moodle_exception("error_page_not_found", "koperedashboard_pages");
}

if (data_submitted() && confirm_sesskey()) {
    webpages_service::delete_page($id);
    redirect(new moodle_url("/local/kopere_dashboard/plugins/pages/", ["message" => "deleted"]));
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/page_delete.php", ["id" => $id]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("page_delete", "koperedashboard_pages"));

$templatecontext = [
    "title" => format_string($page->title),
    "message" => get_string("delete_page_confirm", "koperedashboard_pages", format_string($page->title)),
    "actionurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_delete.php", ["id" => $id]))->out(false),
    "cancelurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_view.php", ["id" => $id]))->out(false),
    "sesskey" => sesskey(),
];

$content = $OUTPUT->render_from_template("koperedashboard_pages/confirm_delete", $templatecontext);
layout::page_render($context, $content, true);
