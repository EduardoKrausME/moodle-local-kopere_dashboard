<?php
/**
 * User: Eduardo Kraus
 * Date: 10/08/17
 * Time: 18:38
 */

namespace local_kopere_dashboard\util;


class DateUtil {
    public static function convertToTime($datetime) {
        if (is_numeric($datetime))
            if ($datetime > 1000000000)
                return $datetime;

        $date = \DateTime::createFromFormat('Y-m-d H:i', $datetime);
        if ($date)
            return $date->getTimestamp();

        $date = \DateTime::createFromFormat('Y-m-d', $datetime);
        if ($date)
            return $date->getTimestamp();

        $date = \DateTime::createFromFormat('d/m/Y H:i', $datetime);
        if ($date)
            return $date->getTimestamp();

        return strtotime($datetime);
    }
}