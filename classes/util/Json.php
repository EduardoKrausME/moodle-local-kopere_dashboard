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

class Json {
    public static function encodeAndReturn($data, $recordsTotal = -1, $recordsFiltered = 0, $sql = null) {
        ob_clean();
        header('Content-Type: application/json; charset: utf-8');

        $returnArray = array();
        if ($recordsTotal != -1) {
            $returnArray['draw'] = optional_param('draw', 0, PARAM_INT);
            $returnArray['recordsTotal'] = intval($recordsTotal);
            $returnArray['recordsFiltered'] = intval($recordsFiltered);
        }
        $returnArray['data'] = $data;

        if ($sql) {
            $returnArray['sql'] = $sql;
        }

        $json = json_encode($returnArray);

        $json = str_replace('"data":{', '"data":[', $json);
        $json = str_replace('}}}', '}]}', $json);
        $json = preg_replace("/\"\d+\":{/", "{", $json);

        die($json);
    }

    public static function error($message) {
        ob_clean();
        header('Content-Type: application/json; charset: utf-8');

        $returnArray = array();
        $returnArray['error'] = $message;

        $json = json_encode($returnArray);

        die($json);
    }
}