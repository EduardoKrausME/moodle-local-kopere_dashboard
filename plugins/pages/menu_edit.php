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
 * menu_edit.php
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

$id = optional_param("id", 0, PARAM_INT);
$parentid = optional_param("menuid", 0, PARAM_INT);
$menu = webpages_service::get_menu($id) ?: webpages_service::default_menu($parentid);
$error = "";

if (data_submitted() && confirm_sesskey()) {
    $menu = new stdClass();
    $menu->id = optional_param("id", 0, PARAM_INT);
    $menu->menuid = optional_param("menuid", 0, PARAM_INT);
    $menu->title = optional_param("title", "", PARAM_TEXT);
    $menu->link = optional_param("link", "", PARAM_TEXT);
    $menu->inheader = optional_param("inheader", 0, PARAM_BOOL);
    $menu->icon = optional_param("icon", "", PARAM_TEXT);

    try {
        webpages_service::save_menu($menu);
        redirect(new moodle_url("/local/kopere_dashboard/plugins/pages/", ["message" => $menu->id ? "updated" : "created"]));
    } catch (moodle_exception $e) {
        $error = $e->getMessage();
    }
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/menu_edit.php", ["id" => $id, "menuid" => $parentid]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->add_body_class("kopere_dashboard-pages-admin");
$PAGE->set_context($context);
$PAGE->set_title($id ? get_string("menu_edit", "koperedashboard_pages") : get_string("menu_new", "koperedashboard_pages"));
$PAGE->requires->strings_for_js(["ajax_error"], "koperedashboard_pages");
$PAGE->requires->js_call_amd("koperedashboard_pages/webpages", "menuUrl");

$templatecontext = [
    "error" => $error,
    "haserror" => $error != "",
    "actionurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/menu_edit.php"))->out(false),
    "cancelurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/"))->out(false),
    "sesskey" => sesskey(),
    "id" => (int) $menu->id,
    "title" => s($menu->title),
    "link" => s($menu->link),
    "icon" => s($menu->icon),
    "inheader" => !empty($menu->inheader),
    "menuoptions" => webpages_service::menu_options((int) $menu->id),
];

foreach ($templatecontext["menuoptions"] as $key => $option) {
    $templatecontext["menuoptions"][$key]["selected"] = ((int) $option["key"] == (int) $menu->menuid);
}

$content = $OUTPUT->render_from_template("koperedashboard_pages/menu_edit", $templatecontext);
layout::page_render($context, $content, true);
