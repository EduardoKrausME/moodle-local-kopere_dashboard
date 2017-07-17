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

spl_autoload_register("kopere_dashboard_autoload");
function kopere_dashboard_autoload($className) {
    global $CFG;

    if (strpos($className, 'kopere') === false) {
        return;
    }

    $className = str_replace('\\', '/', $className);

    preg_match("/local_(.*?)\/(.*)/", $className, $classPartes);

    $file = "{$CFG->dirroot}/local/{$classPartes[1]}/classes/{$classPartes[2]}.php";
    if (file_exists($file)) {
        require_once($file);
    }
}

function loadByQuery($queryString) {
    preg_match("/(.*?)::([a-zA-Z_0-9]+)/", $queryString, $paths);

    $className = $paths[1];
    if (strpos($className, '-')) {
        $class = 'local_kopere_dashboard_' . str_replace('-', '\\', $className);
    } else {
        $class = 'local_kopere_dashboard\\' . $className;
    }

    $class = str_replace('?', '', $class);

    $instance = new $class();
    $method = $paths[2];
    $instance->$method();

}

function get_string_kopere($identifier, $object=null) {
    return get_string($identifier, 'local_kopere_dashboard', $object);
}