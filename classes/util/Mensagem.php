<?php

/**
 * User: Eduardo Kraus
 * Date: 31/01/17
 * Time: 06:01
 */

namespace local_kopere_dashboard\util;

class Mensagem
{
    public static function agendaMensagem ( $mensagem )
    {
        if ( !isset( $_SESSION[ 'kopere_mensagem' ] ) )
            $_SESSION[ 'kopere_mensagem' ] = $mensagem;
        else
            $_SESSION[ 'kopere_mensagem' ] .= $mensagem;
    }

    public static function getMensagemAgendada ()
    {
        if ( isset( $_SESSION[ 'kopere_mensagem' ] ) ) {
            $texto = $_SESSION[ 'kopere_mensagem' ];
            @$_SESSION[ 'kopere_mensagem' ] = null;
            if ( $texto != null ) {
                return $texto;
            }
        }
        return "";
    }

    public static function clearMensagem ()
    {
        @$_SESSION[ 'kopere_mensagem' ] = null;
    }



    public static function warning ( $texto )
    {
        return "<div class=\"alert alert-warning\">
            <i class=\"fa fa-exclamation-circle\"></i>
            $texto
        </div>";
    }

    public static function printWarning ( $texto )
    {
        echo self::warning ( $texto );
    }

    public static function agendaMensagemWarning ( $texto )
    {
        self::agendaMensagem ( self::warning ( $texto ) );
    }



    public static function success ( $texto )
    {
        return "<div class=\"alert alert-success\">
            <i class=\"fa fa-check-circle\"></i>
            $texto
        </div>";
    }

    public static function printSuccess ( $texto )
    {
        echo self::success ( $texto );
    }

    public static function agendaMensagemSuccess ( $texto )
    {
        self::agendaMensagem ( self::success ( $texto ) );
    }



    public static function info ( $texto )
    {
        return "<div class=\"alert alert-info\">
            <i class=\"fa fa-info-circle\"></i>
            $texto
        </div>";
    }

    public static function printInfo ( $texto )
    {
        echo self::info ( $texto );
    }

    public static function agendaMensagemInfo ( $texto )
    {
        self::agendaMensagem ( self::info ( $texto ) );
    }


    public static function danger ( $texto )
    {
        return "<div class=\"alert alert-danger\">
            <i class=\"fa fa-times-circle\"></i>
            $texto
        </div>";
    }

    public static function printDanger ( $texto )
    {
        echo self::danger ( $texto );
    }

    public static function agendaMensagemDanger ( $texto )
    {
        self::agendaMensagem ( self::danger ( $texto ) );
    }
}