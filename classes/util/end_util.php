<?php
/**
 * User: Eduardo Kraus
 * Date: 24/09/17
 * Time: 07:17
 */

namespace local_kopere_dashboard\util;


/**
 * Class end_util
 *
 * @package local_kopere_dashboard\util
 */
class end_util {
    /**
     * @param string $print
     */
    public static function end_script_show( $print = '') {
        die($print);
    }

    /**
     * @param $motivo
     */
    public static function end_script_header( $motivo) {
        header('die-motivo:' . $motivo);
        die();
    }
}