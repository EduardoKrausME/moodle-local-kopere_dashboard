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
 * manage.php
 *
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_userimport\service\import_service;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/userimport:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/userimport/manage.php"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("menu_title", "koperedashboard_userimport"));

$action = optional_param("action", "upload", PARAM_ALPHA);
$templatecontext = [
    "mode_upload" => true,
    "uploadurl" => new moodle_url("/local/kopere_dashboard/plugins/userimport/manage.php", ["action" => "upload"]),
    "wwwroot" => $CFG->wwwroot,
    "sesskey" => sesskey(),
    "intro" => get_string("manage_intro", "koperedashboard_userimport"),
    "tips" => [
        ["text" => get_string("tip_headers", "koperedashboard_userimport")],
        ["text" => get_string("tip_detectseparator", "koperedashboard_userimport")],
        ["text" => get_string("tip_password", "koperedashboard_userimport")],
        ["text" => get_string("tip_existing", "koperedashboard_userimport")],
    ],
];

try {
    if ($action === "upload" && data_submitted() && confirm_sesskey()) {
        $token = import_service::store_uploaded_csv($_FILES["csvfile"] ?? []);
        redirect(new moodle_url("/local/kopere_dashboard/plugins/userimport/manage.php", [
            "action" => "map",
            "token" => $token,
        ]));
    }

    if ($action === "map") {
        $token = required_param("token", PARAM_ALPHANUM);
        $state = import_service::get_state($token);

        $templatecontext = array_merge($templatecontext, [
            "mode_upload" => false,
            "mode_mapping" => true,
        ], import_service::get_mapping_context($state));
    }

    if ($action === "preview" && data_submitted() && confirm_sesskey()) {
        $token = required_param("token", PARAM_ALPHANUM);
        $state = import_service::get_state($token);
        $mapping = import_service::clean_mapping(
            optional_param_array("map", [], PARAM_RAW_TRIMMED),
            optional_param_array("custommap", [], PARAM_RAW_TRIMMED)
        );
        $errors = import_service::validate_mapping($mapping);

        if (!empty($errors)) {
            $templatecontext = array_merge($templatecontext, [
                "mode_upload" => false,
                "mode_mapping" => true,
                "errors" => array_map(static function(string $message): array {
                    return ["message" => $message];
                }, $errors),
            ], import_service::get_mapping_context($state, $mapping));
        } else {
            $preview = import_service::preview_import($state, $mapping);
            $templatecontext = array_merge($templatecontext, [
                "mode_upload" => false,
                "mode_preview" => true,
                "token" => $token,
                "mappingjson" => json_encode($mapping),
                "filename" => s($state["originalname"]),
                "summary_total" => $preview["summary"]["total"],
                "summary_create" => $preview["summary"]["create"],
                "summary_existing" => $preview["summary"]["existing"],
                "summary_errors" => $preview["summary"]["errors"],
                "summary_withcourse" => $preview["summary"]["withcourse"],
                "rows" => $preview["rows"],
                "backurl" => new moodle_url("/local/kopere_dashboard/plugins/userimport/manage.php", [
                    "action" => "map",
                    "token" => $token,
                ]),
            ]);
        }
    }

    if ($action === "import" && data_submitted() && confirm_sesskey()) {
        $token = required_param("token", PARAM_ALPHANUM);
        $state = import_service::get_state($token);
        $mappingjson = required_param("mappingjson", PARAM_RAW);
        $mapping = json_decode($mappingjson, true) ?: ["map" => [], "custommap" => []];
        $result = import_service::execute_import($state, $mapping);
        import_service::cleanup($token);

        $templatecontext = array_merge($templatecontext, [
            "mode_upload" => false,
            "mode_result" => true,
            "filename" => s($state["originalname"]),
            "summary_total" => $result["summary"]["total"],
            "summary_created" => $result["summary"]["created"],
            "summary_existing" => $result["summary"]["existing"],
            "summary_enrolled" => $result["summary"]["enrolled"],
            "summary_errors" => $result["summary"]["errors"],
            "rows" => $result["rows"],
            "restarturl" => new moodle_url("/local/kopere_dashboard/plugins/userimport/manage.php"),
        ]);
    }
} catch (Throwable $exception) {
    $templatecontext["errors"] = [["message" => $exception->getMessage()]];
}

$content = $OUTPUT->render_from_template("koperedashboard_userimport/manage", $templatecontext);
layout::page_render($context, $content, true);
