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
 * dashboard_util file
 *
 * introduced 12/05/17 06:09
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
     * Function add_breadcrumb
     *
     * @param string $titulo
     * @param string $link
     * @param string $extra
     */
    public static function add_breadcrumb($titulo, $link = null, $extra = "") {
        if ($link) {
            self::$breadcrumb[] = [$titulo, $link];
        } else {
            self::$breadcrumb[] = $titulo;
        }

        self::$currenttitle = "{$titulo} {$extra}";
    }

    /**
     * Function set_titulo
     *
     * @param $title
     * @param $settingurl
     * @param $infourl
     *
     * @return string
     * @throws \coding_exception
     */
    public static function set_titulo($title, $settingurl, $infourl) {
        global $CFG;

        self::$currenttitle = $title;

        $link = '';

        if ($settingurl != null) {
            $link
                .= "<div class=\"setting\">
                        <a href='{$settingurl}' class=\"kopere_link\">
                            <img src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg'
                                 alt=\"Settings\" >
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
     * Function start_page
     *
     * @param null $settingurl
     * @param null $infourl
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function start_page($settingurl = null, $infourl = null) {
        global $PAGE, $OUTPUT;

        if (AJAX_SCRIPT) {
            self::start_popup(self::$currenttitle);
        } else {
            $title = "";
            foreach (self::$breadcrumb as $item) {
                if (is_string($item)) {
                    $PAGE->navbar->add($item);
                    $title = $item;
                } else {
                    $PAGE->navbar->add($item[0], $item[1]);
                    $title = $item[0];
                }
            }

            $PAGE->set_title($title . ": " . get_string_kopere("modulename"));
            echo $OUTPUT->header();

            echo "
                <div class=\"kopere_dashboard_div\">
                    <div class='menu-w hidden-print dashboard_menu_html-content'>
                        <div class='menu-and-user'>";
            \local_kopere_dashboard\output\menu::create_menu();
            echo "
                        </div>
                    </div>
                    <div class='content-w'>
                    <div class='content-i'>
                        <div class='content-box'>";

            echo self::set_titulo(self::$currenttitle, $settingurl, $infourl);
            echo mensagem::get_mensagem_agendada();
        }
    }

    /**
     * Function end_page
     *
     * @throws \dml_exception
     */
    public static function end_page() {
        global $OUTPUT;

        if (AJAX_SCRIPT) {
            self::end_popup();
        } else {
            echo "
                        </div>
                    </div>
                </div>
                <div class='modal fade kopere_dashboard_modal_item' id='modal-edit' role=\"dialog\">
                    <div class='kopere-modal-dialog'>
                        <div class='kopere-modal-content'>
                            <div class=\"loader\"></div>
                        </div>
                    </div>
                </div>";
            echo \local_kopere_dashboard\fonts\font_util::print_only_unique();
            echo $OUTPUT->footer();
        }
    }

    /**
     * Function add_menu
     *
     * @param menu_util $menu
     *
     * @return string
     * @throws \coding_exception
     */
    public static function add_menu(menu_util $menu) {
        $retorno = "";

        $class = self::test_menu_active($menu->get_classname());

        $plugin = "kopere_dashboard";
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

            if (strpos($submenu->get_icon(), "http") === 0) {
                $iconurl = $submenu->get_icon();
            } else {
                $iconurl = self::get_icon("/local/{$plugin}/assets/dashboard/img/iconactive/{$submenu->get_icon()}.svg");
            }

            $url = local_kopere_dashboard_makeurl($submenu->get_classname(), $submenu->get_methodname());
            $submenuhtml .= "
                <li class='contains_branch {$classsub}'>
                    <a href='{$url}{$submenu->get_urlextra()}' class=\"kopere_link\">
                        <img src='{$iconurl}' class='menu-icon' alt='Icon {$submenu->get_title()}'>
                        <span>{$submenu->get_title()}</span>
                    </a>
                </li>";
        }
        if ($submenuhtml != '') {
            $submenuhtml = "<ul class='submenu submenu-kopere'>{$submenuhtml}</ul>";
        }

        $iconurl = self::get_icon("/local/{$plugin}/assets/dashboard/img/icon{$class}/{$menu->get_icon()}.svg");
        $url = local_kopere_dashboard_makeurl($menu->get_classname(), $menu->get_methodname());
        $retorno .= "
                <li class='$class'>
                    <a href='{$url}' class=\"kopere_link\">
                        <img src='{$iconurl}' class='menu-icon' alt='Icon {$menu->get_name()}'>
                        <span>{$menu->get_name()}</span>
                    </a>
                    {$submenuhtml}
                </li>";

        return $retorno;
    }

    /**
     * Function get_icon
     *
     * @param $filename
     *
     * @return string
     */
    private static function get_icon($filename) {
        global $CFG;
        return $CFG->wwwroot . $filename;
    }

    /**
     * Function test_menu_active
     *
     * @param $classname
     *
     * @return string
     * @throws \coding_exception
     */
    private static function test_menu_active($classname) {

        $oldclassname = optional_param("classname", '', PARAM_TEXT);

        if ($classname == $oldclassname) {
            return "active";
        }

        return '';
    }

    /**
     * Function start_popup
     *
     * @param $title
     */
    private static function start_popup($title) {
        echo "<div class='modal-header'>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                <h4 class='modal-title'>{$title}</h4>
              </div>
              <div class='modal-body'>";
    }

    /**
     * Function end_popup
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
