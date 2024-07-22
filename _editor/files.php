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
 * Editor.
 *
 * @package     theme_boost_magnific
 * @copyright   2024 Eduardo kraus (http://eduardokraus.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once('../lib.php');

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$chave = required_param('chave', PARAM_TEXT);

$component = 'theme_boost_magnific';
$contextid = $context->id;
$adminid = get_admin()->id;
$filearea = "editor_{$chave}";

if (isset($_FILES['file']['name'])) {

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'])) {
        $fs = get_file_storage();
        $filerecord = (object)[
            "component" => $component,
            "contextid" => $contextid,
            "userid" => $adminid,
            "filearea" => $filearea,
            "filepath" => '/',
            "itemid" => time() - 1714787612,
            "filename" => $_FILES['file']['name'],
        ];
        $fs->create_file_from_pathname($filerecord, $_FILES['file']['tmp_name']);

        $url = moodle_url::make_file_url(
            "$CFG->wwwroot/pluginfile.php",
            "/{$contextid}/theme_boost_magnific/{$filerecord->filearea}/{$filerecord->itemid}{$filerecord->filepath}{$filerecord->filename}");

        echo json_encode([
            "name" => $_FILES['file']['name'],
            "type" => "file",
            "path" => $url->out(false),
            "size" => filesize($_FILES['file']['tmp_name']),
        ]);

        die();
    } else {
        // header($_SERVER['SERVER_PROTOCOL'] . ' 404', true, 500);
        die("File type {$extension} not allowed!");
    }
}

$fs = get_file_storage();
$files = $fs->get_area_files($contextid, $component, $filearea, false, $sort = "filename", false);

$items = [];
/** @var stored_file $file */
foreach ($files as $file) {
    $url = moodle_url::make_file_url(
        "$CFG->wwwroot/pluginfile.php",
        "/{$contextid}/theme_boost_magnific/{$file->get_filearea()}/{$file->get_itemid()}{$file->get_filepath()}{$file->get_filename()}");
    $items[] = [
        "name" => $file->get_filename(),
        "type" => "file",
        "path" => $url->out(false),
        "size" => $file->get_filesize(),
        "info" => "Upload file",
    ];
}

$sql = "SELECT * FROM {course}";
$courses = $DB->get_records_sql($sql);

foreach ($courses as $course) {
    $courseobj = new core_course_list_element($course);
    foreach ($courseobj->get_course_overviewfiles() as $file) {
        $isimage = $file->is_valid_image();
        if ($isimage) {
            $courseimage = file_encode_url("{$CFG->wwwroot}/pluginfile.php",
                "/{$file->get_contextid()}/{$file->get_component()}/" .
                "{$file->get_filearea()}{$file->get_filepath()}{$file->get_filename()}", !$isimage);

            $items[] = [
                "name" => $file->get_filename(),
                "type" => "file",
                "path" => $courseimage,
                "size" => $file->get_filesize(),
                "info" => "Course file",
            ];
        }
    }
}

header("Content-Type: application/json");
echo json_encode([
    "name" => "",
    "type" => "folder",
    "path" => "",
    "items" => $items
]);
die();
