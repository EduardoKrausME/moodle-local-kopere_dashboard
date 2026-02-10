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
 * json file
 *
 * introduced 15/05/17 00:23
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

use Exception;

/**
 * Class json
 *
 * @package local_kopere_dashboard\util
 */
class json {
    /**
     * Function encode
     *
     * @param $data
     * @param int $recordstotal
     * @param int $recordsfiltered
     * @param null $sql
     * @throws Exception
     */
    public static function encode($data, $recordstotal = -1, $recordsfiltered = 0, $sql = null) {
        ob_clean();
        header("Content-Type: application/json; charset: utf-8");

        $returnarray = [];
        if ($recordstotal != -1) {
            $returnarray["draw"] = optional_param("draw", 0, PARAM_INT);
            $returnarray["recordsTotal"] = intval($recordstotal);
            $returnarray["recordsFiltered"] = intval($recordsfiltered);
        }
         /**
         * Fix for https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/issues/146
         * STEP 5: Ensure DataTables receives an array, not an object
         */
        if (is_array($data) && !empty($data) && array_keys($data) !== range(0, count($data) - 1)) {
            $data = array_values($data);
        }
        $returnarray["data"] = $data;

        if ($sql) {
            $returnarray["sql"] = $sql;
        }
        /**
         * Fix for https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/issues/146
         * STEP 4: Added JSON_UNESCAPED_UNICODE option to json_encode to ensure that all Unicode characters are properly encoded and displayed in the JSON response, preventing issues with special characters in user data.
         */
        $json = json_encode($returnarray, JSON_UNESCAPED_UNICODE);

        end_util::end_script_show($json);
    }

    /**
     * Function error
     *
     * @param $message
     */
    public static function error($message) {
        ob_clean();
        header("Content-Type: application/json; charset: utf-8");

        $returnarray = [];
        $returnarray["error"] = $message;

        $json = json_encode($returnarray);

        end_util::end_script_show($json);
    }
}
