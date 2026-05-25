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
 * capabilities_provider.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_requests;

use coding_exception;

/**
 * Class capabilities_provider
 */
class capabilities_provider {
    /**
     * Function get_capabilities
     *
     * @return array[]
     * @throws coding_exception
     */
    public static function get_capabilities(): array {
        return [
            "koperedashboard/requests:respond" => [
                "name" => get_string("cap_respond", "koperedashboard_requests"),
                "description" => get_string("cap_respond_desc", "koperedashboard_requests"),
            ],
            "koperedashboard/requests:manage" => [
                "name" => get_string("cap_manage", "koperedashboard_requests"),
                "description" => get_string("cap_manage_desc", "koperedashboard_requests"),
            ],
        ];
    }
}
