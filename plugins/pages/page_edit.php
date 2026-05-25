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
 * page_edit.php
 *
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_pages\service\webpages_service;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");
require_once($CFG->libdir . "/editorlib.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/pages:manage", $context);

$id = optional_param("id", 0, PARAM_INT);
$menuid = optional_param("menuid", 0, PARAM_INT);
$page = webpages_service::get_page($id) ?: webpages_service::default_page($menuid);
$error = "";

if (data_submitted() && confirm_sesskey()) {
    $page = new stdClass();
    $page->id = optional_param("id", 0, PARAM_INT);
    $page->menuid = optional_param("menuid", 0, PARAM_INT);
    $page->courseid = optional_param("courseid", 0, PARAM_INT);
    $page->title = optional_param("title", "", PARAM_TEXT);
    $page->link = optional_param("link", "", PARAM_TEXT);
    $page->theme = optional_param("theme", webpages_service::get_default_theme(), PARAM_TEXT);
    $page->visible = optional_param("visible", 0, PARAM_BOOL);

    try {
        $pageid = webpages_service::save_page($page);
        redirect(
            new moodle_url(
                "/local/kopere_dashboard/plugins/pages/page_view.php",
                ["id" => $pageid, "message" => $page->id ? "updated" : "created"]
            )
        );
    } catch (moodle_exception $e) {
        $error = $e->getMessage();
    }
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php", ["id" => $id, "menuid" => $menuid]));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->add_body_class("kopere_dashboard-pages-admin");
$PAGE->set_context($context);
$PAGE->set_title($id ? get_string("page_edit", "koperedashboard_pages") : get_string("page_new", "koperedashboard_pages"));
$PAGE->requires->strings_for_js(["ajax_error"], "koperedashboard_pages");
$PAGE->requires->js_call_amd("koperedashboard_pages/webpages", "pageUrl");

$editor = editors_get_preferred_editor(FORMAT_HTML);
$editor->use_editor("kopere_dashboard-page-text", [
    "context" => $context,
    "noclean" => true,
    "trusttext" => true,
]);

$menuoptions = webpages_service::menu_options();
foreach ($menuoptions as $key => $option) {
    $menuoptions[$key]["selected"] = $option["key"] == $page->menuid;
}

$courseoptions = webpages_service::course_options();
foreach ($courseoptions as $key => $option) {
    $courseoptions[$key]["selected"] = $option["key"] == $page->courseid;
}

$themeoptions = webpages_service::list_themes();
foreach ($themeoptions as $key => $option) {
    $themeoptions[$key]["selected"] = $option["key"] == $page->theme;
}

$templatecontext = [
    "sesskey" => sesskey(),
    "error" => $error,
    "haserror" => $error != "",
    "actionurl" => new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php"),
    "cancelurl" => new moodle_url("/local/kopere_dashboard/plugins/pages/page_view.php", ["id" => $page->id]),
    "id" => $page->id,
    "title" => s($page->title),
    "link" => s($page->link),
    "text" => s($page->text),
    "visible" => !empty($page->visible),
    "menuoptions" => $menuoptions,
    "courseoptions" => $courseoptions,
    "themeoptions" => $themeoptions,
];

if ($page->id) {
    $params = [
        "page" => "webpages",
        "id" => $id,
        "link" => $page->link,
    ];
    $templatecontext["pageedit_url"] = new moodle_url("/local/kopere_dashboard/_editor/", $params);
}

$content = $OUTPUT->render_from_template("koperedashboard_pages/page_edit", $templatecontext);
layout::page_render($context, $content, true);
