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

$functions = array(
    'kopere_dashboard_performancemonitor_disk_moodledata' => array(
        'classname' => '\local_kopere_dashboard\external\performancemonitor',
        'methodname' => 'disk_moodledata',
        'classpath'   => 'local/kopere_dashboard/classes/external/performancemonitor.php',
        'description' => 'Disk Moodledata',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'local/kopere_dashboard:view'
    )
);