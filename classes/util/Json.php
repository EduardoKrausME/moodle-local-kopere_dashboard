<?php
/**
 * User: Eduardo Kraus
 * Date: 15/05/17
 * Time: 00:23
 */

namespace local_kopere_dashboard\util;


class Json
{
    public static function encodeAndReturn ( $data, $recordsTotal = 0, $recordsFiltered = 0 )
    {
        ob_clean ();
        header ( 'Content-Type: application/json; charset: utf-8' );

        $returnArray = array();
        if ( $recordsTotal ) {
            $returnArray[ 'draw' ]            = optional_param ( 'draw', 0, PARAM_INT );
            $returnArray[ 'recordsTotal' ]    = intval ( $recordsTotal );
            $returnArray[ 'recordsFiltered' ] = intval ( $recordsFiltered );
        }
        $returnArray[ 'data' ] = $data;


        $json = json_encode ( $returnArray );

        $json = str_replace ( '"data":{', '"data":[', $json );
        $json = str_replace ( '}}}', '}]}', $json );
        $json = preg_replace ( "/\"\d+\":{/", "{", $json );

        die( $json );
    }

    public static function error ( $message )
    {
        ob_clean ();
        header ( 'Content-Type: application/json; charset: utf-8' );

        $returnArray            = array();
        $returnArray[ 'error' ] = $message;

        $json = json_encode ( $returnArray );

        die( $json );
    }
}