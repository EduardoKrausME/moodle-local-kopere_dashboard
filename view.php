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

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/view.php?{$_SERVER['QUERY_STRING']}"));
$PAGE->set_context($context);
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('admin');


$PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
$PAGE->requires->css('/local/kopere_dashboard/assets/all-internal.css');


$PAGE->requires->jquery();

$PAGE->requires->js_call_amd('local_kopere_dashboard/start_load', 'init');

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin("ui");
$PAGE->requires->jquery_plugin("ui-css");

get_kopere_lang();

load_class();
