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
 * Lib file
 *
 * introduced 23/05/17 17:59
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function local_kopere_dashboard_extends_navigation
 *
 * @param global_navigation $nav
 *
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function local_kopere_dashboard_extends_navigation(global_navigation $nav) {
    local_kopere_dashboard_extend_navigation($nav);
}

/**
 * Function local_kopere_dashboard_extend_navigation
 *
 * @param global_navigation $nav
 *
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function local_kopere_dashboard_extend_navigation(global_navigation $nav) {
    global $CFG;

    require_once(__DIR__ . "/locallib.php");

    $CFG->custommenuitems = trim($CFG->custommenuitems);
    $CFG->custommenuitems = preg_replace('/.*kopere_dashboard.*/', "", $CFG->custommenuitems);
    $CFG->custommenuitems = trim($CFG->custommenuitems);

    if (isloggedin()) {
        if ($CFG->branch > 400 && @get_config("local_kopere_dashboard", "menu")) {
            $context = context_system::instance();
            $hascapability = has_capability('local/kopere_dashboard:view', $context) ||
                has_capability('local/kopere_dashboard:manage', $context);

            if ($hascapability && strpos($CFG->custommenuitems, "kopere_dashboard/view.php") === false) {
                $name = get_string("modulename", "local_kopere_dashboard");
                $link = local_kopere_dashboard_makeurl("dashboard", "start");
                $CFG->custommenuitems = "{$name}|{$link}\n{$CFG->custommenuitems}";
            }
            if (@get_config("local_kopere_dashboard", "menuwebpages")) {
                add_pages_custommenuitems_400();
            }
        } else {
            $context = context_system::instance();
            if (has_capability('local/kopere_dashboard:view', $context) ||
                has_capability('local/kopere_dashboard:manage', $context)) {

                $node = $nav->add(
                    get_string("pluginname", "local_kopere_dashboard"),
                    new moodle_url(local_kopere_dashboard_makeurl("dashboard", "start")),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    "kopere_dashboard",
                    new pix_icon("icon", get_string("pluginname", "local_kopere_dashboard"), "local_kopere_dashboard")
                );

                $node->showinflatnavigation = true;
            }

            require_once(__DIR__ . "/classes/util/node.php");
            \local_kopere_dashboard\util\node::add_admin_code();
        }
    } else {
        if ($CFG->branch > 400 && @get_config("local_kopere_dashboard", "menu")) {
            add_pages_custommenuitems_400();
        }
    }
}

/**
 * Function add_pages_custommenuitems_400
 */
function add_pages_custommenuitems_400() {
    global $CFG;

    $cache = \cache::make("local_kopere_dashboard", "report_getdata_cache");
    if ($cache->has("local_kopere_dashboard_menu")) {
        $CFG->extramenu = $cache->get("local_kopere_dashboard_menu");
    } else {

        try {
            $CFG->extramenu = "";
            local_kopere_dashboard_extend_navigation__get_menus(0, "");
            $cache->set("local_kopere_dashboard_menu", $CFG->extramenu);
        } catch (dml_exception $e) { // phpcs:disable
        }
    }

    $CFG->custommenuitems = "{$CFG->custommenuitems}\n{$CFG->extramenu}";
}

/**
 * Function local_kopere_dashboard_extend_navigation__get_menus
 *
 * @param $menuid
 * @param $prefix
 *
 * @throws dml_exception
 */
function local_kopere_dashboard_extend_navigation__get_menus($menuid, $prefix) {
    global $DB, $CFG;

    $menus = $DB->get_records("local_kopere_dashboard_menu", ["menuid" => $menuid]);

    foreach ($menus as $menu) {
        $where = ["visible" => 1, "menuid" => $menu->id];
        $webpages = $DB->get_records("local_kopere_dashboard_pages", $where, 'pageorder ASC');
        $CFG->extramenu .= "{$prefix} {$menu->title}|{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}\n";
        if ($webpages) {
            /** @var \local_kopere_dashboard\vo\local_kopere_dashboard_pages $webpage */
            foreach ($webpages as $webpage) {
                $link = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpage->link}";
                $CFG->extramenu .= "{$prefix}- {$webpage->title}|{$link}\n";
            }
        }
        local_kopere_dashboard_extend_navigation__get_menus($menu->id, "{$prefix}-");
    }
}

/**
 * Function local_kopere_dashboard_pluginfile
 *
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 *
 * @return bool
 * @throws coding_exception
 */
function local_kopere_dashboard_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    if ($filearea == "overviewfiles") {
        $filename = array_pop($args);
        $filepath = $args ? '/' . implode('/', $args) . '/' : '/';
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'course', $filearea, 0, $filepath, $filename);
        if (!$file || $file->is_directory()) {
            die("ops...");
        }

        send_stored_file($file, 0, 0, $forcedownload, $options);
    }

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, "local_kopere_dashboard", "editor_webpages", $args[0], '/', $args[1])) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
