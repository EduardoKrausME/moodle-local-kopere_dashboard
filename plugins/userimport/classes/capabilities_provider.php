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
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_userimport;

use coding_exception;

/**
 * Class capabilities_provider
 */
class capabilities_provider {
    /**
     * Return capabilities displayed in the Kopere Dashboard permissions screen.
     *
     * @return array
     * @throws coding_exception
     */
    public static function get_capabilities(): array {
        return [
            "koperedashboard/userimport:manage" => [
                "name" => get_string("cap_manage", "koperedashboard_userimport"),
                "description" => get_string("cap_manage_desc", "koperedashboard_userimport"),
            ],
        ];
    }
}
