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
 * menu_delete.php
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
$menu = webpages_service::get_menu($id);
if (!$menu) {
    throw new moodle_exception("error_menu_not_found", "koperedashboard_pages");
}

$error = "";
if (data_submitted() && confirm_sesskey()) {
    try {
        webpages_service::delete_menu($id);
        redirect(new moodle_url("/local/kopere_dashboard/plugins/pages/", ["message" => "deleted"]));
    } catch (moodle_exception $e) {
        $error = $e->getMessage();
    }
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/menu_delete.php", ["id" => $id]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("menu_delete", "koperedashboard_pages"));

$templatecontext = [
    "error" => $error,
    "haserror" => $error != "",
    "title" => format_string($menu->title),
    "message" => get_string("delete_menu_confirm", "koperedashboard_pages", format_string($menu->title)),
    "actionurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/menu_delete.php", ["id" => $id]))->out(false),
    "cancelurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/"))->out(false),
    "sesskey" => sesskey(),
];

$content = $OUTPUT->render_from_template("koperedashboard_pages/confirm_delete", $templatecontext);
layout::page_render($context, $content, true);
