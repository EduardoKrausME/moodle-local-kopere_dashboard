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
 * @created    15/05/17 00:23
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

defined('MOODLE_INTERNAL') || die();

/**
 * Class json
 *
 * @package local_kopere_dashboard\util
 */
class json {
    /**
     * @param      $data
     * @param int $records_total
     * @param int $records_filtered
     * @param null $sql
     */
    public static function encode($data, $records_total = -1, $records_filtered = 0, $sql = null) {
        ob_clean();
        header('Content-Type: application/json; charset: utf-8');

        $return_array = array();
        if ($records_total != -1) {
            $return_array['draw'] = optional_param('draw', 0, PARAM_INT);
            $return_array['recordsTotal'] = intval($records_total);
            $return_array['recordsFiltered'] = intval($records_filtered);
        }
        $return_array['data'] = $data;

        if ($sql) {
            $return_array['sql'] = $sql;
        }

        $json = json_encode($return_array);

        $json = str_replace('"data":{', '"data":[', $json);
        $json = str_replace('}}}', '}]}', $json);
        $json = preg_replace("/\"\d+\":{/", "{", $json);

        end_util::end_script_show($json);
    }

    /**
     * @param $message
     */
    public static function error($message) {
        ob_clean();
        header('Content-Type: application/json; charset: utf-8');

        $return_array = array();
        $return_array['error'] = $message;

        $json = json_encode($return_array);

        end_util::end_script_show($json);
    }
}