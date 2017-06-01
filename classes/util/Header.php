<?php

namespace local_kopere_dashboard\util;

class Header
{
    public static function location ( $url, $isDie = true )
    {
        ob_clean ();
        header ( 'Location: ' . $url );

        if ( $isDie )
            die ( 'Redirecionando para ' . $url );
    }

    public static function reload ( $isDie = true )
    {
        ob_clean ();
        $url = $_SERVER[ 'QUERY_STRING' ];

        header ( 'Location: ?' . $url );
        if ( $isDie )
            die ( 'Redirecionando para ?' . $url );
    }

    public static function notfoundNull ( $param, $printText = false )
    {
        if ( $param == null )
            self::notfound ( $printText );
    }

    public static function notfound ( $printText = false )
    {
        global $CFG;

        header ( 'HTTP/1.0 404 Not Found' );
        DashboardUtil::startPage ( 'Erro' );

        echo '<div class="element-box text-center page404">
                  <img width="200" height="200" src="' . $CFG->wwwroot . '/local/kopere_dashboard/assets/dashboard/img/404.svg">
                  <h2>OOPS!</h2>
                  <div class="text404 text-danger">' . $printText . '</div>
                  <p>
                      <a href="#" onclick="window.history.back();return false;" 
                         class="btn btn-primary">Voltar</a>
                  </p>
              </div>';

        DashboardUtil::endPage ();
        die();
    }
}