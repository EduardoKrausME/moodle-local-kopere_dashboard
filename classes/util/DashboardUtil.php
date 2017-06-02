<?php
/**
 * User: Eduardo Kraus
 * Date: 12/05/17
 * Time: 06:09
 */

namespace local_kopere_dashboard\util;


use local_kopere_dashboard\html\Botao;

class DashboardUtil
{
    public static $currentTitle = '';
    public static function setTitulo ( $titulo, $infoUrl = null )
    {
        self::$currentTitle = $titulo;

        if ( $infoUrl == null )
            return "<h3 class=\"element-header\"> $titulo </h3>";
        else
            return "<h3 class=\"element-header\"> 
                        $titulo
                        " . Botao::help ( $infoUrl ) . "
                    </h3>";
    }

    public static function startPage ( $breadcrumb, $tituloPagina = null, $settingUrl = null, $infoUrl = null )
    {
        global $CFG, $SITE;
        $breadcrumbReturn = '';

        if ( !AJAX_SCRIPT ) {
            $breadcrumbReturn
                .= "<ul class=\"breadcrumb\">
                        <li>
                            <a target=\"_top\" href=\"{$CFG->wwwroot}/\">{$SITE->fullname}</a>
                        </li>
                        <li>
                            <a href=\"?Dashboard::start\">Dashboard</a>
                        </li>";

            $titulo = "";
            if ( is_string ( $breadcrumb ) ) {
                $breadcrumbReturn .= '<li><span>' . $breadcrumb . '</span></li>';
                $titulo = $breadcrumb;
            } else if ( is_array ( $breadcrumb ) ) {
                foreach ( $breadcrumb as $breadcrumbItem ) {
                    if ( is_string ( $breadcrumbItem ) ) {
                        $breadcrumbReturn .= '<li><span>' . $breadcrumbItem . '</span></li>';
                        $titulo = $breadcrumbItem;
                    } else {
                        $breadcrumbReturn
                            .= '<li>
                                    <a href="' . $breadcrumbItem[ 0 ] . '">' . $breadcrumbItem[ 1 ] . '</a>
                                </li>';
                        $titulo = $breadcrumbItem[ 1 ];
                    }
                }
            }

            if ( $settingUrl != null ) {
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

            if ( $tituloPagina != -1 )
                $breadcrumbReturn .= self::setTitulo ( $tituloPagina ? $tituloPagina : $titulo, $infoUrl );

            $breadcrumbReturn .= Mensagem::getMensagemAgendada ();
        } else {
            if ( is_string ( $breadcrumb ) )
                self::startPopup ( $breadcrumb );
            else
                self::startPopup ( $breadcrumb[ count ( $breadcrumb ) - 1 ] );
        }

        echo $breadcrumbReturn;
    }

    public static function endPage ()
    {
        if ( AJAX_SCRIPT )
            self::endPopup ();
        else
            echo '</div></div>';
    }

    /**
     * @param       $menuFunction
     * @param       $menuIcon
     * @param       $menuName
     * @param array $subMenus
     */
    public static function addMenu ( $menuFunction, $menuIcon, $menuName, $subMenus = array() )
    {
        global $CFG;

        $class = self::testMenuActive ( $menuFunction );

        $plugin = '';
        preg_match ( "/(.*?)-/", $menuFunction, $menuFunctionStart );
        if ( isset( $menuFunctionStart[ 1 ] ) )
            $plugin = "_" . $menuFunctionStart[ 1 ];

        $submenuHtml = '';
        foreach ( $subMenus as $subMenu ) {
            $classSub = self::testMenuActive ( $subMenu[ 0 ] );
            if ( isset ( $classSub[ 1 ] ) )
                $class = $classSub;

            if ( strpos ( $subMenu[ 2 ], 'http' ) === 0 )
                $iconUrl = $subMenu[ 2 ];
            else
                $iconUrl = "{$CFG->wwwroot}/local/kopere_dashboard$plugin/assets/dashboard/img/iconactive/{$subMenu[2]}.svg";

            $submenuHtml
                .= "<li class=\"$classSub\">
                        <a href=\"?{$subMenu[0]}\">
                            <img src=\"$iconUrl\"
                                 class=\"icon-w\" alt=\"Icon\">
                            <span>{$subMenu[1]}</span>
                        </a>
                    </li>";
        }
        if ( $submenuHtml != '' )
            $submenuHtml = "<ul class='submenu'>$submenuHtml</ul>";

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

    private static function testMenuActive ( $menuFunction )
    {
        preg_match ( "/.*?::/", $menuFunction, $paths );
        if ( strpos ( $_SERVER[ 'QUERY_STRING' ], $paths[ 0 ] ) === 0 )
            return 'active';

        return '';
    }

    private static $_isWithForm = false;

    public static function startPopup ( $titulo, $formAction = null )
    {
        if ( $formAction ) {
            echo '<form method="post" class="validate" enctype="multipart/form-data" >';
            echo '<input type="hidden" name="POST"  value="true" />';
            echo '<input type="hidden" name="action" value="' . $formAction . '" />';
            self::$_isWithForm = true;
        } else
            self::$_isWithForm = false;

        echo '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">' . $titulo . '</h4>
              </div>
              <div class="modal-body">';

        if ( $formAction )
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
    }

    public static function endPopup ( $deleteButtonUrl = null )
    {
        if ( self::$_isWithForm ) {
            echo "</div>
                  <div class=\"modal-footer\">";

            if ( $deleteButtonUrl )
                Botao::delete ( 'Excluir', $deleteButtonUrl, 'float-left', false, false, true );

            echo "    <button class=\"btn btn-default\" data-dismiss=\"modal\">Cancelar</button>
                      <input type=\"submit\" class=\"btn btn-primary margin-left-15\" value=\"Salvar\">
                  </div>
                  </form>";
        } else {
            echo "</div>
                  <div class=\"modal-footer\">
                      <button class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>
                  </div>";
        }
        echo "<script>
                  startForm ( '.modal-content' );
              </script>";

        die();
    }
}