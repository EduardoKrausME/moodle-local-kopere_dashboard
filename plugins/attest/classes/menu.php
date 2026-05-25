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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest;

use koperedashboard_attest\helper\studentcard_helper;
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
        global $USER;

        if (has_capability("koperedashboard/attest:manage", $context)) {
            return [
                "category" => subplugin_manager::CAT_ACADEMIC,
                "items" => [
                    [
                        "title" => get_string("manage_title", "koperedashboard_attest"),
                        "description" => get_string("menu_desc", "koperedashboard_attest"),
                        "url" => [
                            "/local/kopere_dashboard/plugins/attest/manage.php",
                            "/local/kopere_dashboard/plugins/attest/edit.php",
                        ],
                        "icon" => "docs",
                        "capability" => "koperedashboard/attest:manage",
                        "children" => [
                            [
                                "title" => get_string("manage_title", "koperedashboard_attest"),
                                "url" => "/local/kopere_dashboard/plugins/attest/manage.php",
                                "icon" => "docs",
                                "capability" => "koperedashboard/attest:manage",
                            ], [
                                "title" => get_string("student_title", "koperedashboard_attest"),
                                "url" => "/local/kopere_dashboard/plugins/attest/my.php",
                                "icon" => "docs",
                                "capability" => "koperedashboard/attest:view",
                            ], [
                                "title" => get_string("studentcard", "koperedashboard_attest"),
                                "url" => "/local/kopere_dashboard/plugins/attest/studentcard.php",
                                "icon" => "badge",
                                "capability" => "koperedashboard/attest:view",
                            ],
                        ],
                    ],
                ],
            ];
        } else {
            return [
                "category" => subplugin_manager::CAT_ACADEMIC,
                "items" => [
                    [
                        "title" => get_string("title_view", "koperedashboard_attest"),
                        "description" => get_string("menu_desc", "koperedashboard_attest"),
                        "url" => "/local/kopere_dashboard/plugins/attest/my.php",
                        "icon" => "docs",
                        "capability" => "koperedashboard/attest:view",
                        "children" => [],
                    ], [
                        "title" => get_string("studentcard", "koperedashboard_attest"),
                        "description" => get_string("studentcard_desc", "koperedashboard_attest"),
                        "url" => "/local/kopere_dashboard/plugins/attest/studentcard.php",
                        "icon" => "badge",
                        "capability" => "koperedashboard/attest:view",
                        "children" => [],
                    ],
                ],
            ];
        }
    }
}
