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
 * lib.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function local_kopere_dashboard_extend_navigation
 *
 * @param global_navigation $nav
 */
function local_kopere_dashboard_extend_navigation(global_navigation $nav) {
    global $PAGE, $CFG;

    $CFG->custommenuitems = preg_replace('/.*\/local\/kopere_dashboard.*/', '', $CFG->custommenuitems);

    if (!isloggedin()) {
        return;
    }

    try {
        $mynode = $PAGE->navigation->find("myprofile", navigation_node::TYPE_ROOTNODE);
        $mynode->collapse = true;
        $mynode->make_inactive();

        $currenturl = $PAGE->url->out(false);
        if ($currenturl && strpos($currenturl, '/local/kopere_dashboard') > 1) {
            $params = [];
            parse_str($_SERVER['QUERY_STRING'], $params);
            $url = new moodle_url($PAGE->url->get_path(), $params);
        } else {
            $url = new moodle_url("/local/kopere_dashboard/");
        }

        $name = get_string("pluginname", "local_kopere_dashboard");
        $nav->add($name, $url);
        $node = $mynode->add($name, $url, 0, null, "kopere_dashboard_menu");
        $node->showinflatnavigation = true;
        $name = str_replace(",", "&#44;", $name);

        $CFG->custommenuitems .= "\n{$name}|{$url}";
    } catch (Exception $e) { // phpcs:disable
    }
}
