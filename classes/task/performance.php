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
 * User: Eduardo Kraus
 * Date: 21/03/2020
 * Time: 10:17
 */

namespace local_kopere_dashboard\task;

use local_kopere_dashboard\server\performancemonitor;

class performance extends \core\task\scheduled_task {

    /**
     * @return string
     * @throws \coding_exception
     */
    public function get_name() {
        return get_string('crontask_performance', 'local_kopere_dashboard');
    }

    /**
     * @throws \coding_exception
     * @throws \Exception
     */
    public function execute() {
        $time = time();
        $this->add_data($time, 'cpu', performancemonitor::cpu(true));
        $this->add_data($time, 'memory', performancemonitor::memory(true));
        $this->add_data($time, 'disk', performancemonitor::disk_moodledata(true));
        $this->add_data($time, 'average', performancemonitor::load_average(true));
        $this->add_data($time, 'average', performancemonitor::online());
    }

    /**
     * @param $time
     * @param $type
     * @param $value
     * @return bool
     * @throws \dml_exception
     */
    private function add_data($time, $type, $value) {
        global $DB;

        $dashboardperformance = (object)[
            'time' => $time,
            'type' => $type,
            'value' => $value
        ];

        $exists = $DB->record_exists('kopere_dashboard_performance', ['time' => $time, 'type' => $type]);
        if ($exists) {
            return false;
        }

        try {
            $DB->insert_record("kopere_dashboard_performance", $dashboardperformance);
            return true;
        } catch (\dml_exception $e) {
            return false;
        }
    }
}
