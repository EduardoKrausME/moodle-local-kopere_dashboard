<?php
/**
 * User: Eduardo Kraus
 * Date: 25/03/2018
 * Time: 03:28
 */

namespace local_kopere_dashboard\report\custom;

class pagination
{
    /**
     * @param     $atualpage
     * @param     $totalregisters
     * @param int $perpag
     */
    public static function create ( $atualpage, $totalregisters, $perpag = 50 )
    {
        $countpages = intval ( $totalregisters / $perpag );
        $baseurl = preg_replace ( '/&page=\d+/', '', $_SERVER[ 'QUERY_STRING' ] );
        $baseurl = "?{$baseurl}&page=";

        if ( ( $totalregisters % $perpag ) != 0 ) {
            $countpages += 1;
        }

        echo "<div class='pagination-group'>";
        echo "<span class='pagination-info'>Página {$atualpage} de {$countpages}</span>";

        echo "<ul class='pagination'>";
        if ( $atualpage != 1 ) {
            echo "<li><a href='{$baseurl}1' >«</a></li>";
        }
        $i = $atualpage - 4;
        if ( $i < 1 ) {
            $i = 1;
        }
        if ( $i != 1 ) {
            echo "<li><span>...</span></li>";
        }

        $loop = 0;
        for ( ; $i <= $countpages; $i++ ) {
            if ( $i == $atualpage ) {
                echo "<li class='active'><span>{$i}</span></li>";
            } else {
                echo "<li><a href='{$baseurl}{$i}'>{$i}</a></li>";
            }

            $loop++;
            if ( $loop == 7 ) {
                if ( $i != $countpages ) {
                    echo "<li><span>...</span></li>";
                }
                break;
            }
        }
        if ( ( $atualpage ) != $countpages && $countpages > 1 ) {
            echo "<li><a href='{$baseurl}{$countpages}'>»</a></li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}