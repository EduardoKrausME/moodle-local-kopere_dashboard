<?php
/**
 * User: Eduardo Kraus
 * Date: 22/05/17
 * Time: 05:32
 */

namespace local_kopere_dashboard\util;


class Export
{
    private static $_sendJavascriptSend = false;

    private static function sendJavascript ()
    {
        global $CFG;

        if ( self::$_sendJavascriptSend )
            return;

        echo "<script src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/export-to-xls/xlsx.full.min.js\"></script>";
        echo "<script src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/export-to-xls/Blob.js\"></script>";
        echo "<script src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/export-to-xls/FileSaver.js\"></script>";

        ?>

        <script>
            function export_table_to_excel ( tableId, type, filename ) {
                var wb    = XLSX.utils.table_to_book ( document.getElementById ( tableId ), { sheet : "Sheet JS" } );
                var wbout = XLSX.write ( wb, { bookType : type, bookSST : true, type : 'binary' } );
                var fname = filename + '.' + type;
                try {
                    saveAs ( new Blob ( [ s2ab ( wbout ) ], { type : "application/octet-stream" } ), fname );
                } catch ( e ) {
                    if ( typeof console != 'undefined' ) console.log ( e, wbout );
                }
                return wbout;
            }
            function s2ab ( s ) {
                if ( typeof ArrayBuffer !== 'undefined' ) {
                    var buf1 = new ArrayBuffer ( s.length );
                    var view = new Uint8Array ( buf1 );
                    for ( var i = 0; i != s.length; ++i )
                        view[ i ] = s.charCodeAt ( i ) & 0xFF;
                    return buf1;
                } else {
                    var buf2 = new Array ( s.length );
                    for ( var j = 0; j != s.length; ++j )
                        buf2[ j ] = s.charCodeAt ( j ) & 0xFF;
                    return buf2;
                }
            }
        </script>
        <?php
    }

    public static function addButton ( $tableId, $type, $filename, $texto )
    {
        self::sendJavascript ();
        echo "<button class=\"btn btn-info navbar-btn\" onclick=\"export_table_to_excel( '$tableId', '$type', '$filename' );\">$texto</button>";

    }

    public static function exportHeader ( $formato, $filename )
    {
        if ( $formato == 'xls' ) {
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
                  <body>" ;
        }
    }

    public static function exportClose ( $formato )
    {
        if ( $formato == 'xls' ) {
            echo '</body></html>';
            die();
        }
    }
}