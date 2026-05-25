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
 * page_view.php
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

$menu = webpages_service::get_menu((int) $page->menuid);
$course = null;
if (!empty($page->courseid)) {
    $course = $DB->get_record("course", ["id" => $page->courseid], "id,fullname");
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/page_view.php", ["id" => $id]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->add_body_class("kopere_dashboard-pages-admin");
$PAGE->set_context($context);
$PAGE->set_title(format_string($page->title));
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->requires->js_call_amd("koperedashboard_pages/webpages", "view_page");

$templatecontext = [
    "flash" => webpages_service::flash(),
    "id" => (int) $page->id,
    "title" => format_string($page->title),
    "link" => s($page->link),
    "publicurl" => webpages_service::public_page_url((string) $page->link)->out(false),
    "editurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php", ["id" => $page->id]))->out(false),
    "deleteurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_delete.php", ["id" => $page->id]))->out(false),
    "backurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/"))->out(false),
    "theme" => webpages_service::theme_name((string) $page->theme),
    "visible" => !empty($page->visible) ? get_string("yes") : get_string("no"),
    "pageorder" => (int) $page->pageorder,
    "menu" => $menu ? format_string($menu->title) : get_string("root_menu", "koperedashboard_pages"),
    "course" => $course ? format_string($course->fullname) : get_string("none_course", "koperedashboard_pages"),
    "text" => webpages_service::expand_shortcodes((string) $page->text),
];

$content = $OUTPUT->render_from_template("koperedashboard_pages/page_view", $templatecontext);
layout::page_render($context, $content, true);
