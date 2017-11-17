<?php
/**
 * User: Eduardo Kraus
 * Date: 24/09/17
 * Time: 07:17
 */

namespace local_kopere_dashboard\util;


/**
 * Class EndUtil
 *
 * @package local_kopere_dashboard\util
 */
class EndUtil {
    /**
     * @param string $print
     */
    public static function endScriptShow( $print = '') {
        die($print);
    }

    /**
     * @param $motivo
     */
    public static function endScriptHeader( $motivo) {
        header('die-motivo:' . $motivo);
        die();
    }
}