<?php
/**
 * User: Eduardo Kraus
 * Date: 20/10/17
 * Time: 17:40
 */

namespace local_kopere_dashboard\util;


class Config
{
    public static function getKey ( $key )
    {
        $value = get_config ( 'local_kopere_dashboard', $key );

        return optional_param ( $key, $value, PARAM_RAW );
    }
}