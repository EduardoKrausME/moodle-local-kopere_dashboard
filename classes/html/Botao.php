<?php

namespace local_kopere_dashboard\html;

class Botao
{
    const BTN_PEQUENO = 'btn-xs';
    const BTN_MEDIO   = 'btn-sm';
    const BTN_GRANDE  = 'btn-lg';

    public static function add ( $texto, $link, $_class = '', $p = true, $return = false, $modal = false )
    {
        $class = "btn btn-primary " . $_class;

        return self::createBotao ( $texto, $link, $p, $class, $return, $modal );
    }

    public static function edit ( $texto, $link, $_class = '', $p = true, $return = false, $modal = false )
    {
        $class = "btn btn-success " . $_class;

        return self::createBotao ( $texto, $link, $p, $class, $return, $modal );
    }

    public static function delete ( $texto, $link, $_class = '', $p = true, $return = false, $modal = false )
    {
        $class = "btn btn-danger " . $_class;

        return self::createBotao ( $texto, $link, $p, $class, $return, $modal );
    }

    public static function primary ( $texto, $link, $_class = '', $p = true, $return = false, $modal = false )
    {
        $class = "btn btn-primary " . $_class;

        return self::createBotao ( $texto, $link, $p, $class, $return, $modal );
    }

    public static function info ( $texto, $link, $_class = '', $p = true, $return = false, $modal = false )
    {
        $class = "btn btn-info " . $_class;

        return self::createBotao ( $texto, $link, $p, $class, $return, $modal );
    }

    public static function help ( $infoUrl, $texto = 'Ajuda com esta página', $hastag = 'wiki-wrapper' )
    {
        global $CFG;

        return "<a href=\"https://github.com/eduardokraus/moodle-local-kopere_dashboard/wiki/{$infoUrl}#{$hastag}\" target=\"_blank\" class=\"help\">
                  <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/help.svg\" height=\"23\" >
                  $texto
              </a>";
    }

    public static function icon ( $icon, $link, $isPopup = true )
    {
        global $CFG;
        if ( $isPopup )
            return "<a data-toggle=\"modal\" data-target=\"#modal-edit\" 
                       href=\"{$link}\"
                       data-href=\"open-ajax-table.php{$link}\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" width=\"19\">
                    </a>";
        else
            return "<a href=\"{$link}\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" width=\"19\">
                    </a>";
    }

    private static function createBotao ( $texto, $link, $p, $class, $return, $modal = false )
    {
        $_target = '';
        if ( strpos ( $link, 'http' ) === 0 )
            $_target = 'target="_blank"';

        $bt = '';
        if ( $p )
            $bt .= '<div style="width: 100%; min-height: 30px; padding: 0 0 20px;">';

        if ( $modal )
            $bt
                .= '<a data-toggle="modal" data-target="#modal-edit" 
                       class="' . $class . '" ' . $_target . '
                       href="' . $link . '"
                       data-href="open-ajax-table.php' . $link . '">' . $texto . '</a>';
        else
            $bt .= '<a href="' . $link . '" class="' . $class . '" ' . $_target . '>' . $texto . '</a>';

        if ( $p )
            $bt .= '</div>';

        if ( $return )
            return $bt;
        else
            echo $bt;

        return '';
    }
}
