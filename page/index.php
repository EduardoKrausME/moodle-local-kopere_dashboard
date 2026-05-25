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
 * Public static pages for Kopere Dashboard.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.RequireLogin.Missing

use koperedashboard_pages\service\webpages_service;

require_once(__DIR__ . "/../../../config.php");

$context = context_system::instance();
$menulink = optional_param("menu", "", PARAM_TEXT);
$pagelink = optional_param("p", "", PARAM_TEXT);
$htmldata = optional_param("htmldata", "", PARAM_RAW);

if ($htmldata != "" && confirm_sesskey()) {
    $pagelink = optional_param("link", "", PARAM_TEXT);
}

$PAGE->set_context($context);
$PAGE->add_body_class("kopere_dashboard-public-pages");
$PAGE->set_pagetype("local-kopere_dashboard-page");
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');

if ($pagelink != "") {
    $page = webpages_service::get_page_by_link($pagelink);
    if (!$page) {
        header("HTTP/1.0 404 Not Found");
        throw new moodle_exception("error_page_not_found", "koperedashboard_pages");
    }

    if ($htmldata != "" && confirm_sesskey()) {
        $page->text = $htmldata;
    }

    $menu = webpages_service::get_menu((int)$page->menuid);
    $PAGE->set_url(webpages_service::public_page_url((string)$page->link));
    $PAGE->set_pagelayout(webpages_service::clean_theme((string)$page->theme));
    $PAGE->set_title(format_string($page->title));

    $heading = format_string($page->title);
    if (webpages_service::can_manage()) {
        $editurl = new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php", ["id" => $page->id]);
        $heading .= " - " . html_writer::link($editurl, get_string("page_edit", "koperedashboard_pages"), [
            "target" => "_blank",
            "class" => "kopere_dashboard-page-editlink",
        ]);
    }
    $PAGE->set_heading($heading, false);

    $PAGE->navbar->add(get_string("webpages_allpages", "koperedashboard_pages"), webpages_service::public_index_url());
    if ($menu) {
        $PAGE->navbar->add(format_string($menu->title), webpages_service::public_menu_url((string)$menu->link));
    }
    $PAGE->navbar->add(format_string($page->title));

    $PAGE->requires->js_call_amd("koperedashboard_pages/webpages", "view_page");

    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template("koperedashboard_pages/public_view", [
        "title" => format_string($page->title),
        "content" => webpages_service::expand_shortcodes((string)$page->text),
        "analytics" => webpages_service::analytics_html(),
    ]);
    echo $OUTPUT->footer();
    die;
}

if ($menulink != "") {
    $menu = webpages_service::get_menu_by_link($menulink);
    if (!$menu) {
        header("HTTP/1.0 404 Not Found");
        throw new moodle_exception("error_menu_not_found", "koperedashboard_pages");
    }

    $PAGE->set_url(webpages_service::public_menu_url((string)$menu->link));
    $PAGE->set_pagelayout(webpages_service::get_default_theme());
    $PAGE->set_title(format_string($menu->title));
    $PAGE->set_heading(format_string($menu->title));
    $PAGE->navbar->add(get_string("webpages_allpages", "koperedashboard_pages"), webpages_service::public_index_url());
    $PAGE->navbar->add(format_string($menu->title));
} else {
    $PAGE->set_url(webpages_service::public_index_url());
    $PAGE->set_pagelayout(webpages_service::get_default_theme());
    $PAGE->set_title(get_string("webpages_allpages", "koperedashboard_pages"));
    $PAGE->set_heading(get_string("webpages_allpages", "koperedashboard_pages"));
    $PAGE->navbar->add(get_string("webpages_allpages", "koperedashboard_pages"));
}

$data = webpages_service::public_listing($menulink);
$data["analytics"] = webpages_service::analytics_html();

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("koperedashboard_pages/public_listing", $data);
echo $OUTPUT->footer();
