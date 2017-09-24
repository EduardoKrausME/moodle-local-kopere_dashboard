<?php
/**
 * User: Eduardo Kraus
 * Date: 24/09/17
 * Time: 07:17
 */

namespace local_kopere_dashboard\util;


class EndUtil {
    public static function endScriptShow($print = '') {
        die($print);
    }

    public static function endScriptHeader($motivo) {
        header('die-motivo:' . $motivo);
        die();
    }
}