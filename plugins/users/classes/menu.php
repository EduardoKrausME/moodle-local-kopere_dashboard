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
 * @package   koperedashboard_users
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_users;

use coding_exception;
use context;
use core\exception\moodle_exception;
use dml_exception;
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
     * @throws coding_exception
     * @throws moodle_exception
     * @throws dml_exception
     */
    public static function get_definition(context $context): array {
        global $PAGE, $DB;

        $path = "/local/kopere_dashboard/plugins/users/view.php";
        $children = [];
        if ($PAGE->url->get_path() == $path) {
            $id = optional_param("id", null, PARAM_INT);
            if ($id) {
                $user = $DB->get_record("user", ["id" => $id]);
                if ($user) {
                    $children += [
                        [
                            "title" => fullname($user),
                            "url" => new moodle_url($path, ["id" => $id]),
                            "icon" => "person",
                        ],
                    ];
                }
            }
        }

        return [
            "category" => subplugin_manager::CAT_ACADEMIC,
            "items" => [
                [
                    "title" => get_string("menu_title", "koperedashboard_users"),
                    "description" => get_string("menu_desc", "koperedashboard_users"),
                    "url" => "/local/kopere_dashboard/plugins/users/",
                    "icon" => "group",
                    "capability" => "koperedashboard/users:view",
                    "children" => $children,
                ],
                [
                    "title" => get_string("menu_online_title", "koperedashboard_users"),
                    "description" => get_string("menu_desc", "koperedashboard_users"),
                    "url" => "/local/kopere_dashboard/plugins/users/online.php",
                    "icon" => "online_prediction",
                    "capability" => "koperedashboard/users:view",
                ],
            ],
        ];
    }
}
