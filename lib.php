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
 * @created    23/05/17 17:59
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_kopere_dashboard_extends_navigation(global_navigation $nav) {
    local_kopere_dashboard_extend_navigation($nav);
}

/**
 * @param global_navigation $nav
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function local_kopere_dashboard_extend_navigation(global_navigation $nav) {
    global $CFG, $DB;

    if (strpos($_SERVER['REQUEST_URI'], "kopere_dashboard") === false) {
        try {
            $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');

            /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
            foreach ($menus as $menu) {

                $pages = $DB->get_records('kopere_dashboard_webpages', ['menuid' => $menu->id, 'visible' => 1]);

                if ($pages) {
                    $icone = new pix_icon('webpages', $menu->title, 'local_kopere_dashboard');
                    if ($CFG->theme == 'flixcurso') {
                        $icone = new pix_icon('webpages-dark', $menu->title, 'local_kopere_dashboard');
                    }
                    $node = $nav->add(
                        $menu->title,
                        new moodle_url("{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}"),
                        navigation_node::TYPE_CUSTOM,
                        null,
                        "kopere_dashboard-{$menu->id}",
                        $icone
                    );

                    $node->showinflatnavigation = true;

                    /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $page */
                    foreach ($pages as $page) {
                        $icone = new pix_icon('webpages', $page->title, 'local_kopere_dashboard');
                        if ($CFG->theme == 'flixcurso') {
                            $icone = new pix_icon('webpages-dark', $page->title, 'local_kopere_dashboard');
                        }
                        $node->add(
                            $page->title,
                            new moodle_url("{$CFG->wwwroot}/local/kopere_dashboard/?p={$page->link}"),
                            navigation_node::TYPE_CUSTOM,
                            null,
                            "kopere_dashboard-page-{$page->id}",
                            $icone
                        );
                    }
                }
            }
        } catch (Exception $e) {
            // Se der problema, não precisa fazer nada.
        }
    }

    $CFG->custommenuitems = trim($CFG->custommenuitems);
    $CFG->custommenuitems = preg_replace('/.*kopere_dashboard.*/', '', $CFG->custommenuitems);
    $CFG->custommenuitems = trim($CFG->custommenuitems);
    if (isloggedin()) {
        if ($CFG->branch > 400 && @$CFG->kopere_dashboard_menu) {
            $context = context_system::instance();
            $hascapability = has_capability('local/kopere_dashboard:view', $context) ||
                has_capability('local/kopere_dashboard:manage', $context);

            if ($hascapability && strpos($CFG->custommenuitems, "kopere_dashboard/open.php") === false) {
                $name = get_string('modulename', 'local_kopere_dashboard');
                $link = local_kopere_dashboard_makeurl("dashboard", "start");
                $CFG->custommenuitems = "{$name}|{$link}\n{$CFG->custommenuitems}";
            }
            if (@$CFG->kopere_dashboard_menuwebpages) {
                add_pages_custommenuitems_400();
            }
        } else {
            $context = context_system::instance();
            if (has_capability('local/kopere_dashboard:view', $context) ||
                has_capability('local/kopere_dashboard:manage', $context)) {

                $node = $nav->add(
                    get_string('pluginname', 'local_kopere_dashboard'),
                    new moodle_url(local_kopere_dashboard_makeurl("dashboard", "start")),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    'kopere_dashboard',
                    new pix_icon('icon', get_string('pluginname', 'local_kopere_dashboard'), 'local_kopere_dashboard')
                );

                $node->showinflatnavigation = true;
            }

            require_once(__DIR__ . "/classes/util/node.php");
            \local_kopere_dashboard\util\node::add_admin_code();
        }
    } else {
        if ($CFG->branch > 400 && @$CFG->kopere_dashboard_menu) {
            add_pages_custommenuitems_400();
        }
    }
}

/**
 * @throws dml_exception
 */
function add_pages_custommenuitems_400() {
    global $DB, $CFG;

    $extramenu = "";
    $menus = $DB->get_records('kopere_dashboard_menu');
    foreach ($menus as $menu) {

        $sql = "SELECT * FROM {kopere_dashboard_webpages} WHERE visible = 1 AND menuid = {$menu->id} ORDER BY pageorder ASC";
        $webpages = $DB->get_records_sql($sql);

        if ($webpages) {
            $extramenu .= "{$menu->title}|{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}\n";

            /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpage */
            foreach ($webpages as $webpage) {
                $link = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpage->link}";
                $extramenu .= " - {$webpage->title}|{$link}\n";
            }
        }
    }
    $CFG->custommenuitems = "{$CFG->custommenuitems}\n{$extramenu}";
}

/**
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 * @return bool
 * @throws coding_exception
 */
function local_kopere_dashboard_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, 'local_kopere_dashboard', 'webpage_image', $args[0], '/', $args[1])) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
