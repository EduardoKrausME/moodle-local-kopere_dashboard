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


defined('MOODLE_INTERNAL') || die();
ob_start();

require('../../config.php');

$src = required_param('src', PARAM_TEXT);

if (strpos($src, '..')) {
    die('Not Found!');
}

$imageloaded = $CFG->dataroot . '/kopere/dashboard/' . $src;
$extension = pathinfo($imageloaded, PATHINFO_EXTENSION);
$basename = pathinfo($imageloaded, PATHINFO_BASENAME);
$lifetime = 60 * 60 * 24 * 360;
$isimage = false;

ob_clean();
ob_end_flush();
session_write_close();

switch ($extension) {
    case 'jpg':
    case 'jpeg':
        header('Content-Type: image/jpeg');
        $isimage = true;
        break;
    case 'png':
    case 'gif':
    case 'svg':
    case 'bmp':
    case 'tiff':
        header('Content-Type: image/' . $extension);
        $isimage = true;
        break;

    default:
        header('Content-Type: application/octet-stream');
        break;
}

if ($isimage) {
    header('Content-disposition: inline; filename="' . $basename . '"');
    header('Content-disposition: inline; filename="' . $basename . '"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($imageloaded)) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime . ', no-transform');
} else {
    header('Content-Transfer-Encoding: Binary');
    header('Content-disposition: attachment; filename="' . $basename . '"');
}

readfile($imageloaded);

