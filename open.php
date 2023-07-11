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
 * @created    30/01/17 08:34
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

ob_start();

require('../../config.php');
require('autoload.php');

global $PAGE, $CFG, $OUTPUT;

require_once("{$CFG->libdir}/adminlib.php");

require_login();
require_capability('local/kopere_dashboard:view', context_system::instance());
require_capability('local/kopere_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/open.php?classname=dashboard&method=start'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string_kopere('open_dashboard'));
$PAGE->set_heading(get_string_kopere('open_dashboard'));

$PAGE->requires->jquery();
$PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
$PAGE->requires->js_call_amd('local_kopere_dashboard/popup', 'init');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string_kopere('open_dashboard'));

$classname = optional_param('classname', 'dashboard', PARAM_TEXT);
$method = optional_param('method', 'start', PARAM_TEXT);


$url = "{$CFG->wwwroot}/local/kopere_dashboard/open-dashboard.php?" . clean_param($_SERVER['QUERY_STRING'], PARAM_TEXT);
if ($CFG->kopere_dashboard_open == 'internal') {
    $urlinternal = "{$CFG->wwwroot}/local/kopere_dashboard/open-internal.php?" . clean_param($_SERVER['QUERY_STRING'], PARAM_TEXT);
    @header("Location: {$urlinternal}");
    echo "<meta http-equiv='refresh' content='0; url={$urlinternal}'>";
} else if ($CFG->kopere_dashboard_open == '_blank') { ?>
    <div class="text-center kopere-dashboard" style="text-align: center;">
        <a type="button" target="_blank"
           class="dashboard-load-blank btn btn-lg btn-primary"
           href="<?php echo $url ?>"
           id="open-startup-blank"><?php echo get_string_kopere('open_dashboard') ?></a>
    </div>
    <script>
        window.open("<?php echo $url ?>", "_blank");
    </script>
    <?php
} else if ($CFG->kopere_dashboard_open == '_top') {
    @header("Location: {$url}");
    ?>
    <div class="text-center kopere-dashboard" style="text-align: center;">
        <a type="button"
           class="dashboard-load-top btn btn-lg btn-primary"
           href="<?php echo $url ?>"
           id="open-startup-top"><?php echo get_string_kopere('open_dashboard') ?></a>
    </div>

    <meta http-equiv="refresh" content="0; url=<?php echo $url ?>">
    <?php
} else { // popup ?>
    <div class="text-center kopere-dashboard" style="text-align: center;">
        <a type="button"
           class="dashboard-load-popup btn btn-lg btn-primary"
           href="<?php echo $url ?>"
           id="open-startup"><?php echo get_string_kopere('open_dashboard') ?></a>
    </div>

    <div id="modalWindow" style="display:none">
        <div id="base-popup">
            <a href="#" id="base-popup-close" role="button">X</a>
            <div id="operations">
                <iframe src="<?php echo $url ?>"
                        frameborder="0" width="100%" height="100%"></iframe>
            </div>
        </div>
    <div class="ui-widget-overlay"></div>
    </div><?php
}

echo $OUTPUT->footer();

