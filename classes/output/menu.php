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
 * menu file
 *
 * introduced  17/11/2018 12:37
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\output;

use local_kopere_dashboard\reports;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\menu_util;
use local_kopere_dashboard\util\submenu_util;

/**
 * Class menu
 *
 * @package local_kopere_dashboard\output
 */
class menu {

    /**
     * Function create_menu
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function create_menu() {
        global $DB, $CFG;

        $isadmin = has_capability("local/kopere_dashboard:manage", \context_system::instance());

        echo "<ul class='kopere-main-menu block_tree list menu-kopere'>";

        echo dashboard_util::add_menu(
            (new menu_util())->set_classname("dashboard")
                ->set_methodname("start")
                ->set_icon("dashboard")
                ->set_name(get_string("dashboard", "local_kopere_dashboard")));

        $submenus = [
            (new submenu_util())
                ->set_classname("useronline")
                ->set_methodname("dashboard")
                ->set_title(get_string("useronline_title", "local_kopere_dashboard"))
                ->set_icon("users-online"),
        ];
        if ($DB->get_dbfamily() == "mysql") {
            $submenus[] = (new submenu_util())
                ->set_classname("useraccess")
                ->set_methodname("dashboard")
                ->set_title(get_string("useraccess_title", "local_kopere_dashboard"))
                ->set_icon("users-access");
        }
        echo dashboard_util::add_menu(
            (new menu_util())
                ->set_classname("users")
                ->set_methodname("dashboard")
                ->set_icon("users")
                ->set_name(get_string("user_title", "local_kopere_dashboard"))
                ->set_submenus($submenus));

        echo dashboard_util::add_menu(
            (new menu_util())
                ->set_classname("courses")
                ->set_methodname("dashboard")
                ->set_icon("courses")
                ->set_name(get_string("courses_title", "local_kopere_dashboard")));

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
                echo $class->show_menu();
            }
        }

        if (!file_exists("{$CFG->dirroot}/local/kopere_bi/version.php")) {
            echo dashboard_util::add_menu(
                (new menu_util())
                    ->set_classname("reports")
                    ->set_methodname("dashboard")
                    ->set_icon("report")
                    ->set_name(get_string("reports_title", "local_kopere_dashboard"))
                    ->set_submenus(reports::global_menus())
            );
        }

        if ($isadmin) {

            echo dashboard_util::add_menu(
                (new menu_util())
                    ->set_classname("notifications")
                    ->set_methodname("dashboard")
                    ->set_icon("notifications")
                    ->set_name(get_string("notification_title", "local_kopere_dashboard"))
                    ->set_submenus([
                        (new submenu_util())
                            ->set_classname("notifications")
                            ->set_methodname("dashboard")
                            ->set_title(get_string("notification_title", "local_kopere_dashboard"))
                            ->set_icon("notifications"),
                    ]));

            echo dashboard_util::add_menu(
                (new menu_util())
                    ->set_classname("webpages")
                    ->set_methodname("dashboard")
                    ->set_icon("webpages")
                    ->set_name(get_string("webpages_title", "local_kopere_dashboard"))
                    ->set_submenus([
                        (new submenu_util())
                            ->set_classname("webpages")
                            ->set_methodname("dashboard")
                            ->set_title(get_string("webpages_title", "local_kopere_dashboard"))
                            ->set_icon("webpages"),
                        (new submenu_util())
                            ->set_classname("webpages")
                            ->set_methodname("settings")
                            ->set_title(get_string("settings", "local_kopere_dashboard"))
                            ->set_icon("settings"),
                    ]));
        }

        echo dashboard_util::add_menu(
            (new menu_util())
                ->set_classname("benchmark")
                ->set_methodname("test")
                ->set_icon("performace")
                ->set_name(get_string("benchmark_title", "local_kopere_dashboard")));

        if ($isadmin && $DB->get_dbfamily() == "mysql") {
            echo dashboard_util::add_menu(
                (new menu_util())
                    ->set_classname("backup")
                    ->set_methodname("dashboard")
                    ->set_icon("data")
                    ->set_name(get_string("backup_title", "local_kopere_dashboard")));
        }

        echo dashboard_util::add_menu(
            (new menu_util())
                ->set_classname("about")
                ->set_methodname("dashboard")
                ->set_icon("about")
                ->set_name(get_string("about_title", "local_kopere_dashboard")));
        echo "</ul>";
    }
}
