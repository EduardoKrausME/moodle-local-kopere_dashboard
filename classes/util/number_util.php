<?php
/**
 * User: Eduardo Kraus
 * Date: 06/05/2018
 * Time: 12:45
 */

namespace local_kopere_dashboard\util;

class number_util {
    public static function only_number ( $number ){
        $number = preg_replace ( '/[^0-9]/', '', $number );

        return $number;
    }

    public static function phonecell_to_number ( $phone )
    {
        $phone = preg_replace ( '/[^0-9]/', '', $phone );

        if ( preg_replace ( '/\d{2}[6-9]\d{7}/', '', $phone ) == '' )
            return preg_replace ( '/(\d{2})(\d{8})/', '($1) 9$2', $phone );

        // já baseado na regra futura da 404/05
        if ( preg_replace ( '/\d{2}[6-9]{2}\d{7}/', '', $phone ) == '' )
            return preg_replace ( '/(\d{2})(\d{9})/', '($1) $2', $phone );

        return '';
    }
}