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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require '../../../../config.php';
include('config/config.php');
$info = pathinfo($_FILES['file']['name']);
$file_filename = \local_kopere_dashboard\util\Html::retiraCaracteresNaoASCII($info['filename']);
$file_filename = str_replace(' ', '_', $file_filename);
$file_extension = $info['extension'];
$file_name = $file_filename . '.' . $file_extension;
if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
    die('forbiden');
}
include('include/utils.php');
$storeFolder = optional_param('path', '', PARAM_RAW);
$storeFolderThumb = optional_param('path_thumb', '', PARAM_RAW);
$path_pos = strpos($storeFolder, $current_path);
$thumb_pos = strpos(optional_param('path_thumb', '', PARAM_RAW), $thumbs_base_path);
if ($path_pos !== 0
    || $thumb_pos !== 0
    || strpos($storeFolderThumb, '../', strlen($thumbs_base_path)) !== FALSE
    || strpos($storeFolderThumb, './', strlen($thumbs_base_path)) !== FALSE
    || strpos($storeFolder, '../', strlen($current_path)) !== FALSE
    || strpos($storeFolder, './', strlen($current_path)) !== FALSE
)
    die('wrong path');
$path = $storeFolder;
$cycle = true;
$max_cycles = 50;
$i = 0;
while ($cycle && $i < $max_cycles) {
    $i++;
    if ($path == $current_path) $cycle = false;
    if (file_exists($path . "config.php")) {
        require_once($path . "config.php");
        $cycle = false;
    }
    $path = fix_dirname($path) . '/';
}
if (!empty($_FILES)) {
    $info = pathinfo($file_name);
    if (in_array(fix_strtolower($info['extension']), $ext)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $targetPath = $storeFolder;
        $targetPathThumb = $storeFolderThumb;
        $file_name = fix_filename($file_name, $transliteration);
        if (file_exists($targetPath . $file_name)) {
            $i = 1;
            $info = pathinfo($file_name);
            while (file_exists($targetPath . $info['filename'] . "_" . $i . "." . $info['extension'])) {
                $i++;
            }
            $file_name = $info['filename'] . "_" . $i . "." . $info['extension'];
        }
        $targetFile = $targetPath . $file_name;
        $targetFileThumb = $targetPathThumb . $file_name;
        if (in_array(fix_strtolower($info['extension']), $ext_img)) $is_img = true;
        else $is_img = false;
        move_uploaded_file($tempFile, $targetFile);
        chmod($targetFile, 0755);
        if ($is_img) {
            $memory_error = false;
            if (!create_img_gd($targetFile, $targetFileThumb, 122, 91)) {
                $memory_error = false;
            } else {
                if (!new_thumbnails_creation($targetPath, $targetFile, $file_name, $current_path, $relative_image_creation, $relative_path_from_current_pos, $relative_image_creation_name_to_prepend, $relative_image_creation_name_to_append, $relative_image_creation_width, $relative_image_creation_height, $fixed_image_creation, $fixed_path_from_filemanager, $fixed_image_creation_name_to_prepend, $fixed_image_creation_to_append, $fixed_image_creation_width, $fixed_image_creation_height)) {
                    $memory_error = false;
                } else {
                    $imginfo = getimagesize($targetFile);
                    $srcWidth = $imginfo[0];
                    $srcHeight = $imginfo[1];
                    if ($image_resizing) {
                        if ($image_resizing_width == 0) {
                            if ($image_resizing_height == 0) {
                                $image_resizing_width = $srcWidth;
                                $image_resizing_height = $srcHeight;
                            } else {
                                $image_resizing_width = $image_resizing_height * $srcWidth / $srcHeight;
                            }
                        } else if ($image_resizing_height == 0) {
                            $image_resizing_height = $image_resizing_width * $srcHeight / $srcWidth;
                        }
                        $srcWidth = $image_resizing_width;
                        $srcHeight = $image_resizing_height;
                        create_img_gd($targetFile, $targetFile, $image_resizing_width, $image_resizing_height);
                    }
                    // max resizing limit control
                    $resize = false;
                    if ($image_max_width != 0 && $srcWidth > $image_max_width) {
                        $resize = true;
                        $srcHeight = $image_max_width * $srcHeight / $srcWidth;
                        $srcWidth = $image_max_width;
                    }
                    if ($image_max_height != 0 && $srcHeight > $image_max_height) {
                        $resize = true;
                        $srcWidth = $image_max_height * $srcWidth / $srcHeight;
                        $srcHeight = $image_max_height;
                    }
                    if ($resize) {
                        create_img_gd($targetFile, $targetFile, $srcWidth, $srcHeight);
                    }
                }
            }
            if ($memory_error) {
                //error
                unlink($targetFile);
                header('HTTP/1.1 406 Not enought Memory', true, 406);
                exit();
            }
        }
    } else {
        header('HTTP/1.1 406 file not permitted', true, 406);
        exit();
    }
} else {
    header('HTTP/1.1 405 Bad Request', true, 405);
    exit();
}
if (optional_param('submit', false, PARAM_RAW)) {
    $query = http_build_query(array(
        'type' => optional_param('type', '', PARAM_RAW),
        'lang' => optional_param('lang', '', PARAM_RAW),
        'popup' => optional_param('popup', '', PARAM_RAW),
        'field_id' => optional_param('field_id', '', PARAM_RAW),
        'fldr' => optional_param('fldr', '', PARAM_RAW),
        'folderSaveFile' => optional_param('folderSaveFile', '', PARAM_RAW),
    ));
    header("location: dialog.php?" . $query);
}
