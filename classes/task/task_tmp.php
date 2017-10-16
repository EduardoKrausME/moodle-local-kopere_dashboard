<?php
/**
 * User: Eduardo Kraus
 * Date: 09/08/17
 * Time: 01:17
 */

namespace local_kopere_dashboard\task;

defined('MOODLE_INTERNAL') || die();

class task_tmp  extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('crontask_tmp', 'local_kopere_dashboard');
    }

    public function execute() {
        global $CFG;

        $files = glob( $CFG->dataroot . '/kopere/dashboard/tmp/*');
        $now   = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                    unlink($file);
                }
            }
        }
    }


}