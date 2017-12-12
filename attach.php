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
 * @created    28/05/17 02:01
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

ob_start();

require('../../config.php');

$src = required_param('src', PARAM_TEXT);

if (strpos($src, '..')) {
    die('Not Found!');
}


$image_loaded = $CFG->dataroot . '/kopere/dashboard/' . $src;
$extension = pathinfo($image_loaded, PATHINFO_EXTENSION);
$basename = pathinfo($image_loaded, PATHINFO_BASENAME);
$lifetime = 60 * 60 * 24 * 360;
$is_image = false;

ob_clean();
ob_end_flush();
session_write_close();

switch ($extension) {
    case 'jpg':
    case 'jpeg':
        header('Content-Type: image/jpeg');
        $is_image = true;
        break;
    case 'png':
    case 'gif':
    case 'svg':
    case 'bmp':
    case 'tiff':
        header('Content-Type: image/' . $extension);
        $is_image = true;
        break;

    default:
        header('Content-Type: application/octet-stream');
        break;
}

if ($is_image) {
    header('Content-disposition: inline; filename="' . $basename . '"');
    header('Content-disposition: inline; filename="' . $basename . '"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image_loaded)) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime . ', no-transform');
} else {
    header('Content-Transfer-Encoding: Binary');
    header('Content-disposition: attachment; filename="' . $basename . '"');
}


readfile($image_loaded);

