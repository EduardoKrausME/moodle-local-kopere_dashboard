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

include('config/config.php');
if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
    die('Access Denied!');
}
include('include/utils.php');

if (isset($_SESSION['RF']['language_file']) && file_exists($_SESSION['RF']['language_file'])) {
    include($_SESSION['RF']['language_file']);
} else {
    die('Language file is missing!');
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'view':
            if (isset($_GET['type'])) {
                $_SESSION['RF']["view_type"] = $_GET['type'];
            } else {
                die('view type number missing');
            }
            break;
        case 'sort':
            if (isset($_GET['sort_by'])) {
                $_SESSION['RF']["sort_by"] = $_GET['sort_by'];
            }

            if (isset($_GET['descending'])) {
                $_SESSION['RF']["descending"] = $_GET['descending'] === "TRUE";
            }
            break;
        case 'image_size': // not used
            $pos = strpos($_POST['path'], $upload_dir);
            if ($pos !== false) {
                $info = getimagesize(substr_replace($_POST['path'], $current_path, $pos, strlen($upload_dir)));
                echo json_encode($info);
            }
            break;
        case 'save_img':
            $info = pathinfo($_POST['name']);

            if (strpos($_POST['path'], '/') === 0
                || strpos($_POST['path'], '../') !== false
                || strpos($_POST['path'], './') === 0
                || strpos($_POST['url'], 'http://featherfiles.aviary.com/') !== 0
                || $_POST['name'] != fix_filename($_POST['name'], $transliteration)
                || !in_array(strtolower($info['extension']), array('jpg', 'jpeg', 'png'))
            ) {
                die('wrong data');
            }

            $image_data = get_file_by_url($_POST['url']);
            if ($image_data === false) {
                die(lang_Aviary_No_Save);
            }

            file_put_contents($current_path . $_POST['path'] . $_POST['name'], $image_data);

            create_img_gd($current_path . $_POST['path'] . $_POST['name'], $thumbs_base_path . $_POST['path'] . $_POST['name'], 122, 91);
            // TODO something with this function cause its blowing my mind
            new_thumbnails_creation($current_path . $_POST['path'], $current_path . $_POST['path'] . $_POST['name'], $_POST['name'], $current_path, $relative_image_creation, $relative_path_from_current_pos, $relative_image_creation_name_to_prepend, $relative_image_creation_name_to_append, $relative_image_creation_width, $relative_image_creation_height, $fixed_image_creation, $fixed_path_from_filemanager, $fixed_image_creation_name_to_prepend, $fixed_image_creation_to_append, $fixed_image_creation_width, $fixed_image_creation_height);
            break;
        case 'extract':
            if (strpos($_POST['path'], '/') === 0 || strpos($_POST['path'], '../') !== false || strpos($_POST['path'], './') === 0) {
                die('wrong path');
            }

            $path = $current_path . $_POST['path'];
            $info = pathinfo($path);
            $base_folder = $current_path . fix_dirname($_POST['path']) . "/";

            switch ($info['extension']) {
                case "zip":
                    $zip = new ZipArchive;
                    if ($zip->open($path) === true) {
                        // make all the folders
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $OnlyFileName = $zip->getNameIndex($i);
                            $FullFileName = $zip->statIndex($i);
                            if (substr($FullFileName['name'], -1, 1) == "/") {
                                create_folder($base_folder . $FullFileName['name']);
                            }
                        }
                        // unzip into the folders
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $OnlyFileName = $zip->getNameIndex($i);
                            $FullFileName = $zip->statIndex($i);

                            if (!(substr($FullFileName['name'], -1, 1) == "/")) {
                                $fileinfo = pathinfo($OnlyFileName);
                                if (in_array(strtolower($fileinfo['extension']), $ext)) {
                                    copy('zip://' . $path . '#' . $OnlyFileName, $base_folder . $FullFileName['name']);
                                }
                            }
                        }
                        $zip->close();
                    } else {
                        die(lang_Zip_No_Extract);
                    }

                    break;

                case "gz":
                    $p = new PharData($path);
                    $p->decompress(); // creates files.tar

                    break;

                case "tar":
                    // unarchive from the tar
                    $phar = new PharData($path);
                    $phar->decompressFiles();
                    $files = array();
                    check_files_extensions_on_phar($phar, $files, '', $ext);
                    $phar->extractTo($current_path . fix_dirname($_POST['path']) . "/", $files, true);

                    break;

                default:
                    die(lang_Zip_Invalid);
            }
            break;
        case 'copy_cut':
            if ($_POST['sub_action'] != 'copy' && $_POST['sub_action'] != 'cut') {
                die('wrong sub-action');
            }

            if (trim($_POST['path']) == '' || trim($_POST['path_thumb']) == '') {
                die('no path');
            }

            $path = $current_path . $_POST['path'];

            if (is_dir($path)) {
                // can't copy/cut dirs
                if ($copy_cut_dirs === false) {
                    die(sprintf(lang_Copy_Cut_Not_Allowed, ($_POST['sub_action'] == 'copy' ? lcfirst(lang_Copy) : lcfirst(lang_Cut)), lang_Folders));
                }

                // size over limit
                if ($copy_cut_max_size !== false && is_int($copy_cut_max_size)) {
                    if (($copy_cut_max_size * 1024 * 1024) < foldersize($path)) {
                        die(sprintf(lang_Copy_Cut_Size_Limit, ($_POST['sub_action'] == 'copy' ? lcfirst(lang_Copy) : lcfirst(lang_Cut)), $copy_cut_max_size));
                    }
                }

                // file count over limit
                if ($copy_cut_max_count !== false && is_int($copy_cut_max_count)) {
                    if ($copy_cut_max_count < filescount($path)) {
                        die(sprintf(lang_Copy_Cut_Count_Limit, ($_POST['sub_action'] == 'copy' ? lcfirst(lang_Copy) : lcfirst(lang_Cut)), $copy_cut_max_count));
                    }
                }
            } else {
                // can't copy/cut files
                if ($copy_cut_files === false) {
                    die(sprintf(lang_Copy_Cut_Not_Allowed, ($_POST['sub_action'] == 'copy' ? lcfirst(lang_Copy) : lcfirst(lang_Cut)), lang_Files));
                }
            }

            $_SESSION['RF']['clipboard']['path'] = $_POST['path'];
            $_SESSION['RF']['clipboard']['path_thumb'] = $_POST['path_thumb'];
            $_SESSION['RF']['clipboard_action'] = $_POST['sub_action'];
            break;
        case 'clear_clipboard':
            $_SESSION['RF']['clipboard'] = null;
            $_SESSION['RF']['clipboard_action'] = null;
            break;
        default:
            die('no action passed');
    }
} else {
    die('no action passed');
}

?>