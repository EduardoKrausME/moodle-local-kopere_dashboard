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
 * @created    17/11/2018 12:37
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\output;

use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\menu_util;

/**
 * Class menu
 * @package local_kopere_dashboard\output
 */
class menu {
    /**
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function create_menu() {
        global $DB, $CFG;

        $menu = "<ul class=\"main-menu block_tree list menu-kopere\">";

        $menu .= dashboard_util::add_menu(
            (new menu_util())->set_classname('dashboard')
                ->set_methodname('start')
                ->set_icon('dashboard')
                ->set_name(get_string_kopere('dashboard')));
        $menu .= dashboard_util::add_menu(
            (new menu_util())->set_classname('users')
                ->set_methodname('dashboard')
                ->set_icon('users')
                ->set_name(get_string_kopere('user_title')));
        $menu .= dashboard_util::add_menu(
            (new menu_util())->set_classname('courses')
                ->set_methodname('dashboard')
                ->set_icon('courses')
                ->set_name(get_string_kopere('courses_title')));

        $sql = "SELECT plugin
                  FROM {config_plugins}
                 WHERE plugin LIKE 'local\_kopere\_%'
                   AND plugin !=   'local_kopere_dashboard'
                   AND name LIKE 'version'";
        $plugins = $DB->get_records_sql($sql);
        foreach ($plugins as $plugin) {
            $classname = $plugin->plugin . '\\menu';
            if (class_exists($classname)) {
                $class = new $classname();
                $menu .= $class->show_menu();
            }
        }

        $menu .= dashboard_util::add_menu(
            (new menu_util())->set_classname('reports')
                ->set_methodname('dashboard')
                ->set_icon('report')
                ->set_name(get_string_kopere('reports_title'))
                ->set_submenus(\local_kopere_dashboard\reports::global_menus()));

        if (has_capability('moodle/site:config', \context_system::instance())) {
            $menu .= dashboard_util::add_menu(
                (new menu_util())->set_classname('notifications')
                    ->set_methodname('dashboard')
                    ->set_icon('notifications')
                    ->set_name(get_string_kopere('notification_title')));
        }

        if (has_capability('moodle/site:config', \context_system::instance())) {
            $menu .= dashboard_util::add_menu(
                (new menu_util())->set_classname('webpages')
                    ->set_methodname('dashboard')
                    ->set_icon('webpages')
                    ->set_name(get_string_kopere('webpages_title')));
        }

        $menu .= dashboard_util::add_menu(
            (new menu_util())->set_classname('benchmark')
                ->set_methodname('test')
                ->set_icon('performace')
                ->set_name(get_string_kopere('benchmark_title')));

        if (has_capability('moodle/site:config', \context_system::instance()) && $CFG->dbtype == 'mysqli') {
            $menu .= dashboard_util::add_menu(
                (new menu_util())
                    ->set_classname('backup')
                    ->set_methodname('dashboard')
                    ->set_icon('data')
                    ->set_name(get_string_kopere('backup_title')));
        }

        $menu .= dashboard_util::add_menu(
            (new menu_util())->set_classname('about')
                ->set_methodname('dashboard')
                ->set_icon('about')
                ->set_name(get_string_kopere('about_title')));

        $menu .= "</ul>";

        return $menu;
    }
}