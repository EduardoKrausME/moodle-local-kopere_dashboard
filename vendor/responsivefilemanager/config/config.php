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

ob_start();
mb_internal_encoding('UTF-8');
ob_clean();

$subFolder = optional_param('folderSaveFile', '', PARAM_TEXT);

$root_dir = $CFG->dataroot . '/kopere/dashboard/';
$root_href = $CFG->wwwroot . '/local/kopere_dashboard/attach.php?src=';
$base_url = $CFG->wwwroot . '/local/kopere_dashboard/attach.php?src=';
$upload_dir = 'upload/' . $subFolder;
$current_path = $CFG->dataroot . '/kopere/dashboard/upload/' . $subFolder;
$thumbs_base_path = $CFG->dataroot . '/kopere/dashboard/upload_thumb/' . $subFolder;
if (!file_exists($thumbs_base_path)) {
    mkdir($thumbs_base_path, 0777, true);
}
if (!file_exists($current_path)) {
    mkdir($current_path, 0777, true);
}
define('USE_ACCESS_KEYS', false);
$access_keys = array('myPrivateKey', 'someoneElseKey');
$MaxSizeUpload = 200;
if ((int)(ini_get('post_max_size')) < $MaxSizeUpload) {
    $MaxSizeUpload = (int)(ini_get('post_max_size'));
}
$default_language = 'pt_BR'; // default language file name
$icon_theme = 'ico';   // ico or ico_dark you can cusatomize just putting a folder inside filemanager/img
$show_folder_size = true;    // Show or not show folder size in list view feature in filemanager (is possible, if there is a large folder, to greatly increase the calculations)
$show_sorting_bar = true;    // Show or not show sorting feature in filemanager
$loading_bar = true;    // Show or not show loading bar
$transliteration = false;   // active or deactive the transliteration (mean convert all strange characters in A..Za..z0..9 characters)
$image_max_width = 0;
$image_max_height = 0;
$image_resizing = false;
$image_resizing_width = 0;
$image_resizing_height = 0;
$default_view = 0;
$ellipsis_title_after_first_row = true;
$delete_files = true;
$create_folders = true;
$delete_folders = true;
$upload_files = true;
$rename_files = true;
$rename_folders = true;
$duplicate_files = false;
$copy_cut_files = true; // for copy/cut files
$copy_cut_dirs = true; // for copy/cut directories
$copy_cut_max_size = 100;
$copy_cut_max_count = 200;
$ext_img = array(
    'jpg',
    'jpeg',
    'png',
    'gif',
    'bmp',
    'tiff',
    'svg');
$ext_file = array(
    'doc',
    'docx',
    'rtf',
    'pdf',
    'xls',
    'xlsx',
    'txt',
    'csv',
    'html',
    'xhtml',
    'psd',
    'sql',
    'log',
    'fla',
    'xml',
    'ade',
    'adp',
    'mdb',
    'accdb',
    'ppt',
    'pptx',
    'odt',
    'ots',
    'ott',
    'odb',
    'odg',
    'otp',
    'otg',
    'odf',
    'ods',
    'odp',
    'css',
    'ai'
);
$ext_misc = array(
    'zip',
    'rar',
    'gz',
    'tar',
    'iso',
    'dmg'
);
$ext = array_merge($ext_img, $ext_file, $ext_misc);
/******************
 * AVIARY config
 *******************/
$aviary_active = true;
$aviary_key = 'dvh8qudbp6yx2bnp';
$aviary_secret = 'm6xaym5q42rpw433';
$aviary_version = 3;
$aviary_language = 'en';

$file_number_limit_js = 500;

$hidden_folders = array();
$hidden_files = array('config.php');

$fixed_image_creation = false;
$fixed_path_from_filemanager = array(
    '../test/',
    '../test1/'
);
$fixed_image_creation_name_to_prepend = array('', 'test_');
$fixed_image_creation_to_append = array('_test', '');
$fixed_image_creation_width = array(300, 400);
$fixed_image_creation_height = array(200, '');

$relative_image_creation = false;
$relative_path_from_current_pos = array('thumb/',
    'thumb/'
);
$relative_image_creation_name_to_prepend = array('', 'test_');
$relative_image_creation_name_to_append = array('_test', '');
$relative_image_creation_width = array(300, 400);
$relative_image_creation_height = array(200, '');
