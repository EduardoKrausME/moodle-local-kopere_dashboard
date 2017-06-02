<?php
/**
 * User: Eduardo Kraus
 * Date: 22/05/17
 * Time: 05:32
 */

namespace local_kopere_dashboard\util;


class Export
{
    private static $_format;

    public static function exportHeader ( $format, $filename = null )
    {
        if ( $filename == null )
            $filename = DashboardUtil::$currentTitle;

        self::$_format = $format;
        if ( self::$_format == 'xls' ) {
            ob_clean ();
            header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
            header ( 'Content-Disposition: attachment; filename="' . $filename . '.xls"' );
            header ( 'Cache-Control: max-age=0' );

            echo "<html>
                  <head>
                      <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">
                      <title>$filename</title>
                      <!--table
                          {mso-displayed-decimal-separator:\"\,\";
                          mso-displayed-thousand-separator:\"\.\";}
                      -->
                      </style>
                  </head>
                  <body>";
        }
    }

    public static function exportClose ()
    {
        if ( self::$_format == 'xls' ) {
            echo '</body></html>';
            die();
        }
    }
}