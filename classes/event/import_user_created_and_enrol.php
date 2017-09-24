<?php
/**
 * User: Eduardo Kraus
 * Date: 18/08/17
 * Time: 12:21
 */
namespace local_kopere_dashboard\event;


use core\event\base;

class import_user_created_and_enrol extends base {
    /**
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['action'] = 'created';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'course';
    }

    /**
     * @return string
     */
    public static function get_name() {
        return get_string('userimport_import_user_created_and_enrol_name', 'local_kopere_dashboard');
    }
}