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

use local_kopere_dashboard\html\Button;

class DashboardUtil {
    public static $currentTitle = '';

    public static function setTitulo($title, $infoUrl = null) {
        self::$currentTitle = $title;

        if ($infoUrl == null) {
            return "<h3 class=\"element-header\"> $title </h3>";
        } else {
            return "<h3 class=\"element-header\">
                        $title
                        " . Button::help($infoUrl) . "
                    </h3>";
        }
    }

    public static function startPage($breadcrumb, $pageTitle = null, $settingUrl = null, $infoUrl = null) {
        global $CFG, $SITE;
        $breadcrumbReturn = '';

        if (!AJAX_SCRIPT) {
            $breadcrumbReturn
                .= "<ul class=\"breadcrumb\">
                        <li>
                            <a target=\"_top\" href=\"{$CFG->wwwroot}/\">{$SITE->fullname}</a>
                        </li>
                        <li>
                            <a href=\"?Dashboard::start\">Dashboard</a>
                        </li>";

            $title = "";
            if (is_string($breadcrumb)) {
                $breadcrumbReturn .= '<li><span>' . $breadcrumb . '</span></li>';
                $title = $breadcrumb;
            } else if (is_array($breadcrumb)) {
                foreach ($breadcrumb as $breadcrumbItem) {
                    if (is_string($breadcrumbItem)) {
                        $breadcrumbReturn .= '<li><span>' . $breadcrumbItem . '</span></li>';
                        $title = $breadcrumbItem;
                    } else {
                        $breadcrumbReturn
                            .= '<li>
                                    <a href="?' . $breadcrumbItem[0] . '">' . $breadcrumbItem[1] . '</a>
                                </li>';
                        $title = $breadcrumbItem[1];
                    }
                }
            }

            if ($settingUrl != null) {
                $breadcrumbReturn
                    .= "<li class=\"setting\">
                            <a data-toggle=\"modal\" data-target=\"#modal-edit\"
                               data-href=\"open-ajax-table.php?$settingUrl\"
                               href=\"#\">
                                <img src=\"{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/top-settings.svg\" alt=\"Settings\" >
                            </a>
                        </li>";
            }

            $breadcrumbReturn .= '</ul>';
            $breadcrumbReturn .= '<div class="content-i"><div class="content-box">';

            if ($pageTitle != null) {
                $breadcrumbReturn .= self::setTitulo($pageTitle ? $pageTitle : $title, $infoUrl);
            }

            $breadcrumbReturn .= Mensagem::getMensagemAgendada();
        } else {
            if (is_string($breadcrumb)) {
                self::startPopup($breadcrumb);
            } else {
                self::startPopup($breadcrumb[count($breadcrumb) - 1]);
            }
        }

        echo $breadcrumbReturn;
    }

    public static function endPage() {
        if (AJAX_SCRIPT) {
            self::endPopup();
        } else {
            echo '</div></div>';
        }
    }

    /**
     * @param       $menuFunction
     * @param       $menuIcon
     * @param       $menuName
     * @param array $subMenus
     */
    public static function addMenu($menuFunction, $menuIcon, $menuName, $subMenus = array()) {
        global $CFG;

        $class = self::testMenuActive($menuFunction);

        $plugin = '';
        preg_match("/(.*?)-/", $menuFunction, $menuFunctionStart);
        if (isset($menuFunctionStart[1])) {
            $plugin = "_" . $menuFunctionStart[1];
        }

        $submenuHtml = '';
        foreach ($subMenus as $subMenu) {
            $classSub = self::testMenuActive($subMenu[0]);
            if (isset ($classSub[1])) {
                $class = $classSub;
            }

            if (strpos($subMenu[2], 'http') === 0) {
                $iconUrl = $subMenu[2];
            } else {
                $iconUrl = "{$CFG->wwwroot}/local/kopere_dashboard$plugin/assets/dashboard/img/iconactive/{$subMenu[2]}.svg";
            }

            $submenuHtml
                .= "<li class=\"$classSub\">
                        <a href=\"?{$subMenu[0]}\">
                            <img src=\"$iconUrl\"
                                 class=\"icon-w\" alt=\"Icon\">
                            <span>{$subMenu[1]}</span>
                        </a>
                    </li>";
        }
        if ($submenuHtml != '') {
            $submenuHtml = "<ul class='submenu'>$submenuHtml</ul>";
        }

        echo "
                <li class=\"$class\">
                    <a href=\"?$menuFunction\">
                        <img src=\"{$CFG->wwwroot}/local/kopere_dashboard$plugin/assets/dashboard/img/icon$class/$menuIcon.svg\"
                             class=\"icon-w\" alt=\"Icon\">
                        <span>$menuName</span>
                    </a>
                    $submenuHtml
                </li>";
    }

    private static function testMenuActive($menuFunction) {
        preg_match("/.*?::/", $menuFunction, $paths);
        if (strpos($_SERVER['QUERY_STRING'], $paths[0]) === 0) {
            return 'active';
        }

        return '';
    }

    private static $_isWithForm = false;

    public static function startPopup($title, $formAction = null) {
        if ($formAction) {
            echo '<form method="post" class="validate" enctype="multipart/form-data" >';
            echo '<input type="hidden" name="POST"  value="true" />';
            echo '<input type="hidden" name="action" value="?' . $formAction . '" />';
            self::$_isWithForm = true;
        } else {
            self::$_isWithForm = false;
        }

        echo '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">' . $title . '</h4>
              </div>
              <div class="modal-body">';

        if ($formAction) {
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
        }
    }

    public static function endPopup($deleteButtonUrl = null) {
        if (self::$_isWithForm) {
            echo "</div>
                  <div class=\"modal-footer\">";

            if ($deleteButtonUrl) {
                Button::delete('Excluir', $deleteButtonUrl, 'float-left', false, false, true);
            }

            echo "    <button class=\"btn btn-default\" data-dismiss=\"modal\">".get_string('cancel')."</button>
                      <input type=\"submit\" class=\"btn btn-primary margin-left-15\" value=\"".get_string('savechanges')."\">
                  </div>
                  </form>";
        } else {
            echo "</div>
                  <div class=\"modal-footer\">
                      <button class=\"btn btn-default\" data-dismiss=\"modal\">".get_string_kopere('close')."</button>
                  </div>";
        }
        echo "<script>
                  startForm ( '.modal-content' );
              </script>";

        die();
    }
}