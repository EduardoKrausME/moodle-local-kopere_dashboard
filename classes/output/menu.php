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
        global $DB;

        $menu = "<ul class=\"main-menu block_tree list menu-kopere\">";

        $menu .= dashboard_util::add_menu('dashboard', 'start', 'dashboard', get_string_kopere('dashboard'));
        $menu .= dashboard_util::add_menu('users', 'dashboard', 'users', get_string_kopere('user_title'));
        $menu .= dashboard_util::add_menu('courses', 'dashboard', 'courses', get_string_kopere('courses_title'));

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

        $menu .= dashboard_util::add_menu('reports', 'dashboard', 'report', get_string_kopere('reports_title'),
            \local_kopere_dashboard\reports::global_menus());

        if (has_capability('moodle/site:config', \context_system::instance())) {
            $menu .= dashboard_util::add_menu('notifications', 'dashboard', 'notifications',
                get_string_kopere('notification_title'));
        }

        if (has_capability('moodle/site:config', \context_system::instance())) {
            $menu .= dashboard_util::add_menu('webpages', 'dashboard', 'webpages',
                get_string_kopere('webpages_title'));
        }

        $menu .= dashboard_util::add_menu('benchmark', 'test', 'performace', get_string_kopere('benchmark_title'));

        $menu .= dashboard_util::add_menu('about', 'dashboard', 'about', get_string_kopere('about_title'));

        $menu .= "</ul>";

        return $menu;
    }
}