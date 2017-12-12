<?php
/**
 * User: Eduardo Kraus
 * Date: 10/08/17
 * Time: 09:12
 */

namespace local_kopere_dashboard\event;

use core\event\base;

/**
 * Class import_user_created
 *
 * @package local_kopere_dashboard\event
 */
class import_user_created extends base
{
    /**
     * @return void
     */
    protected function init ()
    {
        $this->data[ 'crud' ]        = 'c';
        $this->data[ 'action' ]      = 'created';
        $this->data[ 'edulevel' ]    = self::LEVEL_OTHER;
        $this->data[ 'objecttable' ] = 'user';
    }

    /**
     * @return string
     */
    public static function get_name ()
    {
        return get_string('userimport_import_user_created_name', 'local_kopere_dashboard');
    }
}