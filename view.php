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
require_once('../../config.php');
define('BENCHSTOP', microtime(true));
require_once('autoload.php');
require_once('locallib.php');

global $PAGE, $CFG, $OUTPUT, $USER;

if ($CFG->theme == 'smartlms') {
    $preferencedraweropennav = @$USER->preference['drawer-open-nav'];
    $preferencesidebaropennav = @$USER->preference['sidebar-open-nav'];
    @$USER->preference['drawer-open-nav'] = false;
    @$USER->preference['sidebar-open-nav'] = false;
}

require_once("{$CFG->libdir}/adminlib.php");

require_login();
$context = context_system::instance();
require_capability('local/kopere_dashboard:view', $context);
require_capability('local/kopere_dashboard:manage', $context);

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/view.php'));
$PAGE->set_context($context);
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string_kopere('modulename'));
$PAGE->set_heading(get_string_kopere('modulename'));

$PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
$PAGE->requires->css('/local/kopere_dashboard/assets/all-internal.css');

$PAGE->navbar->add(get_string_kopere('modulename'), new moodle_url('/local/kopere_dashboard/view.php'));

$PAGE->requires->jquery();
//$PAGE->requires->js('/local/kopere_dashboard/assets/bootstrap/bootstrap.js');

$PAGE->requires->js_call_amd('local_kopere_dashboard/start_load', 'init');

echo $OUTPUT->header();
echo get_kopere_lang();

$dashboardmenuhtmlboost = \local_kopere_dashboard\output\menu::create_menu();
$dashboardmenuhtmlboost = str_replace("'", '"', $dashboardmenuhtmlboost);
$dashboardmenuhtmlold = "<div id='inst0' class='block'>
            <div class='dashboard_menu_html kopere-logo'>
                <div class='logo-w'>
                    <img class='normal'
                         src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/logo-notext.svg'
                         alt='" . get_string_kopere('pluginname') . "'>
                </div>
            </div>
            <div class='content'>
                " . \local_kopere_dashboard\output\menu::create_menu() . "
            </div>
        </div>";
$dashboardmenuhtmlold = str_replace("'", '"', $dashboardmenuhtmlold);

echo "<div class='kopere_dashboard_div'>
         <div class='menu-w hidden-print dashboard_menu_html-content'>
            <div class='menu-and-user'>
                {$dashboardmenuhtmlboost}
            </div>
        </div>
        <div class='content-w'>";
load_class();
echo "
        </div>

        <div class='modal fade kopere_dashboard_modal_item' id='modal-edit' role='dialog'>
            <div class='kopere-modal-dialog'>
                <div class='kopere-modal-content'>
                    <div class='loader'></div>
                </div>
            </div>
        </div>
    </div>";


echo \local_kopere_dashboard\fonts\font_util::print_only_unique();
echo $OUTPUT->footer();
