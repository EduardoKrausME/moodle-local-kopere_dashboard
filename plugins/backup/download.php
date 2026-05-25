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
 * download.php
 *
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_backup\backup_manager;
use core\session\manager;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/backup:manage", $context);

$filename = required_param("file", PARAM_FILE);
$filepath = backup_manager::get_backup_file_path($filename);
$filesize = filesize($filepath);

if ($filesize === false) {
    throw new moodle_exception("filenotfound", "koperedashboard_backup", "", $filename);
}

manager::write_close();
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header("Content-Length: {$filesize}");
header("Cache-Control: private, max-age=0, must-revalidate");
header("Pragma: public");
readfile($filepath);
exit;
