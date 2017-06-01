<?php

namespace local_kopere_dashboard\util;

class Html
{
    private static $acentosHtml = array(
            '&aacute;', '&agrave;', '&acirc;', '&atilde;',
            '&auml;', '&eacute;', '&egrave;', '&ecirc;', '&euml;',
            '&iacute;', '&igrave;', '&icirc;', '&iuml;', '&oacute;',
            '&ograve;', '&ocirc;', '&otilde;', '&ouml;', '&uacute;',
            '&ugrave;', '&ucirc;', '&uuml;', '&ccedil;', '&Aacute;',
            '&Agrave;', '&Acirc;', '&Atilde;', '&Auml;', '&Eacute;',
            '&Egrave;', '&Ecirc;', '&Euml;', '&Iacute;', '&Igrave;',
            '&Icirc;', '&Iuml;', '&Oacute;', '&Ograve;', '&Ocirc;',
            '&Otilde;', '&Ouml;', '&Uacute;', '&Ugrave;', '&Ucirc;',
            '&Uuml;', '&Ccedil;', "'", '&acute;', '`', '/', '\\', '_',
            '_', '&uml;', '&ordf;', ',', ':', ' ');
    private static $acentosASCII = array(
            'á', 'à', 'â', 'ã', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î',
            'ï', 'ó', 'ò', 'ô', 'õ', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'Á',
            'À', 'Â', 'Ã', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï',
            'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç', "'", '´',
            '`', '/', '\\', '~', '^', '¨', 'ª', ',', ':', '_');
    private static $semAcento = array(
            'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i',
            'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'A',
            'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I',
            'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'C', '_', '_',
            '_', '/', '_', '_', '_', '_', '_', '_', '_', '_');

    /**
     * @param $html
     *
     * @return mixed
     */
    public static function caracterEspecial ( $html )
    {
        return str_replace ( Html::$acentosASCII, Html::$acentosHtml, $html );
    }

    public static function retiraAcentos ( $html )
    {
        $html = str_replace ( Html::$acentosHtml, Html::$semAcento, $html );
        $html = str_replace ( Html::$acentosASCII, Html::$semAcento, $html );

        return $html;
    }

    public static function trim ( $html )
    {
        for ( $i = 0; $i < 10; $i++ )
            $html = str_replace ( "  ", ' ', $html );
        $html = str_replace ( "\r", ' ', $html );
        $html = str_replace ( "\n", ' ', $html );
        $html = str_replace ( "\t", ' ', $html ); // TAB
        $html = str_replace ( "\x0B", ' ', $html ); //
        $html = preg_replace ( '/\s/', ' ', $html ); // Escessos de espaços
        return $html;
    }

    /**
     * @param $txt
     *
     * @return string
     *  ASCII  32,45,95
     *  ASCII  48-57
     *  ASCII  65-90
     *  ASCII  97-122
     */
    public static function retiraCaracteresNaoASCII ( $txt )
    {
        $txt = self::retiraAcentos ( $txt );
        $len = strlen ( $txt );
        $chr = '';
        for ( $i = 0; $i < $len; $i++ ) {
            $id = ord (
                $txt[ $i ] );
            if ( $id == 32 || $id == 45 || $id == 95 || // Espaço traço e underline
                ( 48 <= $id && $id <= 57 ) ||           // Números
                ( 65 <= $id && $id <= 90 ) ||           // Maiúsculos
                ( 97 <= $id && $id <= 122 )             // Minusculos
            )
                $chr .= $txt[ $i ];
        }

        return $chr;
    }

    public static function link ( $txt )
    {
        $txt = str_replace ( '-', ' ', $txt );
        $txt = trim ( $txt );
        $txt = trim ( $txt );
        $txt = self::retiraCaracteresNaoASCII ( $txt );
        $txt = str_replace ( ' ', '-', $txt );
        $txt = str_replace ( '_', '-', $txt );

        for ( $i = 0; $i < 10; $i++ )
            $txt = str_replace ( '--', '-', $txt );

        return strtolower ( $txt );
    }

    public static function textoTruncado ( $texto, $caracteres )
    {
        if ( strlen ( $texto ) > $caracteres ) {
            $a = explode ( ' ', $texto );
            if ( count ( $a ) > 1 ) {
                array_pop ( $a );
                $texto = implode ( ' ', $a );
                $texto .= '...';

                return self::textoTruncado ( $texto, $caracteres );
            } else {
                return $texto;
            }
        } else
            return $texto;
    }

    public static function makeLink ( $string )
    {
        /***
         * Reconhece as URL sem HTTP://
         **/
        $string = preg_replace ( '/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i', '$1http:/' . '/$2', $string );
        /***
         * Converte todas as URL que tenham HTTP://
         **/
        $string = preg_replace ( '/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i', '<a target="_blank" href="$1">$1</A>', $string );
        /***
         * Cria link para todos os E-MAIL
         **/
        $string = preg_replace ( '/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i', "<a href=\"mailto:$1\">$1</A>", $string );

        return $string;
    }
}