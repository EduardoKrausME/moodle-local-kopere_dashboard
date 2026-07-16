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
 * Install file.
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function xmldb_koperedashboard_attest_install() {
    set_config("sortorder", 101, "koperedashboard_attest");

    $templates = [
        "studentcard_front_mustache" => "studentcard_front.mustache",
        "studentcard_back_mustache" => "studentcard_back.mustache",
    ];

    foreach ($templates as $configname => $filename) {
        $templatefile = __DIR__ . "/files/{$filename}";
        if (is_readable($templatefile)) {
            set_config(
                $configname,
                file_get_contents($templatefile),
                "koperedashboard_attest"
            );
        }
    }

    return true;
}
