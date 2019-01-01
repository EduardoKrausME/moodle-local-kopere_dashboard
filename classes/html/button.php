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
 * Class button
 * @package local_kopere_dashboard\html
 */
class button {
    const BTN_PEQUENO = 'btn-xs';
    const BTN_MEDIO = 'btn-sm';
    const BTN_GRANDE = 'btn-lg';

    /**
     * @param $text
     */
    public static function close_popup($text) {
        echo "<button class=\"btn btn-primary margin-left-10\" data-dismiss=\"modal\">{$text}</button>";
    }

    /**
     * @param $text
     * @param $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function add($text, $link, $class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-primary " . $class;

        return self::create_button($text, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $text
     * @param $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function edit($text, $link, $class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-success " . $class;

        return self::create_button($text, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $text
     * @param $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function delete($text, $link, $class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-danger " . $class;

        return self::create_button($text, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $text
     * @param $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function primary($text, $link, $class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-primary " . $class;

        return self::create_button($text, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $text
     * @param $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     * @param bool $modal
     * @return string
     */
    public static function info($text, $link, $class = '', $p = true, $return = false, $modal = false) {
        $class = "btn btn-info " . $class;

        return self::create_button($text, $link, $p, $class, $return, $modal);
    }

    /**
     * @param $infourl
     * @param string $text
     * @param string $hastag
     * @return string
     */
    public static function help($infourl, $text = null, $hastag = 'wiki-wrapper') {
        global $CFG;

        if ($text == null) {
            $text = get_string_kopere('help_title');
        }

        return "<a href=\"https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/wiki/{$infourl}#{$hastag}\"
                   target=\"_blank\" class=\"help\">
                  <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/help.svg\" height=\"23\" >
                  $text
              </a>";
    }

    /**
     * @param $icon
     * @param $link
     * @param bool $ispopup
     * @return string
     */
    public static function icon($icon, $link, $ispopup = true) {
        global $CFG;
        if ($ispopup) {
            return "<a data-toggle=\"modal\" data-target=\"#modal-edit\"
                       onclick=\"loadRemoteModal('load-ajax.php{$link}')\"
                       href=\"#{$link}\"
                       data-href=\"load-ajax.php{$link}\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" width=\"19\">
                    </a>";
        } else {
            return "<a href=\"{$link}\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" width=\"19\">
                    </a>";
        }
    }

    /**
     * @param $icon
     * @param $link
     * @return string
     */
    public static function icon_popup_table($icon, $link) {
        global $CFG;
        return "<img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg\" 
                     width=\"19\" role=\"button\"
                     onclick=\"loadRemoteModal('load-ajax.php{$link}')\">";
    }

    /**
     * @param $text
     * @param $link
     * @param $p
     * @param $class
     * @param $return
     * @param bool $modal
     * @return string
     */
    private static function create_button($text, $link, $p, $class, $return, $modal = false) {
        $target = '';
        if (strpos($link, 'http') === 0) {
            $target = 'target="_blank"';
        }

        $bt = '';
        if ($p) {
            $bt .= '<div style="width: 100%; min-height: 30px; padding: 0 0 20px;">';
        }

        if ($modal) {
            $bt
                .= '<a data-toggle="modal" data-target="#modal-edit"
                       class="' . $class . '" ' . $target . '
                       href="' . $link . '"
                       data-href="load-ajax.php' . $link . '">' . $text . '</a>';
        } else {
            $bt .= '<a href="' . $link . '" class="' . $class . '" ' . $target . '>' . $text . '</a>';
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
