<?php
/**
 * User: Eduardo Kraus
 * Date: 20/10/17
 * Time: 17:40
 */

namespace local_kopere_dashboard\util;

/**
 * Class config
 *
 * @package local_kopere_dashboard\util
 */
class config
{
    /**
     * @param $key
     *
     * @return string
     */
    public static function get_key ( $key )
    {
        try {
            $value = get_config ( 'local_kopere_dashboard', $key );
        } catch ( \dml_exception $e ) {
            return "";
        }

        try {
            return optional_param ( $key, $value, PARAM_RAW );
        } catch ( \coding_exception $e ) {
            return "";
        }
    }
}