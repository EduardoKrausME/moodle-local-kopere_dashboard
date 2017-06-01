<?php
/**
 * User: Eduardo Kraus
 * Date: 23/05/17
 * Time: 18:24
 */

namespace local_kopere_dashboard\util;


class StringUtil
{
    public static function generateRandomString ( $length = 10 )
    {
        $characters       = '123456789ABCDEFGHJKMNPQRSTUVWXYZ';
        $charactersLength = strlen ( $characters );
        $randomString     = $characters[ rand ( 10, $charactersLength - 1 ) ];
        for ( $i = 0; $i < $length; $i++ )
            $randomString .= $characters[ rand ( 0, $charactersLength - 1 ) ];

        return $randomString;
    }
}