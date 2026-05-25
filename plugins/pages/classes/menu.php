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
 * menu.php
 *
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_pages;

use context;
use local_kopere_dashboard\api\subplugin_manager;
use moodle_url;

/**
 * Class menu
 */
class menu {
    /**
     * Function get_definition
     *
     * @param context $context
     * @return array
     * @throws \coding_exception
     */
    public static function get_definition(context $context): array {
        return [
            "category" => subplugin_manager::CAT_TOOLS,
            "items" => [
                [
                    "title" => get_string("menu_title", "koperedashboard_pages"),
                    "description" => get_string("menu_desc", "koperedashboard_pages"),
                    "url" => [
                        new moodle_url("/local/kopere_dashboard/plugins/pages/"),
                        new moodle_url("/local/kopere_dashboard/plugins/pages/menu_delete.php"),
                        new moodle_url("/local/kopere_dashboard/plugins/pages/menu_edit.php"),
                        new moodle_url("/local/kopere_dashboard/plugins/pages/page_delete.php"),
                        new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php"),
                        new moodle_url("/local/kopere_dashboard/plugins/pages/page_view.php"),
                    ],
                    "icon" => "article",
                    "capability" => "koperedashboard/pages:manage",
                    "children" => [],
                ],
            ],
        ];
    }
}
