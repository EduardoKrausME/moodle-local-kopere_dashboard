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

use local_kopere_dashboard\html\button;

/**
 * Class dashboard_util
 *
 * @package local_kopere_dashboard\util
 */
class dashboard_util {
    /** @var string */
    public static $currenttitle = null;

    /** @var array */
    public static $breadcrumb = [];

    /**
     * @param      $titulo
     * @param null $link
     */
    public static function add_breadcrumb($titulo, $link = null) {
        if ($link) {
            self::$breadcrumb[] = [$titulo, $link];
        } else {
            self::$breadcrumb[] = $titulo;
        }

        self::$currenttitle = $titulo;
    }

    /**
     * @param $title
     * @param $settingurl
     * @param $infourl
     *
     * @return string
     */
    public static function set_titulo($title, $settingurl, $infourl) {
        global $CFG;

        self::$currenttitle = $title;

        $link = '';

        if ($settingurl != null) {
            $link
                .= "<div class='setting'>
                        <a href='{$settingurl}'>
                            <img src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg'
                                 alt='Settings' >
                        </a>
                    </div>";
        }

        if ($infourl == null) {
            return "<h3 class='element-header'>
                        {$title}
                        {$link}
                    </h3>";
        } else {
            $buttonhelp = button::help($infourl);
            return "<h3 class='element-header'>
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
            $url = local_kopere_dashboard_makeurl("dashboard", "start");
            $return
                .= "<ul class='breadcrumb'>
                        <li>
                            <a target='_top' href='{$CFG->wwwroot}/'>{$SITE->fullname}</a>
                        </li>
                        <li>
                            <a href='{$url}'>" . get_string_kopere('dashboard') . "</a>
                        </li>";

            foreach (self::$breadcrumb as $item) {
                if (is_string($item)) {
                    $return .= "<li><span>{$item}</span></li>";

                    $PAGE->navbar->add($item);
                } else {
                    $return .= "<li><a href='{$item[1]}'>{$item[0]}</a></li>";

                    $PAGE->navbar->add($item[0], $item[1]);
                }
            }

            if ($settingurl != null) {
                $return
                    .= "<li class='setting'>
                            <a href='$settingurl'>
                                <img src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg'
                                     alt='Settings' >
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
            self::end_popup();
        } else {
            echo '</div></div>';
        }
    }

    /**
     * @param menu_util $menu
     *
     * @return string
     * @throws \coding_exception
     */
    public static function add_menu(menu_util $menu) {
        $retorno = "";

        $class = self::test_menu_active($menu->get_classname());

        $plugin = 'kopere_dashboard';
        preg_match("/(.*?)-/", $menu->get_classname(), $menufunctionstart);
        if (isset($menufunctionstart[1])) {
            $plugin = "kopere_{$menufunctionstart[1]}";
        }

        $submenuhtml = '';
        /** @var submenu_util $submenu */
        foreach ($menu->get_submenus() as $submenu) {
            $classsub = self::test_menu_active($submenu->get_classname());
            if (isset ($classsub[1])) {
                $class = $classsub;
            }

            if (strpos($submenu->get_icon(), 'http') === 0) {
                $iconurl = $submenu->get_icon();
            } else {
                $iconurl = self::get_icon("/local/{$plugin}/assets/dashboard/img/iconactive/{$submenu->get_icon()}.svg");
            }

            $url = local_kopere_dashboard_makeurl($submenu->get_classname(), $submenu->get_methodname());
            $submenuhtml .= "
                <li class='contains_branch {$classsub}'>
                    <a href='{$url}{$submenu->get_urlextra()}'>
                        <img src='{$iconurl}' class='menu-icon' alt='Icon {$submenu->get_title()}'>
                        <span>{$submenu->get_title()}</span>
                    </a>
                </li>";
        }
        if ($submenuhtml != '') {
            $submenuhtml = "<ul class='submenu submenu-kopere'>{$submenuhtml}</ul>";
        }

        $iconurl = self::get_icon("/local/{$plugin}/assets/dashboard/img/icon{$class}/{$menu->get_icon()}.svg");
        $retorno .= "
                <li class='$class'>
                    <a href='" . local_kopere_dashboard_makeurl($menu->get_classname(), $menu->get_methodname()) . "'>
                        <img src='{$iconurl}' class='menu-icon' alt='Icon {$menu->get_name()}'>
                        <span>{$menu->get_name()}</span>
                    </a>
                    {$submenuhtml}
                </li>";

        return $retorno;
    }

    /**
     * @param $filename
     *
     * @return string
     */
    private static function get_icon($filename) {
        global $CFG;
        return $CFG->wwwroot . $filename;
    }

    /**
     * @param $classname
     *
     * @return string
     * @throws \coding_exception
     */
    private static function test_menu_active($classname) {

        $oldclassname = optional_param('classname', '', PARAM_TEXT);

        if ($classname == $oldclassname) {
            return 'active';
        }

        return '';
    }

    /**
     * @param $title
     */
    private static function start_popup($title) {
        echo "<div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                <h4 class='modal-title'>{$title}</h4>
              </div>
              <div class='modal-body'>";
    }

    /**
     *
     */
    private static function end_popup() {
        echo "</div>
              <script>
                  M.util.js_pending('local_kopere_dashboard/form_popup');
                  require(['local_kopere_dashboard/form_popup'], function(amd) {amd.init();
                  M.util.js_complete('local_kopere_dashboard/form_popup');})
              </script>";

        end_util::end_script_show();
    }
}
