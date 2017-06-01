<?php
/**
 * User: Eduardo Kraus
 * Date: 13/05/17
 * Time: 14:27
 */

namespace local_kopere_dashboard\util;


class ServerUtil
{
    public static function getKoperePath ( $create = true )
    {
        global $CFG;

        if ( $create ) {
            @mkdir ( $CFG->dataroot . '/kopere' );
            @mkdir ( $CFG->dataroot . '/kopere/dashboard' );
        }

        return $CFG->dataroot . '/kopere/dashboard/';
    }

    public static function isFunctionEnabled ( $function )
    {
        return is_callable ( $function ) && false === stripos ( ini_get ( 'disable_functions' ), $function );
    }

}