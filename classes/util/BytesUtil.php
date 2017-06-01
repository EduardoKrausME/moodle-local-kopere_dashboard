<?php

/***************************
 * User: kraus
 * Date: 03/11/2015
 * Time: 00:02
 ***************************/

namespace local_kopere_dashboard\util;

class BytesUtil
{

    private static $divisor = 1000;

    /**
     * @param int $_bytes
     *
     * @return string
     */
    public static function sizeToByte ( $_bytes )
    {
        $_bytes = intval ( $_bytes );

        if ( $_bytes == 0 )
            return '0B';
        $bytes = $_bytes / self::$divisor;
        if ( $bytes < 1000 )
            return self::removeZero ( number_format ( $bytes, 0, ',', '.' ) . ' KB', 0 );

        $bytes = $_bytes / self::$divisor / self::$divisor;
        if ( $bytes < 1000 )
            return self::removeZero ( number_format ( $bytes, 0, ',', '.' ) . ' MB', 0 );

        $bytes = $_bytes / self::$divisor / self::$divisor / self::$divisor;
        if ( $bytes < 1000 )
            return self::removeZero ( number_format ( $bytes, 1, ',', '.' ) . ' GB', 1 );

        $bytes = $_bytes / self::$divisor / self::$divisor / self::$divisor / self::$divisor;

        return self::removeZero ( number_format ( $bytes, 2, ',', '.' ) . ' TB', 2 );
    }

    /**
     * @param string $texto
     *
     * @return string
     */
    private static function removeZero ( $texto, $count )
    {
        if ( $count == 3 )
            return str_replace ( ',000', '', $texto );
        elseif ( $count == 2 )
            return str_replace ( ',00', '', $texto );
        else
            return str_replace ( ',0', '', $texto );
    }

    public static function getDurationSegundos ( $value )
    {
        $partes = explode ( ':', $value );

        return ( $partes[ 0 ] * 60 * 60 ) + ( $partes[ 1 ] * 60 ) + $partes[ 2 ];
    }

}