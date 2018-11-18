<?php
/**
 * User: Eduardo Kraus
 * Date: 15/11/2018
 * Time: 11:41
 */

namespace local_kopere_dashboard\util;


class url_util {
    public static function querystring() {
        return clean_param($_SERVER['QUERY_STRING'], PARAM_TEXT);
    }

}