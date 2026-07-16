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
 * Upgrade steps for the Attestations subplugin.
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the Attestations subplugin.
 *
 * @param int $oldversion
 * @return bool
 * @throws \dml_exception
 * @throws \downgrade_exception
 * @throws \moodle_exception
 * @throws \upgrade_exception
 */
function xmldb_koperedashboard_attest_upgrade(int $oldversion): bool {
    if ($oldversion < 2026071601) {
        $templates = [
            "studentcard_front_mustache" => "studentcard_front.html",
            "studentcard_back_mustache" => "studentcard_back.html",
        ];

        foreach ($templates as $configname => $filename) {
            $currenttemplate = get_config("koperedashboard_attest", $configname);
            $templatefile = __DIR__ . "/files/{$filename}";

            $isempty = $currenttemplate === false || trim((string) $currenttemplate) === "";
            if ($isempty && is_readable($templatefile)) {
                set_config(
                    $configname,
                    file_get_contents($templatefile),
                    "koperedashboard_attest"
                );
            }
        }

        upgrade_plugin_savepoint(true, 2026071601, "koperedashboard", "attest");
    }

    return true;
}
