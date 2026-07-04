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
 * delete.php
 *
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\output\notification;
use koperedashboard_backup\backup_manager;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/backup:manage", $context);

$filename = required_param("file", PARAM_FILE);
$confirm = optional_param("confirm", 0, PARAM_BOOL);

$indexurl = new moodle_url("/local/kopere_dashboard/plugins/backup/");
$pageurl = new moodle_url("/local/kopere_dashboard/plugins/backup/delete.php", ["file" => $filename]);

$PAGE->set_url($pageurl);
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("delete_confirm_title", "koperedashboard_backup"));
$PAGE->set_heading(get_string("page_title", "koperedashboard_backup"));

$filepath = backup_manager::get_backup_file_path($filename);

if ($confirm) {
    require_sesskey();
    backup_manager::delete_backup_file($filename);
    redirect(
        $indexurl,
        get_string("delete_success", "koperedashboard_backup", $filename),
        null,
        notification::NOTIFY_SUCCESS
    );
}

$templatecontext = [
    "filename" => $filename,
    "filesize" => display_size(filesize($filepath)),
    "sesskey" => sesskey(),
    "deleteurl" => $pageurl,
    "cancelurl" => $indexurl,
];

$content = $OUTPUT->render_from_template("koperedashboard_backup/delete", $templatecontext);
layout::page_render($context, $content, true);
