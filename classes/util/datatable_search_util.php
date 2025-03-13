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
 * datatable_search_util file
 *
 * introduced 18/05/17 04:58
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class datatable_search_util
 *
 * @package local_kopere_dashboard\util
 */
class datatable_search_util {
    /** @var string */
    private $columnselect;
    /** @var mixed */
    private $start;
    /** @var mixed */
    private $length;
    /** @var string */
    private $order;
    /** @var string */
    private $orderdir;
    /** @var string */
    private $where;
    /** @var array */
    private $params = [];

    /**
     * Datatable_search_util constructor.
     *
     * @param $columnselect
     *
     * @throws \coding_exception
     */
    public function __construct($columnselect) {
        $this->columnselect = $columnselect;
        $this->start = optional_param("start", 0, PARAM_INT);
        $this->length = optional_param("length", 0, PARAM_INT);

        $this->process_where();
        $this->proccess_order();
    }

    /**
     * Function process_where
     *
     * @throws \coding_exception
     */
    public function process_where() {
        global $DB;

        $count = 0;
        $search = optional_param_array("search", [], PARAM_TEXT);

        if ($search && isset($search["value"]) && isset($search["value"][0])) {
            $like = [];
            foreach ($this->columnselect as $column) {
                $searchvalue = $search["value"];
                $searchvalue = str_replace("'", "\'", $searchvalue);
                $searchvalue = str_replace(["\n", "\r"], "", $searchvalue);
                $searchvalue = str_replace("--", "", $searchvalue);
                if ($DB->get_dbfamily() == "postgres") {
                    if (is_array($column)) {
                        $count++;
                        $like[] = " cast( {$column[0]} as text ) LIKE :searchparam{$count}";
                        $this->params["searchparam{$count}"] = "%{$searchvalue}%";
                    } else {
                        $count++;
                        $like[] = "cast( {$column} as text ) LIKE :searchparam{$count}";
                        $this->params["searchparam{$count}"] = "%{$searchvalue}%";
                    }
                } else {
                    if (is_array($column)) {
                        $count++;
                        $like[] = "{$column[0]} LIKE :searchparam{$count}";
                        $this->params["searchparam{$count}"] = "%{$searchvalue}%";
                    } else {
                        $count++;
                        $like[] = "{$column} LIKE :searchparam{$count}";
                        $this->params["searchparam{$count}"] = "%{$searchvalue}%";
                    }
                }
            }
            $this->where = "AND (" . implode(" OR ", $like) . ")";
        }
    }

    /**
     * Function proccess_order
     */
    private function proccess_order() {

        $order = string_util::clear_all_params("order", [], PARAM_TEXT);

        if ($order) {
            $column = intval($order[0]["column"]);
            if (is_array($this->columnselect[$column])) {
                $this->order = $this->columnselect[$column][0];
            } else {
                $this->order = $this->columnselect[$column];
            }

            // Check if it is ASC to avoid adding the value from the user.
            $this->orderdir = $order[0]["dir"] == "asc" ? "ASC" : "DESC";
        }
    }

    /**
     * Function execute_sql_and_return
     *
     * @param $sql
     * @param null $group
     * @param null $params
     * @param null $functionbeforereturn
     *
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public function execute_sql_and_return($sql, $group = null, $params = null, $functionbeforereturn = null) {
        global $DB;

        if ($params == null) {
            $params = $this->params;
        } else {
            $params = array_merge($params, $this->params);
        }

        $groupfind = str_replace("GROUP BY", "", $group);

        $sqlsearch = "{$sql} {$this->where}";
        $sqltotal = $sql;
        if ($group) {
            $sqlsearch = str_replace("{[columns]}", "count(DISTINCT {$groupfind}) as num", $sqlsearch);
            $sqltotal = str_replace("{[columns]}", "count(DISTINCT {$groupfind}) as num", $sqltotal);
        } else {
            $sqlsearch = str_replace("{[columns]}", "count(*) as num", $sqlsearch);
            $sqltotal = str_replace("{[columns]}", "count(*) as num", $sqltotal);
        }

        $order = "";
        if ($this->order) {
            $order = "ORDER BY $this->order $this->orderdir";
        }

        if ($DB->get_dbfamily() == "postgres") {
            $sqlreturn = "{$sql} $this->where $group {$order} LIMIT $this->length OFFSET $this->start";
        } else {
            $sqlreturn = "{$sql} $this->where $group {$order} LIMIT $this->start, $this->length";
        }

        $sqlreturn = str_replace("{[columns]}", implode(", ", $this->columnselect), $sqlreturn);

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
