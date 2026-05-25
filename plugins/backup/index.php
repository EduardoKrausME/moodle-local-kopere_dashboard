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
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_backup\backup_manager;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/backup:manage", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/backup/"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("page_title", "koperedashboard_backup"));
$PAGE->set_heading(get_string("page_title", "koperedashboard_backup"));

$message = null;
$messagetype = null;
$dbformat = backup_manager::get_default_database_export_format();
$separatelogs = false;

if (optional_param("action", "", PARAM_ALPHA) !== "") {
    require_capability("koperedashboard/backup:generate", $context);
    require_sesskey();

    $action = required_param("action", PARAM_ALPHA);
    $dbformat = optional_param("dbformat", $dbformat, PARAM_ALPHA);
    $separatelogs = optional_param("separatelogs", 0, PARAM_BOOL);

    try {
        if ($action === "moodledata") {
            $filepath = backup_manager::create_moodledata_backup();
            $message = get_string("moodledata_success", "koperedashboard_backup", basename($filepath));
            $messagetype = \core\output\notification::NOTIFY_SUCCESS;
        } else if ($action === "database") {
            $filepath = backup_manager::create_database_backup($dbformat, (bool) $separatelogs);
            $message = get_string("database_success", "koperedashboard_backup", basename($filepath));
            $messagetype = \core\output\notification::NOTIFY_SUCCESS;
        } else {
            throw new moodle_exception("invalidaction", "koperedashboard_backup");
        }
    } catch (Throwable $e) {
        $message = $e->getMessage();
        $messagetype = \core\output\notification::NOTIFY_ERROR;
    }
}

$backups = [];
foreach (backup_manager::list_backups() as $file) {
    $backups[] = [
        "filename" => $file["filename"],
        "type_label" => get_string("type_" . $file["type"], "koperedashboard_backup"),
        "size" => display_size((int) $file["filesize"]),
        "timecreated" => userdate((int) $file["timemodified"]),
        "downloadurl" => (new moodle_url("/local/kopere_dashboard/plugins/backup/download.php", [
            "file" => $file["filename"],
        ]))->out(false),
    ];
}

$formats = [];
foreach (backup_manager::get_database_export_formats() as $format) {
    $formats[] = [
        "value" => $format["value"],
        "label" => $format["label"],
        "selected" => $format["value"] === $dbformat,
    ];
}

$templatecontext = [
    "cangenerate" => has_capability("koperedashboard/backup:generate", $context),
    "moodledata_title" => get_string("moodledata_title", "koperedashboard_backup"),
    "moodledata_desc" => get_string("moodledata_desc", "koperedashboard_backup"),
    "database_title" => get_string("database_title", "koperedashboard_backup"),
    "database_desc" => get_string("database_desc", "koperedashboard_backup"),
    "backupdir" => backup_manager::get_backup_dir(),
    "sourcedblabel" => backup_manager::get_source_database_label(),
    "sesskey" => sesskey(),
    "formats" => $formats,
    "separatelogs" => $separatelogs,
    "files" => $backups,
    "hasfiles" => !empty($backups),
    "emptyfiles" => get_string("emptyfiles", "koperedashboard_backup"),
];

$content = $OUTPUT->render_from_template("koperedashboard_backup/index", $templatecontext);
$afterheader = $OUTPUT->notification($message, $messagetype);
layout::page_render($context, $content, true, $afterheader);
