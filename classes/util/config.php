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
class config {
    /**
     * @param $key
     *
     * @return mixed
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function get_key($key) {
        $value = get_config('local_kopere_dashboard', $key);

        return optional_param($key, $value, PARAM_RAW);
    }
}