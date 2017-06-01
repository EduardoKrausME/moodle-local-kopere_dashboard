<?php
/**
 * User: Eduardo Kraus
 * Date: 25/05/17
 * Time: 16:00
 */

namespace local_kopere_dashboard\chartsjs;


class Base
{
    private static $_isSend = false;

    private static $chartColors
        = array(
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        );

    private static $_lastColor = 0;

    protected static function getColor ()
    {
        if ( self::$_lastColor >= count ( self::$chartColors ) )
            self::$_lastColor = 0;

        return "'" . self::$chartColors[ self::$_lastColor++ ] . "'";
    }

    protected static function start ()
    {
        global $CFG;

        if ( self::$_isSend )
            return;

        echo "<script src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/chartjs/Chart.bundle.js\" charset=\"utf-8\"></script>";
    }
}