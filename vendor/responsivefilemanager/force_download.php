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
if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") die('forbiden');
include('include/utils.php');
if (strpos(optional_param('path', '', PARAM_RAW), '/') === 0
    || strpos(optional_param('path', '', PARAM_RAW), '../') !== FALSE
    || strpos(optional_param('path', '', PARAM_RAW), './') === 0
)
    die('wrong path');
if (strpos(optional_param('name', '', PARAM_RAW), '/') !== FALSE) {
    die('wrong path');
}
$path = $current_path . optional_param('path', '', PARAM_RAW);
$name = optional_param('name', '', PARAM_RAW);
$info = pathinfo($name);
if (!in_array(fix_strtolower($info['extension']), $ext)) {
    die('wrong extension');
}
header('Pragma: private');
header('Cache-control: private, must-revalidate');
header("Content-Type: application/octet-stream");
header("Content-Length: " . (string)(filesize($path . $name)));
header('Content-Disposition: attachment; filename="' . ($name) . '"');
readfile($path . $name);
exit;
