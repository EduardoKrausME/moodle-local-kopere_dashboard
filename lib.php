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

defined('MOODLE_INTERNAL') || die();

function local_kopere_dashboard_extends_navigation(global_navigation $nav) {
    local_kopere_dashboard_extend_navigation($nav);
}

function local_kopere_dashboard_extend_navigation(global_navigation $nav) {
    global $CFG, $PAGE, $USER, $DB;

    try {
        $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');

        /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
        foreach ($menus as $menu) {

            $pages = $DB->get_records('kopere_dashboard_webpages', ['menuid' => $menu->id, 'visible' => 1]);

            if ($pages) {
                $node = $nav->add(
                    $menu->title,
                    new moodle_url($CFG->wwwroot . '/local/kopere_dashboard/?menu=' . $menu->link),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    'kopere_dashboard-' . $menu->id,
                    new pix_icon('webpages', $menu->title, 'local_kopere_dashboard')
                );

                $node->showinflatnavigation = true;

                /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $page */
                foreach ($pages as $page) {
                    $node->add(
                        $page->title,
                        new moodle_url($CFG->wwwroot . '/local/kopere_dashboard/?p=' . $page->link),
                        navigation_node::TYPE_CUSTOM,
                        null,
                        'kopere_dashboard-page-' . $page->id,
                        new pix_icon('webpages', $page->title, 'local_kopere_dashboard')
                    );
                }
            }
        }
    } catch (Exception $e) {
        // Se der problema, não precisa fazer nada
    }

    if (isloggedin()) {
        $context = context_system::instance();
        if (has_capability('local/kopere_dashboard:view', $context) ||
            has_capability('local/kopere_dashboard:manage', $context)) {

            $node = $nav->add(
                get_string('pluginname', 'local_kopere_dashboard'),
                new moodle_url($CFG->wwwroot . '/local/kopere_dashboard/open.php?classname=dashboard&method=start'),
                navigation_node::TYPE_CUSTOM,
                null,
                'kopere_dashboard',
                new pix_icon('icon', get_string('pluginname', 'local_kopere_dashboard'), 'local_kopere_dashboard')
            );

            $node->showinflatnavigation = true;
        }

        require_once __DIR__ . "/classes/util/node.php";
        if (\local_kopere_dashboard\util\node::is_enables()) {
            $PAGE->requires->jquery();

            $PAGE->requires->js(new moodle_url(\local_kopere_dashboard\util\node::geturl_socketio()));
            $PAGE->requires->js('/local/kopere_dashboard/node/app-v2.js');

            $userid = intval($USER->id);
            $fullname = fullname($USER);
            $servertime = time();
            $urlnode = \local_kopere_dashboard\util\node::base_url();

            $PAGE->requires->js_init_code("startServer( {$userid}, \"{$fullname}\", {$servertime}, \"{$urlnode}\" )");
        }
    }
}
