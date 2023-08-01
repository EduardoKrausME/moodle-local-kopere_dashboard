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
 * User: Eduardo Kraus
 * Date: 31/07/2023
 * Time: 20:41
 */

?>
<script>
    lang_yes = '<?php echo get_string('yes') ?>';
    lang_no = '<?php echo get_string('no') ?>';
    lang_visible = '<?php echo get_string_kopere('courses_visible')?>';
    lang_invisible = '<?php echo get_string_kopere('courses_invisible')?>';
    lang_active = '<?php echo get_string_kopere('notification_status_active')?>';
    lang_inactive = '<?php echo get_string_kopere('notification_status_inactive')?>';
    dataTables_oLanguage = {
        sEmptyTable        : "<?php echo get_string_kopere('datatables_sEmptyTable') ?>",
        sInfo              : "<?php echo get_string_kopere('datatables_sInfo') ?>",
        sInfoEmpty         : "<?php echo get_string_kopere('datatables_sInfoEmpty') ?>",
        sInfoFiltered      : "<?php echo get_string_kopere('datatables_sInfoFiltered') ?>",
        sInfoPostFix       : "<?php echo get_string_kopere('datatables_sInfoPostFix') ?>",
        sInfoThousands     : "<?php echo get_string_kopere('datatables_sInfoThousands') ?>",
        sLengthMenu        : "<?php echo get_string_kopere('datatables_sLengthMenu') ?>",
        sLoadingRecords    : "<?php echo get_string_kopere('datatables_sLoadingRecords') ?>",
        sProcessing        : "<?php echo get_string_kopere('datatables_sProcessing') ?>",
        sErrorMessage      : "<?php echo get_string_kopere('datatables_sErrorMessage') ?>",
        sZeroRecords       : "<?php echo get_string_kopere('datatables_sZeroRecords') ?>",
        sSearch            : "",
        sSearchPlaceholder : "<?php echo get_string_kopere('datatables_sSearch') ?>",
        buttons            : {
            print_text   : "<?php echo get_string_kopere('datatables_buttons_print_text') ?>",
            copy_text    : "<?php echo get_string_kopere('datatables_buttons_copy_text') ?>",
            csv_text     : "<?php echo get_string_kopere('datatables_buttons_csv_text') ?>",
            copySuccess1 : "<?php echo get_string_kopere('datatables_buttons_copySuccess1') ?>",
            copySuccess_ : "<?php echo get_string_kopere('datatables_buttons_copySuccess_') ?>",
            copyTitle    : "<?php echo get_string_kopere('datatables_buttons_copyTitle') ?>",
            copyKeys     : "<?php echo get_string_kopere('datatables_buttons_copyKeys') ?>",
        },
        select             : {
            rows : {
                _ : "<?php echo get_string_kopere('datatables_buttons_select_rows_') ?>",
                0 : "",
                1 : "<?php echo get_string_kopere('datatables_buttons_select_rows1') ?>",
            }
        },
        oPaginate          : {
            sNext     : "<?php echo get_string_kopere('datatables_oPaginate_sNext') ?>",
            sPrevious : "<?php echo get_string_kopere('datatables_oPaginate_sPrevious') ?>",
            sFirst    : "<?php echo get_string_kopere('datatables_oPaginate_sFirst') ?>",
            sLast     : "<?php echo get_string_kopere('datatables_oPaginate_sLast') ?>"
        },
        oAria              : {
            sSortAscending  : "<?php echo get_string_kopere('datatables_oAria_sSortAscending') ?>",
            sSortDescending : "<?php echo get_string_kopere('datatables_oAria_sSortDescending') ?>"
        }
    }
</script>
