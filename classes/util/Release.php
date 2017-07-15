<?php
/**
 * User: Eduardo Kraus
 * Date: 15/07/17
 * Time: 13:52
 */

namespace local_kopere_dashboard\util;


class Release
{
    public static function getVersion ()
    {
        $release  = get_config ( 'release' );
        $releases = explode ( '.', $release );

        return intval ( $releases[ 0 ] ) . "." . intval ( $releases[ 1 ] );
    }
}