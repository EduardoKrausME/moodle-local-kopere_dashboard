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
 * @created    23/05/17 17:59
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use local_kopere_dashboard\util\DashboardUtil;

ob_start();
define('AJAX_SCRIPT', false);

define('BENCHSTART', microtime(true));
require('../../config.php');
define('BENCHSTOP', microtime(true));

require_once('autoload.php');

global $PAGE, $CFG, $OUTPUT;

require_login();
require_capability('local/kopere_dashboard:view', context_system::instance());
require_capability('local/kopere_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/open-dashboard.php'));
$PAGE->set_pagetype('reports');
$PAGE->set_context(context_system::instance());

if (!strlen($_SERVER['QUERY_STRING'])) {
    $_SERVER['QUERY_STRING'] = 'Dashboard::start';
}

if (isset($_POST['action'])) {
    loadByQuery($_POST['action']);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Kopere Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/all.css" rel="stylesheet">
    <link async href="https://fonts.googleapis.com/css?family=Nunito:300,400" rel="stylesheet">

    <script>
        lang_yes = '<?php echo get_string('yes') ?>';
        lang_no = '<?php echo get_string('no') ?>';
        dataTables_oLanguage = {
            "sEmptyTable": "<?php echo get_string_kopere('datatables_sEmptyTable') ?>",
            "sInfo": "<?php echo get_string_kopere('datatables_sInfo') ?>",
            "sInfoEmpty": "<?php echo get_string_kopere('datatables_sInfoEmpty') ?>",
            "sInfoFiltered": "<?php echo get_string_kopere('datatables_sInfoFiltered') ?>",
            "sInfoPostFix": "<?php echo get_string_kopere('datatables_sInfoPostFix') ?>",
            "sInfoThousands": "<?php echo get_string_kopere('datatables_sInfoThousands') ?>",
            "sLengthMenu": "<?php echo get_string_kopere('datatables_sLengthMenu') ?>",
            "sLoadingRecords": "<?php echo get_string_kopere('datatables_sLoadingRecords') ?>",
            "sProcessing": "<?php echo get_string_kopere('datatables_sProcessing') ?>",
            "sZeroRecords": "<?php echo get_string_kopere('datatables_sZeroRecords') ?>",
            "sSearch": "<?php echo get_string_kopere('datatables_sSearch') ?>",
            "oPaginate": {
                "sNext": "<?php echo get_string_kopere('datatables_oPaginate_sNext') ?>",
                "sPrevious": "<?php echo get_string_kopere('datatables_oPaginate_sPrevious') ?>",
                "sFirst": "<?php echo get_string_kopere('datatables_oPaginate_sFirst') ?>",
                "sLast": "<?php echo get_string_kopere('datatables_oPaginate_sLast') ?>"
            },
            "oAria": {
                "sSortAscending": "<?php echo get_string_kopere('datatables_oAria_sSortAscending') ?>",
                "sSortDescending": "<?php echo get_string_kopere('datatables_oAria_sSortDescending') ?>"
            }
        }
    </script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/jquery-3.2.1.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/dashboard.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/bootstrap/bootstrap.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-numeric-comma.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-currency.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-date-uk.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/sorting-file-size.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/dataTables/renderer-visible.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/moment.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/daterangepicker.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/select2.full.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/validator.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/jquery.maskedinput.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/jquery.validate-v1.15.0.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/js/custom.js"></script>

    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/node/socket.io.js"></script>
    <script src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/node/app-v2.js"></script>

    <?php
    if (get_config('local_kopere_dashboard', 'nodejs-status')) {
        if (get_config('local_kopere_dashboard', 'nodejs-ssl')) {
            $url = "https://" . get_config('local_kopere_dashboard', 'nodejs-url') . ':' . get_config('local_kopere_dashboard', 'nodejs-port');
        } else {
            $url = get_config('local_kopere_dashboard', 'nodejs-url') . ':' . get_config('local_kopere_dashboard', 'nodejs-port');
        }

        $userid = intval($USER->id);
        $fullname = '"' . fullname($USER) . '"';
        $serverTime = time();
        $urlNode = '"' . $url . '"';

        echo "<script type=\"text/javascript\">
                  startServer ( $userid, $fullname, $serverTime, $urlNode, 'z35admin' );
              </script>";
    }
    ?>
    <link rel="icon" href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/favicon.png"/>

</head>
<body>
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
                     alt="Kopere DashBoard">
                <img class="mobile"
                     src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/assets/dashboard/img/logo-notext.svg"
                     alt="Kopere DashBoard">
            </div>
            <div class="menu-and-user">
                <ul class="main-menu">
                    <?php

                    DashboardUtil::addMenu('Dashboard::start', 'dashboard', 'Dashboard');

                    if (has_capability('moodle/site:config', context_system::instance())) {
                        $menuExtra = array(
                            array('UsersOnline::dashboard', get_string_kopere('useronline_title'), 'users-online'),
                            array('UsersImport::dashboard', get_string_kopere('userimport_title'), 'users-import')
                        );
                    } else {
                        $menuExtra = array(
                            array('UsersOnline::dashboard', get_string_kopere('useronline_title'), 'users-online')
                        );
                    }
                    DashboardUtil::addMenu('Users::dashboard', 'users', get_string_kopere('user_title'), $menuExtra);

                    DashboardUtil::addMenu('Courses::dashboard', 'courses', get_string_kopere('courses_title'));

                    $plugins = $DB->get_records_sql("SELECT plugin FROM {config_plugins} WHERE plugin LIKE 'local\_kopere\_dashboard\_%' AND name LIKE 'version'");
                    foreach ($plugins as $plugin) {
                        $className = $plugin->plugin . '\\Menu';
                        $class = new $className();
                        $class->showMenu();
                    }

                    DashboardUtil::addMenu('Reports::dashboard', 'report', get_string_kopere('reports_title'),
                        \local_kopere_dashboard\Reports::globalMenus());

                    // DashboardUtil::addMenu ( 'Gamification::dashboard', 'gamification', 'Gamificação' );

                    if (has_capability('moodle/site:config', context_system::instance()))
                        DashboardUtil::addMenu('Notifications::dashboard', 'notifications', get_string_kopere('notification_title'));

                    if (has_capability('moodle/site:config', context_system::instance()))
                        DashboardUtil::addMenu('WebPages::dashboard', 'webpages', get_string_kopere('webpages_title'));
                    DashboardUtil::addMenu('Benchmark::test', 'performace', get_string_kopere('benchmark_title'));
                    if (has_capability('moodle/site:config', context_system::instance()))
                        DashboardUtil::addMenu('Backup::dashboard', 'data', 'Backup');
                    DashboardUtil::addMenu('About::dashboard', 'about', get_string_kopere('about_title'));
                    ?>
                </ul>
            </div>
        </div>

        <div class="content-w">
            <?php
            $queryString = $_SERVER['QUERY_STRING'];
            loadByQuery($queryString);
            ?>
        </div>


    </div>
</div>

<div class="modal fade" id="modal-edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loader"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-details" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="loader"></div>
        </div>
    </div>
</div>

</body>
</html>