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
 * settings.php
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

$webpagesanalyticsid = optional_param("webpages_analytics_id", "", PARAM_RAW_TRIMMED);

if (data_submitted() && confirm_sesskey()) {
    set_config(
        "webpages_theme", webpages_service::clean_theme(optional_param("webpages_theme", "standard", PARAM_TEXT)),
        "koperedashboard_pages"
    );
    set_config("webpages_analytics_id", $webpagesanalyticsid, "koperedashboard_pages");
    redirect(new moodle_url("/local/kopere_dashboard/plugins/pages/", ["message" => "settings"]));
}

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/pages/settings.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->add_body_class("kopere_dashboard-pages-admin");
$PAGE->set_context($context);
$PAGE->set_title(get_string("settings_title", "koperedashboard_pages"));

$theme = webpages_service::get_default_theme();
$themeoptions = webpages_service::list_themes();
foreach ($themeoptions as $key => $option) {
    $themeoptions[$key]["selected"] = ((string) $option["key"] == $theme);
}

$templatecontext = [
    "actionurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/settings.php"))->out(false),
    "cancelurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/"))->out(false),
    "sesskey" => sesskey(),
    "themeoptions" => $themeoptions,
    "analytics" => s((string) get_config("koperedashboard_pages", "webpages_analytics_id")),
];

$content = $OUTPUT->render_from_template("koperedashboard_pages/settings", $templatecontext);
layout::page_render($context, $content, true);
