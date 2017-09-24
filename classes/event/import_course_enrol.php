<?php
/**
 * User: Eduardo Kraus
 * Date: 11/06/17
 * Time: 00:51
 */

namespace local_kopere_dashboard\event;

use core\event\base;

class import_course_enrol extends base
{
    /**
     * @return void
     */
    protected function init ()
    {
        $this->data[ 'crud' ]        = 'c';
        $this->data[ 'action' ]      = 'created';
        $this->data[ 'edulevel' ]    = self::LEVEL_OTHER;
        $this->data[ 'objecttable' ] = 'course';
    }

    /**
     * @return string
     */
    public static function get_name ()
    {
        return get_string('userimport_import_course_enrol_name', 'local_kopere_dashboard');
    }
}