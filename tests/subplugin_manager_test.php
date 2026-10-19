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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Tests the convention-based Dashboard plugin discovery.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use advanced_testcase;
use core_component;
use local_kopere_dashboard\api\subplugin_manager;

/**
 * Checks native menu contracts and external plugin discovery.
 *
 * @covers \local_kopere_dashboard\api\subplugin_manager
 */
final class subplugin_manager_test extends advanced_testcase {
    /**
     * Every installed native subplugin must define the public static menu contract.
     */
    public function test_native_subplugins_have_menu_definition(): void {
        $this->resetAfterTest();

        foreach (core_component::get_plugin_list("koperedashboard") as $name => $path) {
            $class = "\\koperedashboard_{$name}\\menu";
            $this->assertTrue(class_exists($class), "Missing menu class in koperedashboard_{$name}");
            $this->assertTrue(is_callable([$class, "get_definition"]),
                "Missing public static menu::get_definition() in koperedashboard_{$name}");
        }
    }

    /**
     * Discovered state contains only plugins that implement the menu contract.
     */
    public function test_discovered_plugins_have_callable_menu_definition(): void {
        $this->resetAfterTest();

        foreach (subplugin_manager::get_plugins_state() as $state) {
            $class = "\\{$state["component"]}\\menu";
            $this->assertTrue(is_callable([$class, "get_definition"]), $state["component"]);
        }
    }

    /**
     * External local plugins are included without relying on name prefixes.
     */
    public function test_external_local_plugin_discovery_uses_menu_file(): void {
        $this->resetAfterTest();

        $discovered = array_column(subplugin_manager::get_plugins_state(), "component");
        $files = core_component::get_plugin_list_with_file("local", "classes/menu.php");
        foreach ($files as $name => $path) {
            $component = "local_{$name}";
            if ($component === "local_kopere_dashboard") {
                continue;
            }

            $class = "\\{$component}\\menu";
            if (!is_callable([$class, "get_definition"])) {
                continue;
            }

            $plugininfo = \core_plugin_manager::instance()->get_plugin_info($component);
            if (!$plugininfo || $plugininfo->is_enabled() === false) {
                continue;
            }

            $this->assertContains($component, $discovered);
        }
    }
}
