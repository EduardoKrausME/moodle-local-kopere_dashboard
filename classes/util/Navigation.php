<?php
/**
 * User: Eduardo Kraus
 * Date: 04/07/17
 * Time: 08:55
 */

namespace local_kopere_dashboard\util;


class Navigation
{
    public static function create ( $atualPage, $totalRegisters, $baseUrl, $perPag = 20 )
    {
        $countPages = intval ( $totalRegisters / $perPag );

        if ( ( $totalRegisters % $perPag ) != 0 )
            $countPages += 1;

        echo "<span class=\"pagination-info\">Página {$atualPage} de {$countPages}</span>";

        echo "<ul class=\"pagination\">";
        if ( $atualPage != 1 ) {
            echo "<li><a href=\"{$baseUrl}1\" >«</a></li>";
        }
        $i = $atualPage - 4;
        if ( $i < 1 )
            $i = 1;
        if ( $i != 1 )
            echo "<li><span>...</span></li>";

        $loop = 0;
        for ( ; $i <= $countPages; $i++ ) {
            if ( $i == $atualPage ) {
                echo "<li class=\"active\"><span>{$i}</span></li>";
            } else {
                echo "<li><a href=\"{$baseUrl}{$i}\">{$i}</a></li>";
            }

            $loop++;
            if ( $loop == 7 ) {
                if ( $i != $countPages )
                    echo "<span>...</span></li>";
                break;
            }
        }
        if ( ( $atualPage ) != $countPages && $countPages > 1 ) {
            echo "<li><a href=\"{$baseUrl}{$countPages}\">»</a></li>";
        }
        echo "</ul>";
    }
}