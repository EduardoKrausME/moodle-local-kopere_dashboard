<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @created    23/05/17 18:24
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

defined ( 'MOODLE_INTERNAL' ) || die();

class StringUtil
{
    public static function generateRandomString ( $length = 10 )
    {
        $characters = '123456789';
        $characters .= 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $characters .= 'abcdefghjkmnpqrstuvwxyz';

        $lengthString = strlen ( $characters );
        $string       = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $string .= $characters[ rand ( 0, $lengthString - 1 ) ];
        }

        return $string;
    }

    public static function generateUID ()
    {
        return strtolower (
            self::generateRandomString ( 8 ) . '-' .
            self::generateRandomString ( 4 ) . '-' .
            self::generateRandomString ( 4 ) . '-' .
            self::generateRandomString ( 12 )
        );
    }


    public static function clearParamsAll ( $param, $default, $type )
    {
        if( $param == null ){
            return self::clear_all_params ( $_POST, $type );
        }

        if ( !isset( $_POST[ $param ] ) ) {
            return $default;
        }

        if ( is_string ( $_POST[ $param ] ) ) {
            return clean_param ( $param, $type );
        }

        return self::clear_all_params ( $_POST[ $param ], $type );
    }

    private static function clear_all_params ( $in, $type )
    {
        $out = array();
        if ( is_array ( $in ) ) {
            foreach ( $in as $key => $value ) {
                $out [ $key ] = self::clear_all_params ( $value, $type );
            }
        } elseif ( is_string ( $in ) ) {
            return clean_param ( $in, $type );
        } else {
            return $in;
        }

        return $out;
    }
}