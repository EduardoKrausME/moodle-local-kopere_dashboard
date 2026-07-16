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
 * Central public validation endpoint for Kopere Dashboard plugins.
 *
 * Each installed subplugin may expose a class named `validation` in its root
 * namespace. The class must implement a public static method with this
 * signature:
 *
 *     public static function validate(string $token): bool;
 *
 * The method must return true when it recognises and handles the token. It
 * must return false when the token does not belong to the plugin, allowing
 * the next validation provider to be checked.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.RequireLogin.Missing
use local_kopere_dashboard\api\subplugin_manager;

require_once(__DIR__ . "/../../../config.php");

$token = required_param("t", PARAM_ALPHANUMEXT);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/v/", ["t" => $token]));
$PAGE->set_context(context_system::instance());

foreach (array_keys(subplugin_manager::get_installed_subplugins()) as $pluginname) {
    $classname = "\\koperedashboard_{$pluginname}\\validation";

    if (!class_exists($classname) || !is_callable([$classname, "validate"])) {
        continue;
    }

    if ($classname::validate($token)) {
        exit;
    }
}

throw new moodle_exception("invalidaccess", "error");
