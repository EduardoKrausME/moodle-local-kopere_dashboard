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

use core\output\notification;
use koperedashboard_backup\backup_manager;
use local_alternative_file_system\external_file_system;
use local_alternative_file_system\filesystem_config;
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

$dbformat = backup_manager::get_default_database_export_format();
$separatelogs = false;

if ($action = optional_param("action", false, PARAM_TEXT)) {
    require_capability("koperedashboard/backup:generate", $context);
    require_sesskey();

    $dbformat = optional_param("dbformat", $dbformat, PARAM_ALPHA);
    $separatelogs = optional_param("separatelogs", 0, PARAM_BOOL);

    try {
        if ($action === "moodledata") {
            $filepath = backup_manager::create_moodledata_backup();

            $message = get_string("moodledata_success", "koperedashboard_backup", basename($filepath));
            $messagetype = notification::NOTIFY_SUCCESS;
            redirect(new moodle_url("/local/kopere_dashboard/plugins/backup/"), $message, 0, $messagetype);
        } else if ($action === "database") {
            $filepath = backup_manager::create_database_backup($dbformat, (bool) $separatelogs);

            $message = get_string("database_success", "koperedashboard_backup", basename($filepath));
            $messagetype = notification::NOTIFY_SUCCESS;
            redirect(new moodle_url("/local/kopere_dashboard/plugins/backup/"), $message, 0, $messagetype);
        } else if ($action === "friendly_installation") {
            $filepath = backup_manager::create_friendly_installation_backup();

            $message = get_string("friendly_installation_success", "koperedashboard_backup");
            $messagetype = notification::NOTIFY_SUCCESS;
            redirect(new moodle_url("/local/kopere_dashboard/plugins/backup/"), $message, 0, $messagetype);
        } else {
            throw new moodle_exception("invalidaction", "koperedashboard_backup");
        }
    } catch (Throwable $e) {
        $message = $e->getMessage();
        $messagetype = notification::NOTIFY_ERROR;
        redirect(new moodle_url("/local/kopere_dashboard/plugins/backup/"), $message, 0, $messagetype);
    }
}

$strftimedatetimeshort = get_string('strftimedatetimeshort', 'langconfig');

$backups = [];
foreach (backup_manager::list_backups() as $file) {
    $backups[] = [
        "filename" => $file["filename"],
        "type_label" => get_string("type_" . $file["type"], "koperedashboard_backup"),
        "size" => display_size((int) $file["filesize"]),
        "timecreated" => userdate($file["timemodified"], $strftimedatetimeshort),
        "downloadurl" => new moodle_url("/local/kopere_dashboard/plugins/backup/download.php", ["file" => $file["filename"]]),
        "deleteurl" => new moodle_url("/local/kopere_dashboard/plugins/backup/delete.php", ["file" => $file["filename"]]),
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
    "sourcedblabel" => backup_manager::get_source_database_label(),
    "sesskey" => sesskey(),
    "formats" => $formats,
    "separatelogs" => $separatelogs,
    "files" => $backups,
    "hasfiles" => !empty($backups),
    "alternative_file_system_settings" => "{$CFG->wwwroot}/admin/settings.php?section=local_alternative_file_system",
];

try {
    if (backup_manager::is_alternative_file_system_ready()) {
        $externalfilesystem = new external_file_system();

        if ($externalfilesystem) {
            $destination = filesystem_config::get_value("storage_destination");
            if ($destination == "s3") {
                $storagename = "Amazon S3";
                $bucketname = filesystem_config::get_value("settings_s3_bucketname");
                $region = filesystem_config::get_value("settings_s3_region");
                $storageurl = "https://{$region}.console.aws.amazon.com/s3/buckets/{$bucketname}?region={$region}";
            } else if ($destination == "space") {
                $storagename = "Digital Ocean Space";
                $bucketname = filesystem_config::get_value("settings_s3_bucketname");
                $storageurl = "https://cloud.digitalocean.com/spaces/{$bucketname}?path";
            } else if ($destination == "s3generic") {
                $storagename = get_string("settings_s3generic_destino", "local_alternative_file_system");
            } else if ($destination == "gcs") {
                $storagename = "Google Cloud Storage";
            }

            $templatecontext += [
                "is_alternative_file_system" => true,
                "is_alternative_missing" => $externalfilesystem->missing_count() == 0,
                "alternative_storagename" => $storagename,
                "alternative_storageurl" => $storageurl,
            ];
        }
    }
} catch (Exception $e) {
    $templatecontext += [
        "is_alternative_file_system" => false,
        "is_alternative_missing" => false,
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_backup/index", $templatecontext);
layout::page_render($context, $content, true);
