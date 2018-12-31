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
 * @created    12/05/17 06:09
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\button;

/**
 * Class dashboard_util
 *
 * @package local_kopere_dashboard\util
 */
class dashboard_util {
    /**
     * @var string
     */
    public static $currenttitle = null;

    public static $breadcrumb = [];

    public static function add_breadcrumb($titulo, $link = null) {
        if ($link) {
            self::$breadcrumb[] = [$titulo, $link];
        } else {
            self::$breadcrumb[] = $titulo;
        }

        self::$currenttitle = $titulo;
    }

    /**
     * @param      $title
     * @param null $infourl
     *
     * @return string
     */
    public static function set_titulo($title, $settingurl, $infourl) {
        global $CFG;

        self::$currenttitle = $title;

        $link = '';

        if ($settingurl != null) {
            $link
                .= "<div class=\"setting\">
                        <a data-toggle=\"modal\" data-target=\"#modal-edit\"
                           data-href=\"load-ajax.php{$settingurl}\"
                           href=\"#\">
                            <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg\"
                                 alt=\"Settings\" >
                        </a>
                    </div>";
        }

        if ($infourl == null) {
            return "<h3 class=\"element-header\">
                        {$title}
                        {$link} 
                    </h3>";
        } else {
            $buttonhelp = button::help($infourl);
            return "<h3 class=\"element-header\">
                        {$title}
                        {$link}
                        {$buttonhelp}
                    </h3>";
        }
    }

    /**
     * @param null $settingurl
     * @param null $infourl
     */
    public static function start_page($settingurl = null, $infourl = null) {
        global $CFG, $SITE, $PAGE;

        $return = '';

        if (AJAX_SCRIPT) {
            self::start_popup(self::$currenttitle);
            return;
        } else {
            $return
                .= "<ul class=\"breadcrumb\">
                        <li>
                            <a target=\"_top\" href=\"{$CFG->wwwroot}/\">{$SITE->fullname}</a>
                        </li>
                        <li>
                            <a href=\"?classname=dashboard&method=start\">" . get_string_kopere('dashboard') . "</a>
                        </li>";


            foreach (self::$breadcrumb as $item) {
                if (is_string($item)) {
                    $return .= '<li><span>' . $item . '</span></li>';

                    if ($CFG->kopere_dashboard_open == 'internal') {
                        $PAGE->navbar->add($item);
                    }
                } else {
                    $return .= "<li><a href=\"{$item[1]}\">{$item[0]}</a></li>";

                    if ($CFG->kopere_dashboard_open == 'internal') {
                        $PAGE->navbar->add($item[0], $item[1]);
                    }
                }
            }

            if ($settingurl != null) {
                $return
                    .= "<li class=\"setting\">
                            <a data-toggle=\"modal\" data-target=\"#modal-edit\"
                               data-href=\"load-ajax.php{$settingurl}\"
                               href=\"#\">
                                <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg\"
                                     alt=\"Settings\" >
                            </a>
                        </li>";
            }

            $return .= '</ul>';
            $return .= '<div class="content-i"><div class="content-box">';

            $return .= self::set_titulo(self::$currenttitle, $settingurl, $infourl);

            $return .= mensagem::get_mensagem_agendada();
        }

        echo $return;
    }

    /**
     */
    public static function end_page() {
        if (AJAX_SCRIPT) {
            try {
                self::end_popup();
            } catch (\coding_exception $e) {
            }
        } else {
            echo '</div></div>';
        }
    }

    /**
     * @param menu_util $menu
     * @return string
     * @throws \coding_exception
     */
    public static function add_menu(menu_util $menu) {
        global $CFG;

        $retorno = "";

        $class = self::test_menu_active($menu->get_classname());

        $plugin = 'kopere_dashboard';
        preg_match("/(.*?)-/", $menu->get_classname(), $menufunctionstart);
        if (isset($menufunctionstart[1])) {
            $plugin = "kopere_" . $menufunctionstart[1];
        }

        $submenuhtml = '';
        /** @var submenu_util $submenu */
        foreach ($menu->get_submenus() as $submenu) {
            $classsub = self::test_menu_active($submenu->get_host());
            if (isset ($classsub[1])) {
                $class = $classsub;
            }

            if (strpos($submenu->get_icon(), 'http') === 0) {
                $iconurl = $submenu->get_icon();
            } else {
                $iconurl = "{$CFG->wwwroot}/local/{$plugin}/assets/dashboard/img/iconactive/{$submenu->get_icon()}.svg";
            }

            $submenuhtml
                .= "<li class=\"contains_branch {$classsub}\">
                        <a href=\"{$submenu->get_host()}\">
                            <img src=\"{$iconurl}\"
                                 class=\"menu-icon\" alt=\"Icon\">
                            <span>{$submenu->get_title()}</span>
                        </a>
                    </li>";
        }
        if ($submenuhtml != '') {
            $submenuhtml = "<ul class='submenu submenu-kopere'>{$submenuhtml}</ul>";
        }

        $retorno .= "
                <li class=\"$class\">
                    <a href=\"?classname={$menu->get_classname()}&method={$menu->get_methodname()}\">
                        <img src=\"{$CFG->wwwroot}/local/{$plugin}/assets/dashboard/img/icon{$class}/{$menu->get_icon()}.svg\"
                             class=\"menu-icon\" alt=\"Icon\">
                        <span>{$menu->get_name()}</span>
                    </a>
                    {$submenuhtml}
                </li>";

        return $retorno;
    }

    /**
     * @param $classname
     *
     * @return string
     * @throws \coding_exception
     */
    private static function test_menu_active($classname) {

        $_classname = optional_param('classname', '', PARAM_TEXT);

        if ($classname == $_classname) {
            return 'active';
        }

        if (strpos($classname, explode('-', $_classname)[0]) === 0) {
            return 'active';
        }

        return '';
    }

    /**
     * @param      $title
     */
    private static function start_popup($title) {
        echo '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">' . $title . '</h4>
              </div>
              <div class="modal-body">';
    }

    /**
     * @param null $deletebuttonurl
     * @throws \coding_exception
     */
    private static function end_popup() {

        if (false) {
            echo "</div>
                  <div class=\"modal-footer\">
                      <button class=\"btn btn-default\" data-dismiss=\"modal\">" . get_string('cancel') . "</button>
                      <input type=\"submit\" class=\"btn btn-primary margin-left-15\" value=\"" . get_string('savechanges') . "\">
                  </div>
                  </form>";
        } else {
            echo "</div>
                  <div class=\"modal-footer\">
                      <button class=\"btn btn-default\" data-dismiss=\"modal\">" . get_string_kopere('close') . "</button>
                  </div>";
        }
        echo "<script>
                  startForm ( '.modal-content' );
              </script>";

        end_util::end_script_show();
    }
}