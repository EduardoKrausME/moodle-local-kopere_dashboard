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
 * locallib file
 *
 * introduced 06/05/2024 17:25
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * make url.
 *
 * @param string $classname
 * @param string $method
 * @param array $params
 * @param string $file
 *
 * @return string
 */
function local_kopere_dashboard_makeurl($classname, $method, $params = [], $file = "view") {
    $query = "classname={$classname}&method={$method}";
    foreach ($params as $key => $param) {
        $query .= "&{$key}={$param}";
    }

    global $CFG;
    return "{$CFG->wwwroot}/local/kopere_dashboard/{$file}.php?{$query}";
}

/**
 * Function get_kopere_lang
 */
function get_kopere_lang() {

    global $PAGE;

    $PAGE->requires->strings_for_js(["yes", "no"], "moodle");
    $PAGE->requires->strings_for_js([
        "visible",
        "invisible",
        "active",
        "inactive",
        "date_renderer_format",
        "datetime_renderer_format",
        "datatables_sEmptyTable",
        "datatables_sInfo",
        "datatables_sInfoEmpty",
        "datatables_sInfoFiltered",
        "datatables_sInfoPostFix",
        "datatables_sInfoThousands",
        "datatables_sLengthMenu",
        "datatables_sLoadingRecords",
        "datatables_sProcessing",
        "datatables_sErrorMessage",
        "datatables_sZeroRecords",
        "datatables_sSearch",
        "datatables_buttons_print_text",
        "datatables_buttons_copy_text",
        "datatables_buttons_csv_text",
        "datatables_buttons_copySuccess1",
        "datatables_buttons_copySuccess_",
        "datatables_buttons_copyTitle",
        "datatables_buttons_copyKeys",
        "datatables_buttons_select_rows_",
        "datatables_buttons_select_rows1",
        "datatables_buttons_pageLength_",
        "datatables_buttons_pageLength_1",
        "datatables_oPaginate_sNext",
        "datatables_oPaginate_sPrevious",
        "datatables_oPaginate_sFirst",
        "datatables_oPaginate_sLast",
        "datatables_oAria_sSortAscending",
        "datatables_oAria_sSortDescending",
        "datatables_oPaginate_sFirst",
        "datatables_oPaginate_sLast",
    ], "local_kopere_dashboard");
}
