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
 * @created    06/05/2024 17:25
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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

function get_kopere_lang() {
    return '
    <script>
        lang_yes = "' . get_string('yes') . '";
        lang_no = "' . get_string('no') . '";
        lang_visible = "' . get_string('courses_visible', 'local_kopere_dashboard') . '";
        lang_invisible = "' . get_string('courses_invisible', 'local_kopere_dashboard') . '";
        lang_active = "' . get_string('notification_status_active', 'local_kopere_dashboard') . '";
        lang_inactive = "' . get_string('notification_status_inactive', 'local_kopere_dashboard') . '";
        dataTables_oLanguage = {
            sEmptyTable        : "' . get_string('datatables_sEmptyTable', 'local_kopere_dashboard') . '",
            sInfo              : "' . get_string('datatables_sInfo', 'local_kopere_dashboard') . '",
            sInfoEmpty         : "' . get_string('datatables_sInfoEmpty', 'local_kopere_dashboard') . '",
            sInfoFiltered      : "' . get_string('datatables_sInfoFiltered', 'local_kopere_dashboard') . '",
            sInfoPostFix       : "' . get_string('datatables_sInfoPostFix', 'local_kopere_dashboard') . '",
            sInfoThousands     : "' . get_string('datatables_sInfoThousands', 'local_kopere_dashboard') . '",
            sLengthMenu        : "' . get_string('datatables_sLengthMenu', 'local_kopere_dashboard') . '",
            sLoadingRecords    : "' . get_string('datatables_sLoadingRecords', 'local_kopere_dashboard') . '",
            sProcessing        : "' . get_string('datatables_sProcessing', 'local_kopere_dashboard') . '",
            sErrorMessage      : "' . get_string('datatables_sErrorMessage', 'local_kopere_dashboard') . '",
            sZeroRecords       : "' . get_string('datatables_sZeroRecords', 'local_kopere_dashboard') . '",
            sSearch            : "",
            sSearchPlaceholder : "' . get_string('datatables_sSearch', 'local_kopere_dashboard') . '",
            buttons            : {
                print_text   : "' . get_string('datatables_buttons_print_text', 'local_kopere_dashboard') . '",
                copy_text    : "' . get_string('datatables_buttons_copy_text', 'local_kopere_dashboard') . '",
                csv_text     : "' . get_string('datatables_buttons_csv_text', 'local_kopere_dashboard') . '",
                copySuccess1 : "' . get_string('datatables_buttons_copySuccess1', 'local_kopere_dashboard') . '",
                copySuccess_ : "' . get_string('datatables_buttons_copySuccess_', 'local_kopere_dashboard') . '",
                copyTitle    : "' . get_string('datatables_buttons_copyTitle', 'local_kopere_dashboard') . '",
                copyKeys     : "' . get_string('datatables_buttons_copyKeys', 'local_kopere_dashboard') . '",
            },
            select             : {
                rows : {
                    _ : "' . get_string('datatables_buttons_select_rows_', 'local_kopere_dashboard') . '",
                    0 : "",
                    1 : "' . get_string('datatables_buttons_select_rows1', 'local_kopere_dashboard') . '",
                }
            },
            oPaginate          : {
                sNext     : "' . get_string('datatables_oPaginate_sNext', 'local_kopere_dashboard') . '",
                sPrevious : "' . get_string('datatables_oPaginate_sPrevious', 'local_kopere_dashboard') . '",
                sFirst    : "' . get_string('datatables_oPaginate_sFirst', 'local_kopere_dashboard') . '",
                sLast     : "' . get_string('datatables_oPaginate_sLast', 'local_kopere_dashboard') . '"
            },
            oAria              : {
                sSortAscending  : "' . get_string('datatables_oAria_sSortAscending', 'local_kopere_dashboard') . '",
                sSortDescending : "' . get_string('datatables_oAria_sSortDescending', 'local_kopere_dashboard') . '"
            }
        }
    </script>';
}
