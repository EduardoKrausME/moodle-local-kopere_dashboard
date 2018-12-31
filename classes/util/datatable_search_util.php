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

/**
 * Class datatable_search_util
 *
 * @package local_kopere_dashboard\util
 */
class datatable_search_util {
    /**
     * @var
     */
    private $column_select;
    /**
     * @var mixed
     */
    private $start;
    /**
     * @var mixed
     */
    private $length;
    /**
     * @var
     */
    private $order;
    /**
     * @var
     */
    private $order_dir;
    /**
     * @var
     */
    private $where;

    /**
     * datatable_search_util constructor.
     *
     * @param $column_select
     *
     * @throws \coding_exception
     */
    public function __construct($column_select) {
        $this->column_select = $column_select;
        $this->start = optional_param('start', 0, PARAM_INT);
        $this->length = optional_param('length', 0, PARAM_INT);

        $this->process_where();
        $this->proccess_order();
    }


    public function process_where() {
        global $CFG;

        $search = string_util::clear_all_params('search', false, PARAM_TEXT);

        if ($search && isset($search['value']) && isset($search['value'][0])) {
            $like = array();
            foreach ($this->column_select as $column) {
                $find = $search['value'];
                $find = str_replace("'", "\'", $find);
                $find = str_replace("--", "", $find);
                if ($CFG->dbtype == 'pgsql') {
                    if (is_array($column)) {
                        $like [] = " cast( {$column[0]} as text ) LIKE '%{$find}%'";
                    } else {
                        $like [] = "cast( {$column} as text ) LIKE '%{$find}%'";
                    }
                } else {
                    if (is_array($column)) {
                        $like [] = "{$column[0]} LIKE '%{$find}%'";
                    } else {
                        $like [] = "{$column} LIKE '%{$find}%'";
                    }
                }
            }
            $this->where = 'AND (' . implode(' OR ', $like) . ')';
        }
    }


    private function proccess_order() {

        $order = string_util::clear_all_params('order', [], PARAM_TEXT);
        $columns = string_util::clear_all_params('columns', [], PARAM_TEXT);

        if ($order && $columns) {
            $column = $order[0]['column'];
            if (is_array($this->column_select[$column])) {
                $this->order = $this->column_select[$column][0];
            } else {
                $this->order = $this->column_select[$column];
            }
            $this->order_dir = $order[0]['dir'];
        }
    }

    /**
     * @param          $sql
     * @param string $group
     * @param array $params
     * @param callback $functionbeforereturn
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function execute_sql_and_return($sql, $group = '', $params = null, $functionbeforereturn = null) {
        global $DB, $CFG;

        $sqlsearch = $sql . " $this->where $group";
        $sqlsearch = str_replace('{[columns]}', 'count(*) as num', $sqlsearch);

        $sqltotal = $sql . " $group";
        $sqltotal = str_replace('{[columns]}', 'count(*) as num', $sqltotal);

        if ($CFG->dbtype == 'pgsql') {
            $sqlreturn = $sql . " $this->where \n $group\n ORDER BY $this->order $this->order_dir \n
                                                            LIMIT $this->length OFFSET $this->start";
        } else {
            $sqlreturn = $sql . " $this->where \n $group\n ORDER BY $this->order $this->order_dir \n
                                                            LIMIT $this->start, $this->length";
        }
        $sqlreturn = str_replace('{[columns]}', implode(', ', $this->column_select), $sqlreturn);

        $result = $DB->get_records_sql($sqlreturn, $params);
        $total = $DB->get_record_sql($sqltotal, $params);
        $totalnum = $total->num;
        if ($this->where) {
            $search = $DB->get_record_sql($sqlsearch, $params);
            $searchnum = $search->num;
        } else {
            $searchnum = $totalnum;
        }

        if ($functionbeforereturn) {
            $result = call_user_func($functionbeforereturn, $result);
        }

        json::encode($result, $totalnum, $searchnum);
    }
}