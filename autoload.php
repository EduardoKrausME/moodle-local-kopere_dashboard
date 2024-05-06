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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . "/locallib.php");

$debug = optional_param("debug", false, PARAM_INT);
if ($debug) {
    global $DB;
    $DB->set_debug(true);
}

spl_autoload_register("kopere_dashboard_autoload");
function kopere_dashboard_autoload($classname) {
    global $CFG;

    if (strpos($classname, 'kopere') === false) {
        return;
    }

    $classname = str_replace('\\', '/', $classname);

    preg_match("/local_(.*?)\/(.*)/", $classname, $classpartes);

    if (isset($classpartes[2])) {
        $file = "{$CFG->dirroot}/local/{$classpartes[1]}/classes/{$classpartes[2]}.php";
        if (file_exists($file)) {
            require_once($file);
        }
    }
}

/**
 * @throws coding_exception
 */
function load_class() {
    $classname = optional_param('classname', false, PARAM_TEXT);
    $method = optional_param('method', '', PARAM_TEXT);
    if (!$classname) {
        $classname = 'dashboard';
        $method = 'start';
    }

    if (strpos($classname, '-')) {
        $class = 'local_kopere_' . str_replace('-', '\\', $classname);
    } else {
        $class = 'local_kopere_dashboard\\' . $classname;
    }

    $class = str_replace('?', '', $class);

    $instance = new $class();
    $instance->$method();
}

/**
 * @return string
 *
 * @throws coding_exception
 */
function get_path_query() {
    $classname = optional_param('classname', '', PARAM_TEXT);
    $method = optional_param('method', '', PARAM_TEXT);
    return $classname . "-{$method}";
}

/**
 * @param $identifier
 * @param null $object
 *
 * @return string
 *
 * @throws coding_exception
 */
function get_string_kopere($identifier, $object = null) {
    return get_string($identifier, 'local_kopere_dashboard', $object);
}
