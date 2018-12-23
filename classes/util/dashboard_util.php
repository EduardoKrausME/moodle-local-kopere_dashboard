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
    public static $currenttitle = '';

    /**
     * @param      $title
     * @param null $infourl
     *
     * @return string
     */
    public static function set_titulo($title, $settingurl, $infourl) {
        global $CFG;

        self::$currenttitle = $title;

        $breadcrumbreturn = '';

        if ($settingurl != null) {
            $breadcrumbreturn
                .= "<div class=\"setting\">
                        <a data-toggle=\"modal\" data-target=\"#modal-edit\"
                           data-href=\"open-ajax-table.php{$settingurl}\"
                           href=\"#\">
                            <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg\"
                                 alt=\"Settings\" >
                        </a>
                    </div>";
        }

        if ($infourl == null) {
            return "<h3 class=\"element-header\">
                        {$title}
                        {$breadcrumbreturn}
                    </h3>";
        } else {
            $buttonhelp = button::help($infourl);
            return "<h3 class=\"element-header\">
                        {$title}
                        {$breadcrumbreturn}
                        {$buttonhelp}
                    </h3>";
        }
    }

    /**
     * @param      $breadcrumb
     * @param null $pagetitle
     * @param null $settingurl
     * @param null $infourl
     */
    public static function start_page($breadcrumb, $pagetitle = null, $settingurl = null, $infourl = null) {
        global $CFG, $SITE, $PAGE;
        $breadcrumbreturn = '';

        if (!AJAX_SCRIPT) {
            $breadcrumbreturn
                .= "<ul class=\"breadcrumb\">
                        <li>
                            <a target=\"_top\" href=\"{$CFG->wwwroot}/\">{$SITE->fullname}</a>
                        </li>
                        <li>
                            <a href=\"?classname=dashboard&method=start\">" . get_string_kopere('dashboard') . "</a>
                        </li>";

            $title = "";
            if (is_string($breadcrumb)) {
                $breadcrumbreturn .= '<li><span>' . $breadcrumb . '</span></li>';
                $title = $breadcrumb;
            } else if (is_array($breadcrumb)) {
                foreach ($breadcrumb as $breadcrumbitem) {
                    if (is_string($breadcrumbitem)) {
                        $breadcrumbreturn .= '<li><span>' . $breadcrumbitem . '</span></li>';
                        $title = $breadcrumbitem;

                        if( $CFG->kopere_dashboard_open == 'internal') {
                            $PAGE->navbar->add($breadcrumbitem);
                        }
                    } else {
                        $breadcrumbreturn
                            .= '<li>
                                    <a href="' . $breadcrumbitem[0] . '">' . $breadcrumbitem[1] . '</a>
                                </li>';
                        $title = $breadcrumbitem[1];

                        if( $CFG->kopere_dashboard_open == 'internal') {
                            $PAGE->navbar->add($breadcrumbitem[1], $breadcrumbitem[0]);
                        }
                    }
                }
            }

            if ($settingurl != null) {
                $breadcrumbreturn
                    .= "<li class=\"setting\">
                            <a data-toggle=\"modal\" data-target=\"#modal-edit\"
                               data-href=\"open-ajax-table.php{$settingurl}\"
                               href=\"#\">
                                <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg\"
                                     alt=\"Settings\" >
                            </a>
                        </li>";
            }

            $breadcrumbreturn .= '</ul>';
            $breadcrumbreturn .= '<div class="content-i"><div class="content-box">';

            if ($pagetitle != null) {
                if ($pagetitle == -1) {
                    $pagetitle = $title;
                }
                $breadcrumbreturn .= self::set_titulo($pagetitle, $settingurl, $infourl);
            }

            $breadcrumbreturn .= mensagem::get_mensagem_agendada();
        } else {
            if (is_string($breadcrumb)) {
                self::start_popup($breadcrumb);
            } else {
                self::start_popup($breadcrumb[count($breadcrumb) - 1]);
            }
        }

        echo $breadcrumbreturn;
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
     * @param       $classname
     * @param       $methodname
     * @param       $menuicon
     * @param       $menuname
     * @param array $submenus
     * @return string
     */
    public static function add_menu($classname, $methodname, $menuicon, $menuname, $submenus = array()) {
        global $CFG;

        $retorno = "";

        $class = self::test_menu_active($classname);

        $plugin = 'kopere_dashboard';
        preg_match("/(.*?)-/", $classname, $menufunctionstart);
        if (isset($menufunctionstart[1])) {
            $plugin = "kopere_" . $menufunctionstart[1];
        }

        $submenuhtml = '';
        foreach ($submenus as $submenu) {
            $classsub = self::test_menu_active($submenu[0]);
            if (isset ($classsub[1])) {
                $class = $classsub;
            }

            if (strpos($submenu[2], 'http') === 0) {
                $iconurl = $submenu[2];
            } else {
                $iconurl = "{$CFG->wwwroot}/local/{$plugin}/assets/dashboard/img/iconactive/{$submenu[2]}.svg";
            }

            $submenuhtml
                .= "<li class=\"contains_branch {$classsub}\">
                        <a href=\"{$submenu[0]}\">
                            <img src=\"{$iconurl}\"
                                 class=\"icon-w\" alt=\"Icon\">
                            <span>{$submenu[1]}</span>
                        </a>
                    </li>";
        }
        if ($submenuhtml != '') {
            $submenuhtml = "<ul class='submenu-kopere'>{$submenuhtml}</ul>";
        }

        $retorno .= "
                <li class=\"$class\">
                    <a href=\"?classname={$classname}&method={$methodname}\">
                        <img src=\"{$CFG->wwwroot}/local/{$plugin}/assets/dashboard/img/icon{$class}/{$menuicon}.svg\"
                             class=\"icon-w\" alt=\"Icon\">
                        <span>{$menuname}</span>
                    </a>
                    {$submenuhtml}
                </li>";

        return $retorno;
    }

    /**
     * @param $classname
     *
     * @return string
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
     * @var bool
     */
    private static $iswithform = false;

    /**
     * @param      $title
     * @param null $formaction
     */
    public static function start_popup($title, $formaction = null) {
        if ($formaction) {
            echo '<form method="post" class="validate" action="' . $formaction . '" >';
            echo '<input type="hidden" name="POST"  value="true" />';
            // echo '<input type="hidden" name="action" value="' . $formaction . '" />';
            self::$iswithform = true;
        } else {
            self::$iswithform = false;
        }

        echo '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">' . $title . '</h4>
              </div>
              <div class="modal-body">';

        if ($formaction) {
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
        }
    }

    /**
     * @param null $deletebuttonurl
     */
    public static function end_popup($deletebuttonurl = null) {
        if (self::$iswithform) {
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