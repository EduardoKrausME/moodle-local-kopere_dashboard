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

    $CFG->custommenuitems = preg_replace('/Kopere Dashboard|.*?start\n?/', '', $CFG->custommenuitems);
    if (isloggedin()) {
        if ($CFG->branch > 400 && @$CFG->kopere_dashboard_menu) {
            $hassiteconfig = has_capability('moodle/site:config', context_system::instance());

            if ($hassiteconfig && strpos($CFG->custommenuitems, "kopere_dashboard") === false) {
                $name = get_string('modulename', 'local_kopere_dashboard');
                $link = "/local/kopere_dashboard/open.php?classname=dashboard&method=start";
                $CFG->custommenuitems = "{$name}|{$link}\n{$CFG->custommenuitems}";
            }
        } else {
            $context = context_system::instance();
            if (has_capability('local/kopere_dashboard:view', $context) ||
                has_capability('local/kopere_dashboard:manage', $context)) {

                $node = $nav->add(
                    get_string('pluginname', 'local_kopere_dashboard'),
                    new moodle_url("{$CFG->wwwroot}/local/kopere_dashboard/open.php?classname=dashboard&method=start"),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    'kopere_dashboard',
                    new pix_icon('icon', get_string('pluginname', 'local_kopere_dashboard'), 'local_kopere_dashboard')
                );

                $node->showinflatnavigation = true;
            }

            require_once __DIR__ . "/classes/util/node.php";
            \local_kopere_dashboard\util\node::add_admin_code();
        }
    } else {
        if ($CFG->branch > 400 && @$CFG->kopere_dashboard_menu) {

            $extraMenu = "";
            $menus = $DB->get_records('kopere_dashboard_menu');
            foreach ($menus as $menu) {
                //$link = "/local/kopere_dashboard/?menu={$menu->link}";
                $extraMenu .= "{$menu->title}\n";

                $sql = "SELECT * FROM {kopere_dashboard_webpages} WHERE visible = 1 ORDER BY pageorder ASC";
                $webpagess = $DB->get_records_sql($sql);

                /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpages */
                foreach ($webpagess as $webpages) {
                    $link = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}";
                    $extraMenu .= " - {$webpages->title}|{$link}\n";
                }
            }
            $CFG->custommenuitems = "{$CFG->custommenuitems}\n{$extraMenu}";
        }
    }
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
