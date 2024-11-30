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
 * Introduced  09/08/17 01:17
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\task;

/**
 * Class task_tmp
 *
 * @package local_kopere_dashboard\task
 */
class task_tmp extends \core\task\scheduled_task {

    /**
     * Function get_name
     *
     * @return string
     * @throws \coding_exception
     */
    public function get_name() {
        return get_string("crontask_tmp", "local_kopere_dashboard");
    }

    /**
     * Function execute
     */
    public function execute() {
        global $CFG;

        $files = glob("{$CFG->dataroot}/kopere/dashboard/tmp/*");
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days.
                    unlink($file);
                }
            }
        }
    }
}
