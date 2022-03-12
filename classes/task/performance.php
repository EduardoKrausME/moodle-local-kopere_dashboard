<?php
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
     */
    public function execute() {
        $time = time();
        $this->add_data($time, 'cpu', performancemonitor::cpu(true));
        $this->add_data($time, 'memory', performancemonitor::memory(true));
        $this->add_data($time, 'disk', performancemonitor::disk_moodledata(true));
        $this->add_data($time, 'average', performancemonitor::load_average(true));
        $this->add_data($time, 'average', performancemonitor::online());
    }

    private function add_data($time, $type, $value) {
        global $DB;

        $kopere_dashboard_performance = (object)[
            'time' => $time,
            'type' => $type,
            'value' => $value
        ];

        $exists = $DB->record_exists('kopere_dashboard_performance', ['time' => $time, 'type' => $type]);
        if ($exists) {
            return false;
        }

        try {
            $DB->insert_record("kopere_dashboard_performance", $kopere_dashboard_performance);
            return true;
        } catch (\dml_exception $e) {
            return false;
        }
    }
}