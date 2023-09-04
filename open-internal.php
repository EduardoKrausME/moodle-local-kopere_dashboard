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
    echo "<meta http-equiv='refresh' content='0; url={$urlinternal}'>";
}

global $PAGE, $CFG, $OUTPUT, $USER;

if ($CFG->theme == 'smartlms') {
    $preferencedraweropennav = @$USER->preference['drawer-open-nav'];
    $preferencesidebaropennav = @$USER->preference['sidebar-open-nav'];
    @$USER->preference['drawer-open-nav'] = false;
    @$USER->preference['sidebar-open-nav'] = false;
}

require_once("{$CFG->libdir}/adminlib.php");

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
$htmlapp = ob_get_contents();
ob_clean();

$PAGE->requires->jquery();
$PAGE->requires->js('/local/kopere_dashboard/assets/bootstrap/bootstrap.js');

$PAGE->requires->js_call_amd('local_kopere_dashboard/start_load', 'init');

echo $OUTPUT->header();
echo "<link rel='icon' href='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/favicon.png'/>";
require_once(__DIR__ . "/autoload-lang-js.php");
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
        <div class='content-w'>
            {$htmlapp}
        </div>

        <div class='modal fade kopere_dashboard_modal_item' id='modal-edit' role='dialog'>
            <div class='kopere-modal-dialog'>
                <div class='kopere-modal-content'>
                    <div class='loader'></div>
                </div>
            </div>
        </div>
    </div>";

echo $OUTPUT->footer();

$html = ob_get_contents();
ob_clean();
$dashboardmenuhtmlold = "<style>.dashboard_menu_html-content{display:none !important}</style>{$dashboardmenuhtmlold}";


if ($CFG->theme == 'smartlms') {
    $USER->preference['drawer-open-nav'] = $preferencedraweropennav;
    $USER->preference['sidebar-open-nav'] = $preferencesidebaropennav;
} else if ($CFG->theme != 'moove') {
    if (preg_match_all('/(.*)(<div.*?class="block_navigation.*)/', $html)) {
        $html = preg_replace('/(.*)(<div.*?class="block_navigation.*)/', "$1{$dashboardmenuhtmlold}$2", $html);
    } else if (preg_match_all('/(.*)(<section.*?class="(\s+)?block_navigation.*)/s', $html)) {
        $html = preg_replace('/(.*)(<section.*?class="(\s+)?block_navigation.*)/s',
            "$1<div class='card mb-3'>{$dashboardmenuhtmlold}</div>$2", $html);
    } else if (strpos($html, 'data-region="drawer"')) {
        $classdiv = " class='list-group-item kopere-list-group-item' ";
        $html = preg_replace('/(.*data-region="drawer".*?>)(.*)/',
            "$1<div{$classdiv}>{$dashboardmenuhtmlold}</div>$2", $html);
    }
}

echo $html;
