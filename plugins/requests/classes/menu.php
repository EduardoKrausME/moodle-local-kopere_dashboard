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
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_requests;

use coding_exception;
use context;
use local_kopere_dashboard\api\subplugin_manager;

/**
 * Class menu
 */
class menu {
    /**
     * Function get_definition
     *
     * @param context $context
     * @return array
     * @throws coding_exception
     */
    public static function get_definition(context $context): array {
        return [
            "category" => subplugin_manager::CAT_ACADEMIC,
            "items" => [
                [
                    "title" => get_string("manage_title", "koperedashboard_requests"),
                    "description" => "View and respond requests.",
                    "url" => [
                        "/local/kopere_dashboard/plugins/requests/manage.php",
                        "/local/kopere_dashboard/plugins/requests/view.php",
                    ],
                    "icon" => "support_agent",
                    "capability" => [
                        "koperedashboard/requests:respond",
                        "koperedashboard/requests:manage",
                    ],
                    "children" => [
                        [
                            "title" => get_string("categories_title", "koperedashboard_requests"),
                            "url" => "/local/kopere_dashboard/plugins/requests/categories.php",
                            "icon" => "category",
                        ],
                    ],
                ],
                [
                    "title" => get_string("my_title", "koperedashboard_requests"),
                    "description" => get_string("menu_desc", "koperedashboard_requests"),
                    "url" => [
                        "/local/kopere_dashboard/plugins/requests/my.php",
                        "/local/kopere_dashboard/plugins/requests/view.php",
                    ],
                    "icon" => "contact_support",
                    "capability" => "koperedashboard/requests:create",
                    "children" => [],
                ],
            ],
        ];
    }
}
