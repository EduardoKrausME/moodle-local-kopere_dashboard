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
 * performancemonitor file
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\external;

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * Class performancemonitor
 *
 * @package local_kopere_dashboard\external
 */
class performancemonitor extends external_api {

    /**
     * Function disk_moodledata_parameters
     *
     * @return external_function_parameters
     */
    public static function disk_moodledata_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * Function disk_moodledata_is_allowed_from_ajax
     *
     * @return bool
     */
    public static function disk_moodledata_is_allowed_from_ajax() {
        return true;
    }

    /**
     * Function disk_moodledata_returns
     *
     * @return external_single_structure
     */
    public static function disk_moodledata_returns() {
        return new external_single_structure([
            "disk" => new external_value(PARAM_TEXT, 'Use Disk Moodledata', VALUE_OPTIONAL),
        ]);
    }

    /**
     * Function disk_moodledata
     *
     * @return array
     */
    public static function disk_moodledata() {

        return [
            "disk" => \local_kopere_dashboard\server\performancemonitor::disk_moodledata(false),
        ];
    }
}
