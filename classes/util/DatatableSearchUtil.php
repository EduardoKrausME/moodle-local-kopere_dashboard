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
 * @created    18/05/17 04:58
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

defined('MOODLE_INTERNAL') || die();

class DatatableSearchUtil {
    private $columnOrder;
    private $columnsSelect;
    private $start;
    private $length;
    private $order;
    private $orderDir;
    private $where;

    public function __construct($columnSelect, $columnOrder) {
        $this->columnOrder = $columnOrder;
        $this->columnsSelect = $columnSelect;
        $this->start = optional_param('start', 0, PARAM_INT);
        $this->length = optional_param('length', 0, PARAM_INT);

        $this->processWhere();
        $this->proccessOrder();
    }

    public function processWhere() {
        if (isset($_POST['search']['value']) && isset($_POST['search']['value'][0])) {
            $like = array();
            foreach ($this->columnOrder as $column) {
                $find = $_POST['search']['value'];
                $find = str_replace("'", "\'", $find);
                $find = str_replace("--", "", $find);
                if (is_array($column)) {
                    $like [] = $column[1] . " LIKE '%{$find}%'";
                } else {
                    $like [] = $column . " LIKE '%{$find}%'";
                }
            }
            $this->where = 'AND (' . implode(' OR ', $like) . ')';
        }
    }

    private function proccessOrder() {
        if (isset($_POST['order']) && isset($_POST['columns'])) {
            $_column = $_POST['order'][0]['column'];
            if (is_array($this->columnOrder[$_column])) {
                $this->order = $this->columnOrder[$_column][0];
            } else {
                $this->order = $this->columnOrder[$_column];
            }
            $this->orderDir = $_POST['order'][0]['dir'];
        }
    }

    /**
     * @param          $sql
     * @param string $group
     * @param array $params
     * @param callback $functionBeforeReturn
     */
    public function executeSqlAndReturn($sql, $group = '', $params = null, $functionBeforeReturn = null) {
        global $DB, $CFG;

        $sqlSearch = $sql . " $this->where $group";
        $sqlSearch = str_replace('{[columns]}', 'count(*) as num', $sqlSearch);

        $sqlTotal = $sql . " $group";
        $sqlTotal = str_replace('{[columns]}', 'count(*) as num', $sqlTotal);

        if ($CFG->dbtype == 'pgsql') {
            $sqlReturn = $sql . " $this->where \n $group\n ORDER BY $this->order $this->orderDir \n LIMIT $this->length OFFSET $this->start";
        }else{
            $sqlReturn = $sql . " $this->where \n $group\n ORDER BY $this->order $this->orderDir \n LIMIT $this->start, $this->length";
        }
        $sqlReturn = str_replace('{[columns]}', implode(', ', $this->columnsSelect), $sqlReturn);

        $result = $DB->get_records_sql($sqlReturn, $params);
        $total  = $DB->get_record_sql($sqlTotal, $params);
        $totalNum = $total->num;
        if ($this->where) {
            $search = $DB->get_record_sql($sqlSearch, $params);
            $searchNum = $search->num;
        } else {
            $searchNum = $totalNum;
        }

        if ($functionBeforeReturn) {
            $result = call_user_func($functionBeforeReturn, $result);
        }

        Json::encodeAndReturn($result, $totalNum, $searchNum);
    }
}