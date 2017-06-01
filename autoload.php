<?php
/**
 * User: Eduardo Kraus
 * Date: 23/05/17
 * Time: 17:59
 */

spl_autoload_register ( "kopere_dashboard_autoload" );
function kopere_dashboard_autoload ( $className )
{
    global $CFG;

    if ( strpos ( $className, 'kopere' ) === false )
        return;

    $className = str_replace ( '\\', '/', $className );

    preg_match ( "/local_(.*?)\/(.*)/", $className, $classPartes );

    $file = "{$CFG->dirroot}/local/{$classPartes[1]}/classes/{$classPartes[2]}.php";
    if ( file_exists ( $file ) )
        require_once $file;
}

function loadByQuery ( $queryString )
{
    preg_match ( "/(.*?)::([a-zA-Z_0-9]+)/", $queryString, $paths );

    $className = $paths[ 1 ];
    if ( strpos ( $className, '-' ) )
        $class = 'local_kopere_dashboard_' . str_replace ( '-', '\\', $className );
    else
        $class = 'local_kopere_dashboard\\' . $className;

    $class = str_replace ( '?', '', $class );

    $instance = new $class();
    $method   = $paths[ 2 ];
    $instance->$method();

}