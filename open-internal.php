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
define('OPEN_INTERNAL', true);

define('BENCHSTART', microtime(true));
require('../../config.php');
define('BENCHSTOP', microtime(true));
require('autoload.php');

if ($CFG->kopere_dashboard_open != 'internal') {
    $urlinternal = "{$CFG->wwwroot}/local/kopere_dashboard/open.php?" . clean_param($_SERVER['QUERY_STRING'], PARAM_TEXT);
    @header("Location: {$urlinternal}");
    echo "<meta http-equiv=\"refresh\" content=\"0; url={$urlinternal}\">";
}

global $PAGE, $CFG, $OUTPUT, $USER;

if ($CFG->theme == 'smartlms') {
    $preference_drawer_open_nav = @$USER->preference['drawer-open-nav'];
    $preference_sidebar_open_nav = @$USER->preference['sidebar-open-nav'];
    @$USER->preference['drawer-open-nav'] = false;
    @$USER->preference['sidebar-open-nav'] = false;
}

require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('local/kopere_dashboard:view', context_system::instance());
require_capability('local/kopere_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/open-internal.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string_kopere('modulename'));
$PAGE->set_heading(get_string_kopere('modulename'));

$PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
$PAGE->requires->css('/local/kopere_dashboard/assets/all-internal.css');

$PAGE->navbar->add(get_string_kopere('modulename'), new moodle_url('/local/kopere_dashboard/open-internal.php'));

load_class();
$htmlApp = ob_get_contents();
ob_clean();

$PAGE->requires->jquery();
$PAGE->requires->js('/local/kopere_dashboard/assets/bootstrap/bootstrap.js');

$PAGE->requires->js_call_amd('local_kopere_dashboard/start_load', 'init');

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
<?php

$dashboard_menu_html_boost = \local_kopere_dashboard\output\menu::create_menu();
$dashboard_menu_html_boost = str_replace("'", '"', $dashboard_menu_html_boost);
$dashboard_menu_html_old = "<div id=\"inst0\" class=\"block\">
            <div class=\"dashboard_menu_html kopere-logo\">
                <div class=\"logo-w\">
                    <img class=\"normal\"
                         src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/logo-notext.svg\"
                         alt=\"{get_string_kopere('pluginname')}\">
                </div>
            </div>
            <div class=\"content\">
                " . \local_kopere_dashboard\output\menu::create_menu() . "
            </div>
        </div>";
$dashboard_menu_html_old = str_replace("'", '"', $dashboard_menu_html_old);

echo "<div id='kopere_dashboard_div'>
         <div class=\"menu-w hidden-print dashboard_menu_html-content\">
            <div class=\"menu-and-user\">
                {$dashboard_menu_html_boost}
            </div>
        </div>
        <div class='content-w'>
            {$htmlApp}
        </div>

        <div class=\"modal fade kopere_dashboard_modal_item\" id=\"modal-edit\" role=\"dialog\">
            <div class=\"kopere-modal-dialog\">
                <div class=\"kopere-modal-content\">
                    <div class=\"loader\"></div>
                </div>
            </div>
        </div>
    </div>";

echo $OUTPUT->footer();

$html = ob_get_contents();
ob_clean();
$dashboard_menu_html_old = "<style>.dashboard_menu_html-content{display:none !important}</style>" . $dashboard_menu_html_old;


if ($CFG->theme == 'smartlms') {
    $USER->preference['drawer-open-nav'] = $preference_drawer_open_nav;
    $USER->preference['sidebar-open-nav'] = $preference_sidebar_open_nav;
} elseif ($CFG->theme != 'moove') {
    if (preg_match_all('/(.*)(<div.*?class="block_navigation.*)/', $html)) {
        $html = preg_replace('/(.*)(<div.*?class="block_navigation.*)/', "$1{$dashboard_menu_html_old}$2", $html);
    } else if (preg_match_all('/(.*)(<section.*?class="(\s+)?block_navigation.*)/s', $html)) {
        $html = preg_replace('/(.*)(<section.*?class="(\s+)?block_navigation.*)/s', "$1<div class='card mb-3'>{$dashboard_menu_html_old}</div>$2", $html);
    } else if (strpos($html, 'data-region="drawer"')) {
        $classdiv = " class='list-group-item kopere-list-group-item' ";
        $html = preg_replace('/(.*data-region="drawer".*?>)(.*)/', "$1<div{$classdiv}>{$dashboard_menu_html_old}</div>$2", $html);
    }
}

echo $html;
