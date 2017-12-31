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
    public static $current_title = '';

    /**
     * @param      $title
     * @param null $info_url
     *
     * @return string
     */
    public static function set_titulo($title, $info_url = null) {
        self::$current_title = $title;

        if ($info_url == null) {
            return "<h3 class=\"element-header\"> $title </h3>";
        } else {
            return "<h3 class=\"element-header\">
                        $title
                        " . button::help($info_url) . "
                    </h3>";
        }
    }

    /**
     * @param      $breadcrumb
     * @param null $page_title
     * @param null $setting_url
     * @param null $info_url
     */
    public static function start_page($breadcrumb, $page_title = null, $setting_url = null, $info_url = null) {
        global $CFG, $SITE;
        $breadcrumb_return = '';

        if (!AJAX_SCRIPT) {
            $breadcrumb_return
                .= "<ul class=\"breadcrumb\">
                        <li>
                            <a target=\"_top\" href=\"{$CFG->wwwroot}/\">{$SITE->fullname}</a>
                        </li>
                        <li>
                            <a href=\"?dashboard::start\">" . get_string_kopere('dashboard') . "</a>
                        </li>";

            $title = "";
            if (is_string($breadcrumb)) {
                $breadcrumb_return .= '<li><span>' . $breadcrumb . '</span></li>';
                $title = $breadcrumb;
            } else if (is_array($breadcrumb)) {
                foreach ($breadcrumb as $breadcrumb_item) {
                    if (is_string($breadcrumb_item)) {
                        $breadcrumb_return .= '<li><span>' . $breadcrumb_item . '</span></li>';
                        $title = $breadcrumb_item;
                    } else {
                        $breadcrumb_return
                            .= '<li>
                                    <a href="?' . $breadcrumb_item[0] . '">' . $breadcrumb_item[1] . '</a>
                                </li>';
                        $title = $breadcrumb_item[1];
                    }
                }
            }

            if ($setting_url != null) {
                $breadcrumb_return
                    .= "<li class=\"setting\">
                            <a data-toggle=\"modal\" data-target=\"#modal-edit\"
                               data-href=\"open-ajax-table.php?$setting_url\"
                               href=\"#\">
                                <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg\"
                                     alt=\"Settings\" >
                            </a>
                        </li>";
            }

            $breadcrumb_return .= '</ul>';
            $breadcrumb_return .= '<div class="content-i"><div class="content-box">';

            if ($page_title != null) {
                if ($page_title == -1) {
                    $page_title = $title;
                }
                $breadcrumb_return .= self::set_titulo($page_title, $info_url);
            }

            $breadcrumb_return .= mensagem::get_mensagem_agendada();
        } else {
            if (is_string($breadcrumb)) {
                self::start_popup($breadcrumb);
            } else {
                self::start_popup($breadcrumb[count($breadcrumb) - 1]);
            }
        }

        echo $breadcrumb_return;
    }

    /**
     *
     */
    public static function end_page() {
        if (AJAX_SCRIPT) {
            self::end_popup();
        } else {
            echo '</div></div>';
        }
    }

    /**
     * @param       $menu_function
     * @param       $menu_icon
     * @param       $menu_name
     * @param array $sub_menus
     */
    public static function add_menu($menu_function, $menu_icon, $menu_name, $sub_menus = array()) {
        global $CFG;

        $class = self::test_menu_active($menu_function);

        $plugin = '';
        preg_match("/(.*?)-/", $menu_function, $menu_function_start);
        if (isset($menu_function_start[1])) {
            $plugin = "_" . $menu_function_start[1];
        }

        $submenu_html = '';
        foreach ($sub_menus as $sub_menu) {
            $class_sub = self::test_menu_active($sub_menu[0]);
            if (isset ($class_sub[1])) {
                $class = $class_sub;
            }

            if (strpos($sub_menu[2], 'http') === 0) {
                $icon_url = $sub_menu[2];
            } else {
                $icon_url = "{$CFG->wwwroot}/local/kopere_dashboard$plugin/assets/dashboard/img/iconactive/{$sub_menu[2]}.svg";
            }

            $submenu_html
                .= "<li class=\"$class_sub\">
                        <a href=\"?{$sub_menu[0]}\">
                            <img src=\"$icon_url\"
                                 class=\"icon-w\" alt=\"Icon\">
                            <span>{$sub_menu[1]}</span>
                        </a>
                    </li>";
        }
        if ($submenu_html != '') {
            $submenu_html = "<ul class='submenu'>$submenu_html</ul>";
        }

        echo "
                <li class=\"$class\">
                    <a href=\"?$menu_function\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard$plugin/assets/dashboard/img/icon$class/$menu_icon.svg\"
                             class=\"icon-w\" alt=\"Icon\">
                        <span>$menu_name</span>
                    </a>
                    $submenu_html
                </li>";
    }

    /**
     * @param $menu_function
     *
     * @return string
     */
    private static function test_menu_active($menu_function) {
        preg_match("/.*?::/", $menu_function, $paths);
        if (strpos($_SERVER['QUERY_STRING'], $paths[0]) === 0) {
            return 'active';
        }

        return '';
    }

    /**
     * @var bool
     */
    private static $_is_with_form = false;

    /**
     * @param      $title
     * @param null $form_action
     */
    public static function start_popup($title, $form_action = null) {
        if ($form_action) {
            echo '<form method="post" class="validate" >';
            echo '<input type="hidden" name="POST"  value="true" />';
            echo '<input type="hidden" name="action" value="?' . $form_action . '" />';
            self::$_is_with_form = true;
        } else {
            self::$_is_with_form = false;
        }

        echo '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">' . $title . '</h4>
              </div>
              <div class="modal-body">';

        if ($form_action) {
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
        }
    }

    /**
     * @param null $deletebuttonurl
     */
    public static function end_popup($deletebuttonurl = null) {
        if (self::$_is_with_form) {
            echo "</div>
                  <div class=\"modal-footer\">";

            if ($deletebuttonurl) {
                button::delete('Excluir', $deletebuttonurl, 'float-left', false, false, true);
            }

            echo "    <button class=\"btn btn-default\" data-dismiss=\"modal\">" . get_string('cancel') . "</button>
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