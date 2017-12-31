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

require('../../config.php');
require('autoload.php');

global $PAGE, $CFG, $OUTPUT;

require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('local/kopere_dashboard:view', context_system::instance());
require_capability('local/kopere_dashboard:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/open.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagetype('admin-setting');
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string_kopere('open_dashboard'));
$PAGE->set_heading(get_string_kopere('open_dashboard'));

$PAGE->requires->jquery();
$PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
$PAGE->requires->js('/local/kopere_dashboard/assets/popup.js');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string_kopere('open_dashboard'));

?>
    <div class="text-center kopere-dashboard" style="text-align: center;">
        <a type="button" target="_blank"
           class="dashboard-load-popup btn btn-lg btn-primary"
           href="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/open-dashboard.php?dashboard::start"
           id="open-startup"><?php echo get_string_kopere('open_dashboard') ?></a>
    </div>

    <div id="modalWindow" style="display:none">
        <div id="base-popup">
            <a href="#" id="base-popup-close" role="button">X</a>
            <div id="operations">
                <iframe src="<?php echo $CFG->wwwroot ?>/local/kopere_dashboard/open-dashboard.php?dashboard::start"
                        frameborder="0" width="100%" height="100%"></iframe>
            </div>
        </div>
        <div class="ui-widget-overlay"></div>
    </div>
<?php

echo $OUTPUT->footer();

