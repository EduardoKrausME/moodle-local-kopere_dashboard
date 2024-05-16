<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class html
 *
 * @package local_kopere_dashboard\util
 */
class html {
    /** @var array */
    private static $acentoshtml = [
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
        '_', '&uml;', '&ordf;', ',', ':', ' '];
    /** @var array */
    private static $acentosascii = [
        'á', 'à', 'â', 'ã', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î',
        'ï', 'ó', 'ò', 'ô', 'õ', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'Á',
        'À', 'Â', 'Ã', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï',
        'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç', "'", '´',
        '`', '/', '\\', '~', '^', '¨', 'ª', ',', ':', '_'];
    /** @var array */
    private static $semacento = [
        'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i',
        'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'A',
        'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I',
        'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'C', '_', '_',
        '_', '/', '_', '_', '_', '_', '_', '_', '_', '_'];

    /**
     * @param $html
     *
     * @return mixed
     */
    public static function caracter_spacial($html) {
        return str_replace(self::$acentosascii, self::$acentoshtml, $html);
    }

    /**
     * @param $html
     *
     * @return mixed
     */
    public static function retira_acentos($html) {
        $html = str_replace(self::$acentoshtml, self::$semacento, $html);
        $html = str_replace(self::$acentosascii, self::$semacento, $html);

        return $html;
    }

    /**
     * @param $html
     *
     * @return mixed
     */
    public static function trim($html) {
        for ($i = 0; $i < 10; $i++) {
            $html = str_replace("  ", ' ', $html);
        }
        $html = str_replace("\r", ' ', $html);
        $html = str_replace("\n", ' ', $html);
        $html = str_replace("\t", ' ', $html); // TAB.
        $html = str_replace("\x0B", ' ', $html); // Tabulação vertical.
        $html = preg_replace('/\s/', ' ', $html); // Escessos de espaços.
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
    public static function retira_caracteres_nao_ascii($txt) {
        $txt = self::retira_acentos($txt);
        $len = strlen($txt);
        $chr = '';
        for ($i = 0; $i < $len; $i++) {
            $id = ord($txt[$i]);
            if ($id == 32 || $id == 45 || $id == 95 || // Espaço traço e underline.
                (48 <= $id && $id <= 57) ||           // Números.
                (65 <= $id && $id <= 90) ||           // Maiúsculos.
                (97 <= $id && $id <= 122)             // Minusculos.
            ) {
                $chr .= $txt[$i];
            }
        }

        return $chr;
    }

    /**
     * @param $txt
     *
     * @return string
     */
    public static function link($txt) {
        $txt = str_replace('-', ' ', trim($txt));
        $txt = str_replace('_', ' ', $txt);
        $txt = preg_replace("/\s+/", " ", $txt);
        $txt = self::retira_caracteres_nao_ascii($txt);
        $txt = str_replace(' ', '-', $txt);

        for ($i = 0; $i < 10; $i++) {
            $txt = str_replace('--', '-', $txt);
        }

        return strtolower($txt);
    }

    /**
     * @param $texto
     * @param $caracteres
     *
     * @return string
     */
    public static function truncate_text($texto, $caracteres) {
        if (strlen($texto) > $caracteres) {
            $a = explode(' ', $texto);
            if (count($a) > 1) {
                array_pop($a);
                $texto = implode(' ', $a);
                $texto .= '...';

                return self::truncate_text($texto, $caracteres);
            } else {
                return $texto;
            }
        } else {
            return $texto;
        }
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    public static function makelink($string) {
        /***
         * Reconhece as URL sem HTTP://
         **/
        $string = preg_replace('/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i', '$1http:/' . '/$2', $string);
        /***
         * Converte todas as URL que tenham HTTP://
         **/
        $string = preg_replace('/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i', '<a target="_blank" href="$1">$1</A>', $string);
        /***
         * Cria link para todos os E-MAIL
         **/
        $string = preg_replace('/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i',
            "<a href=\"mailto:$1\">$1</A>", $string);

        return $string;
    }
}
