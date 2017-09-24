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

namespace local_kopere_dashboard\html;

defined('MOODLE_INTERNAL') || die();

/**
 * Class Button
 * @package local_kopere_dashboard\html
 */
class Button {
    const BTN_PEQUENO = 'btn-xs';
    const BTN_MEDIO = 'btn-sm';
    const BTN_GRANDE = 'btn-lg';

    /**
     * @param $texto
     * @param $link
     * @param string $_class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function add($texto, $link, $_class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-primary " . $_class;

        return self::createButton($texto, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $texto
     * @param $link
     * @param string $_class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function edit($texto, $link, $_class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-success " . $_class;

        return self::createButton($texto, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $texto
     * @param $link
     * @param string $_class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function delete($texto, $link, $_class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-danger " . $_class;

        return self::createButton($texto, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $texto
     * @param $link
     * @param string $_class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function primary($texto, $link, $_class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-primary " . $_class;

        return self::createButton($texto, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $texto
     * @param $link
     * @param string $_class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function info($texto, $link, $_class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-info " . $_class;

        return self::createButton($texto, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $infoUrl
     * @param string $texto
     * @param string $hastag
     * @return string
     */
    public static function help($infoUrl, $texto = null, $hastag = 'wiki-wrapper') {
        global $CFG;

        if ($texto == null)
            $texto = get_string_kopere('help_title');

        return "<a href=\"https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/wiki/{$infoUrl}#{$hastag}\" target=\"_blank\" class=\"help\">
                  <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/help.svg\" height=\"23\" >
                  $texto
              </a>";
    }

    /**
     * @param $icon
     * @param $link
     * @param bool $isPopup
     * @return string
     */
    public static function icon($icon, $link, $isPopup = true) {
        global $CFG;
        if ($isPopup) {
            return "<a data-toggle=\"modal\" data-target=\"#modal-edit\"
                       href=\"?{$link}\"
                       data-href=\"open-ajax-table.php?{$link}\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" width=\"19\">
                    </a>";
        } else {
            return "<a href=\"?{$link}\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" width=\"19\">
                    </a>";
        }
    }

    /**
     * @param $texto
     * @param $link
     * @param $p
     * @param $class
     * @param $return
     * @param bool $modal
     * @return string
     */
    private static function createButton($texto, $link, $p, $class, $return, $modal = false) {
        $_target = '';
        if (strpos($link, 'http') === 0) {
            $_target = 'target="_blank"';
        } else {
            $link = "?$link";
        }

        $bt = '';
        if ($p) {
            $bt .= '<div style="width: 100%; min-height: 30px; padding: 0 0 20px;">';
        }

        if ($modal) {
            $bt
                .= '<a data-toggle="modal" data-target="#modal-edit"
                       class="' . $class . '" ' . $_target . '
                       href="' . $link . '"
                       data-href="open-ajax-table.php' . $link . '">' . $texto . '</a>';
        } else {
            $bt .= '<a href="' . $link . '" class="' . $class . '" ' . $_target . '>' . $texto . '</a>';
        }

        if ($p) {
            $bt .= '</div>';
        }

        if ($return) {
            return $bt;
        } else {
            echo $bt;
        }

        return '';
    }
}
