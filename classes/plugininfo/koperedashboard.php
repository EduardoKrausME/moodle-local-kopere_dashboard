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
 * kopere_dashboard.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\plugininfo;

use admin_setting_configcheckbox;
use admin_settingpage;
use context_system;
use core\plugininfo\base;
use core_component;
use local_kopere_dashboard\api\subplugin_manager;
use Exception;
use moodle_url;
use part_of_admin_tree;
use progress_trace;

/**
 * Class kopere_dashboard
 */
class koperedashboard extends base {

    /**
     * Return enabled status for all kopere_dashboard subplugins.
     * Keys are raw plugin names (e.g. 'html', 'course'); values are booleans.
     *
     * @return array<string,bool>
     */
    public static function get_enabled_plugins() {
        return subplugin_manager::get_installed_subplugins(true);
    }

    /**
     * Whether the subplugin can be uninstalled via admin UI.
     *
     * @return true
     */
    public function is_uninstall_allowed() {
        return true;
    }

    /**
     * Function can_uninstall_plugin
     *
     * @return true
     */
    public function can_uninstall_plugin() {
        return true;
    }

    /**
     * Return settings section name used in the admin tree (optional).
     *
     * @return string
     */
    public function get_settings_section_name() {
        return "koperedashboard_{$this->name}_settings";
    }

    /**
     * Add a simple Enable/Disable checkbox for this subplugin in the admin tree.
     *
     * @param part_of_admin_tree $adminroot
     * @param string $parentnodename
     * @param bool $hassiteconfig
     * @return void
     * @throws Exception
     */
    public function load_settings(part_of_admin_tree $adminroot, $parentnodename, $hassiteconfig) {
        if (!$hassiteconfig) {
            return;
        }

        $section = $this->get_settings_section_name();
        $page = new admin_settingpage($section, $this->displayname, "moodle/site:config");

        $component = "koperedashboard_{$this->name}";
        $page->add(
            new admin_setting_configcheckbox(
                "{$component}/enabled",
                get_string("pluginname", $component),     // Subplugin's own display name.
                get_string("enableplugin", "admin"),      // Core admin string.
                1
            )
        );

        $adminroot->add($parentnodename, $page);
    }

    /**
     * Uninstall hook: remove items that belong to this subplugin and clear its config.
     * Does not drop core tables; only cleans items and files tied to this subplugin.
     *
     * @param progress_trace $progress
     * @return bool
     * @throws Exception
     */
    public function uninstall(progress_trace $progress) {
        $component = "koperedashboard_{$this->name}";

        // Clear all component config (including 'enabled').
        unset_all_config_for_plugin($component);
        $progress->output("Cleared config for " . $component, 1);

        return true;
    }

    /**
     * Return URL used for management of plugins of this type.
     *
     * @return moodle_url
     */
    public static function get_manage_url() {
        return new moodle_url("/local/kopere_dashboard/admin_plugins.php");
    }
}
