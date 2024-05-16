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

/**
 * Class json
 *
 * @package local_kopere_dashboard\util
 */
class json {
    /**
     * @param        $data
     * @param int    $recordstotal
     * @param int    $recordsfiltered
     * @param string $sql
     *
     * @throws \coding_exception
     */
    public static function encode($data, $recordstotal = -1, $recordsfiltered = 0, $sql = null) {
        ob_clean();
        header('Content-Type: application/json; charset: utf-8');

        $returnarray = [];
        if ($recordstotal != -1) {
            $returnarray['draw'] = optional_param('draw', 0, PARAM_INT);
            $returnarray['recordsTotal'] = intval($recordstotal);
            $returnarray['recordsFiltered'] = intval($recordsfiltered);
        }
        $returnarray['data'] = $data;

        if ($sql) {
            $returnarray['sql'] = $sql;
        }

        $json = json_encode($returnarray);

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

        $returnarray = [];
        $returnarray['error'] = $message;

        $json = json_encode($returnarray);

        end_util::end_script_show($json);
    }
}
