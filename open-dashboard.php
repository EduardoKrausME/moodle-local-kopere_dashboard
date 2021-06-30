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
 * @created    16/11/18 02:01
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

ob_start();
define('AJAX_SCRIPT', false);
define('OPEN_INTERNAL', false);

define('BENCHSTART', microtime(true));
require('../../config.php');
define('BENCHSTOP', microtime(true));
require('autoload.php');

if ($CFG->kopere_dashboard_open == 'internal') {
    $urlinternal = "{$CFG->wwwroot}/local/kopere_dashboard/open-internal.php?" . clean_param($_SERVER['QUERY_STRING'], PARAM_TEXT);
    @header("Location: {$urlinternal}");
    echo "<meta http-equiv=\"refresh\" content=\"0; url={$urlinternal}\">";
}

global $PAGE, $CFG, $OUTPUT;

require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('local/kopere_dashboard:view', context_system::instance());
require_capability('local/kopere_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/open-dashboard.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('popup');
$PAGE->set_title(get_string_kopere('modulename'));
$PAGE->set_heading(get_string_kopere('modulename'));

$PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
$PAGE->requires->css('/local/kopere_dashboard/assets/all-frame.css');

$PAGE->navbar->add(get_string_kopere('modulename'), new moodle_url('/local/kopere_dashboard/open-dashboard.php'));

load_class();
$htmlApp = ob_get_contents();
ob_clean();

$PAGE->requires->jquery();
$PAGE->requires->js('/local/kopere_dashboard/assets/bootstrap/bootstrap.js');

$PAGE->requires->js_call_amd('local_kopere_dashboard/start_load', 'init');

$PAGE->add_body_class("kopere_dashboard_body");

echo $OUTPUT->header();
echo "<link rel=\"icon\" href=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/favicon.png\"/>";
?>
    <script>
        lang_yes = '<?php echo get_string('yes') ?>';
        lang_no = '<?php echo get_string('no') ?>';
        lang_visible = '<?php echo get_string_kopere('courses_visible')?>';
        lang_invisible = '<?php echo get_string_kopere('courses_invisible')?>';
        lang_active = '<?php echo get_string_kopere('notification_status_active')?>';
        lang_inactive = '<?php echo get_string_kopere('notification_status_inactive')?>';
        dataTables_oLanguage = {
            sEmptyTable     : "<?php echo get_string_kopere('datatables_sEmptyTable') ?>",
            sInfo           : "<?php echo get_string_kopere('datatables_sInfo') ?>",
            sInfoEmpty      : "<?php echo get_string_kopere('datatables_sInfoEmpty') ?>",
            sInfoFiltered   : "<?php echo get_string_kopere('datatables_sInfoFiltered') ?>",
            sInfoPostFix    : "<?php echo get_string_kopere('datatables_sInfoPostFix') ?>",
            sInfoThousands  : "<?php echo get_string_kopere('datatables_sInfoThousands') ?>",
            sLengthMenu     : "<?php echo get_string_kopere('datatables_sLengthMenu') ?>",
            sLoadingRecords : "<?php echo get_string_kopere('datatables_sLoadingRecords') ?>",
            sProcessing     : "<?php echo get_string_kopere('datatables_sProcessing') ?>",
            sZeroRecords    : "<?php echo get_string_kopere('datatables_sZeroRecords') ?>",
            sSearch         : "<?php echo get_string_kopere('datatables_sSearch') ?>",
            oPaginate       : {
                sNext     : "<?php echo get_string_kopere('datatables_oPaginate_sNext') ?>",
                sPrevious : "<?php echo get_string_kopere('datatables_oPaginate_sPrevious') ?>",
                sFirst    : "<?php echo get_string_kopere('datatables_oPaginate_sFirst') ?>",
                sLast     : "<?php echo get_string_kopere('datatables_oPaginate_sLast') ?>"
            },
            oAria           : {
                sSortAscending  : "<?php echo get_string_kopere('datatables_oAria_sSortAscending') ?>",
                sSortDescending : "<?php echo get_string_kopere('datatables_oAria_sSortDescending') ?>"
            }
        }
    </script>

    <script>
        if (window != window.top) {
            document.body.className += " in-iframe";
        }
    </script>
    <div class="all-wrapper">

        <div class="layout-w">

            <div class="menu-w hidden-print">
                <div class="logo-w">
                    <img class="normal"
                         src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/logo.svg"
                         alt="<?php echo get_string_kopere('pluginname') ?>">
                    <img class="mobile"
                         src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/logo-notext.svg"
                         alt="<?php echo get_string_kopere('pluginname') ?>">
                </div>
                <div class="menu-and-user">
                    <?php
                    echo \local_kopere_dashboard\output\menu::create_menu();
                    ?>
                </div>
            </div>

            <div class="content-w <?php echo get_path_query() ?>">
                <?php
                load_class();
                ?>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modal-edit" role="dialog">
        <div class="kopere-modal-dialog">
            <div class="kopere-modal-content">
                <div class="loader"></div>
            </div>
        </div>
    </div>

<?php


echo $OUTPUT->footer();
