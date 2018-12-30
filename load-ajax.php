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
define('AJAX_SCRIPT', true);

require('../../config.php');
require_once('autoload.php');

global $PAGE, $CFG, $OUTPUT;

ob_clean();

try {
    require_capability('local/kopere_dashboard:view', context_system::instance());
    require_capability('local/kopere_dashboard:manage', context_system::instance());
} catch (Exception $e) { ?>
    <script>
        location.reload();
    </script>
    <meta http-equiv="refresh" content="0; url=#">
    <?php
    die();
}

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/load-ajax.php'));
$PAGE->set_pagetype('reports');
$PAGE->set_context(context_system::instance());


load_class();
