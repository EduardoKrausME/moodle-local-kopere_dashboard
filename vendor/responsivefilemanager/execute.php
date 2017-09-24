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
if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
    die('forbiden');
}
include('include/utils.php');
$thumb_pos = strpos(optional_param('path_thumb', '', PARAM_RAW), $thumbs_base_path);
if ($thumb_pos != 0
    || strpos(optional_param('path_thumb', '', PARAM_RAW), '../', strlen($thumbs_base_path) + $thumb_pos) !== false
    || strpos(optional_param('path', '', PARAM_RAW), '/') === 0
    || strpos(optional_param('path', '', PARAM_RAW), '../') !== false
    || strpos(optional_param('path', '', PARAM_RAW), './') === 0
) {
    die('wrong path');
}
$language_file = 'lang/' . $default_language . '.php';
if (optional_param('lang', false, PARAM_RAW) && optional_param('lang', '', PARAM_RAW) != 'undefined'
    && optional_param('lang', '', PARAM_RAW) != '') {
    $path_parts = pathinfo(optional_param('lang', '', PARAM_RAW));
    if (is_readable('lang/' . $path_parts['basename'] . '.php')) {
        $language_file = 'lang/' . $path_parts['basename'] . '.php';
    }
}
require_once($language_file);
$base = $current_path;
$path = $current_path . optional_param('path', '', PARAM_RAW);
$cycle = true;
$max_cycles = 50;
$i = 0;
while ($cycle && $i < $max_cycles) {
    $i++;
    if ($path == $base) {
        $cycle = false;
    }
    if (file_exists($path . "config.php")) {
        require_once($path . "config.php");
        $cycle = false;
    }
    $path = fix_dirname($path) . "/";
    $cycle = false;
}
$path = $current_path . optional_param('path', '', PARAM_RAW);
$path_thumb = optional_param('path_thumb', '', PARAM_RAW);
if (optional_param('name', false, PARAM_RAW)) {
    $name = optional_param('name', '', PARAM_RAW);
    if (strpos($name, '../') !== false) {
        die('wrong name');
    }
}
$info = pathinfo($path);
if (isset($info['extension']) && !(optional_param('action', false, PARAM_RAW) && optional_param('action', '', PARAM_RAW) == 'delete_folder') && !in_array(strtolower($info['extension']), $ext)) {
    die('wrong extension');
}
if (optional_param('action', false, PARAM_RAW)) {
    switch (optional_param('action', '', PARAM_RAW)) {
        case 'delete_file':
            if ($delete_files) {
                unlink($path);
                if (file_exists($path_thumb)) {
                    unlink($path_thumb);
                }
                $info = pathinfo($path);
                if ($relative_image_creation) {
                    foreach ($relative_path_from_current_pos as $k => $path) {
                        if ($path != "" && $path[strlen($path) - 1] != "/") {
                            $path .= "/";
                        }
                        if (file_exists($info['dirname'] . "/" . $path . $relative_image_creation_name_to_prepend[$k] . $info['filename'] . $relative_image_creation_name_to_append[$k] . "." . $info['extension'])) {
                            unlink($info['dirname'] . "/" . $path . $relative_image_creation_name_to_prepend[$k] . $info['filename'] . $relative_image_creation_name_to_append[$k] . "." . $info['extension']);
                        }
                    }
                }
                if ($fixed_image_creation) {
                    foreach ($fixed_path_from_filemanager as $k => $path) {
                        if ($path != "" && $path[strlen($path) - 1] != "/") {
                            $path .= "/";
                        }
                        $base_dir = $path . substr_replace($info['dirname'] . "/", '', 0, strlen($current_path));
                        if (file_exists($base_dir . $fixed_image_creation_name_to_prepend[$k] . $info['filename'] . $fixed_image_creation_to_append[$k] . "." . $info['extension'])) {
                            unlink($base_dir . $fixed_image_creation_name_to_prepend[$k] . $info['filename'] . $fixed_image_creation_to_append[$k] . "." . $info['extension']);
                        }
                    }
                }
            }
            break;
        case 'delete_folder':
            if ($delete_folders) {
                if (is_dir($path_thumb)) {
                    deleteDir($path_thumb);
                }
                if (is_dir($path)) {
                    deleteDir($path);
                    if ($fixed_image_creation) {
                        foreach ($fixed_path_from_filemanager as $k => $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }
                            $base_dir = $paths . substr_replace($path, '', 0, strlen($current_path));
                            if (is_dir($base_dir)) {
                                deleteDir($base_dir);
                            }
                        }
                    }
                }
            }
            break;
        case 'create_folder':
            if ($create_folders) {
                create_folder(fix_path($path, $transliteration), fix_path($path_thumb, $transliteration));
            }
            break;
        case 'rename_folder':
            if ($rename_folders) {
                $name = fix_filename($name, $transliteration);
                $name = str_replace('.', '', $name);
                if (!empty($name)) {
                    if (!rename_folder($path, $name, $transliteration)) {
                        die(lang_Rename_existing_folder);
                    }
                    rename_folder($path_thumb, $name, $transliteration);
                    if ($fixed_image_creation) {
                        foreach ($fixed_path_from_filemanager as $k => $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }
                            $base_dir = $paths . substr_replace($path, '', 0, strlen($current_path));
                            rename_folder($base_dir, $name, $transliteration);
                        }
                    }
                } else {
                    die(lang_Empty_name);
                }
            }
            break;
        case 'rename_file':
            if ($rename_files) {
                $name = fix_filename($name, $transliteration);
                if (!empty($name)) {
                    if (!rename_file($path, $name, $transliteration)) {
                        die(lang_Rename_existing_file);
                    }
                    rename_file($path_thumb, $name, $transliteration);
                    if ($fixed_image_creation) {
                        $info = pathinfo($path);
                        foreach ($fixed_path_from_filemanager as $k => $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }
                            $base_dir = $paths . substr_replace($info['dirname'] . "/", '', 0, strlen($current_path));
                            if (file_exists($base_dir . $fixed_image_creation_name_to_prepend[$k] . $info['filename'] . $fixed_image_creation_to_append[$k] . "." . $info['extension'])) {
                                rename_file($base_dir . $fixed_image_creation_name_to_prepend[$k] . $info['filename'] . $fixed_image_creation_to_append[$k] . "." . $info['extension'], $fixed_image_creation_name_to_prepend[$k] . $name . $fixed_image_creation_to_append[$k], $transliteration);
                            }
                        }
                    }
                } else {
                    die(lang_Empty_name);
                }
            }
            break;
        case 'duplicate_file':
            if ($duplicate_files) {
                $name = fix_filename($name, $transliteration);
                if (!empty($name)) {
                    if (!duplicate_file($path, $name)) {
                        die(lang_Rename_existing_file);
                    }
                    duplicate_file($path_thumb, $name);
                    if ($fixed_image_creation) {
                        $info = pathinfo($path);
                        foreach ($fixed_path_from_filemanager as $k => $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }
                            $base_dir = $paths . substr_replace($info['dirname'] . "/", '', 0, strlen($current_path));
                            if (file_exists($base_dir . $fixed_image_creation_name_to_prepend[$k] . $info['filename'] . $fixed_image_creation_to_append[$k] . "." . $info['extension'])) {
                                duplicate_file($base_dir . $fixed_image_creation_name_to_prepend[$k] . $info['filename'] . $fixed_image_creation_to_append[$k] . "." . $info['extension'], $fixed_image_creation_name_to_prepend[$k] . $name . $fixed_image_creation_to_append[$k]);
                            }
                        }
                    }
                } else {
                    die(lang_Empty_name);
                }
            }
            break;
        case 'paste_clipboard':
            if (!isset($_SESSION['RF']['clipboard_action'], $_SESSION['RF']['clipboard']['path'], $_SESSION['RF']['clipboard']['path_thumb'])
                || $_SESSION['RF']['clipboard_action'] == ''
                || $_SESSION['RF']['clipboard']['path'] == ''
                || $_SESSION['RF']['clipboard']['path_thumb'] == ''
            ) {
                die();
            }
            $action = $_SESSION['RF']['clipboard_action'];
            $data = $_SESSION['RF']['clipboard'];
            $data['path'] = $current_path . $data['path'];
            $pinfo = pathinfo($data['path']);
            // user wants to paste to the same dir. nothing to do here...
            if ($pinfo['dirname'] == rtrim($path, '/')) {
                die();
            }
            // user wants to paste folder to it's own sub folder.. baaaah.
            if (is_dir($data['path']) && strpos($path, $data['path']) !== false) {
                die();
            }
            // something terribly gone wrong
            if ($action != 'copy' && $action != 'cut') {
                die('no action');
            }
            // check for writability
            if (is_really_writable($path) === false || is_really_writable($path_thumb) === false) {
                die($path . '--' . $path_thumb . '--' . lang_Dir_No_Write);
            }
            // check if server disables copy or rename
            if (is_function_callable(($action == 'copy' ? 'copy' : 'rename')) === false) {
                die(sprintf(lang_Function_Disabled, ($action == 'copy' ? lcfirst(lang_Copy) : lcfirst(lang_Cut))));
            }
            if ($action == 'copy') {
                rcopy($data['path'], $path);
                rcopy($data['path_thumb'], $path_thumb);
            } else if ($action == 'cut') {
                rrename($data['path'], $path);
                rrename($data['path_thumb'], $path_thumb);
                // cleanup
                if (is_dir($data['path']) === true) {
                    rrename_after_cleaner($data['path']);
                    rrename_after_cleaner($data['path_thumb']);
                }
            }
            // cleanup
            $_SESSION['RF']['clipboard']['path'] = null;
            $_SESSION['RF']['clipboard']['path_thumb'] = null;
            $_SESSION['RF']['clipboard_action'] = null;
            break;
        default:
            die('wrong action');
    }
}
?>