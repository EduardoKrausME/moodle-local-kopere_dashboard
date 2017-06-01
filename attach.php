<?php
/**
 * User: Eduardo Kraus
 * Date: 28/05/17
 * Time: 02:01
 */

ob_start ();

require ( '../../config.php' );

$src = required_param ( 'src', PARAM_TEXT );

if ( strpos ( $src, '..' ) ) {
    die( 'Not Found!' );
}


$imageLoaded = $CFG->dataroot . '/kopere/dashboard/' . $src;
$extension   = pathinfo ( $imageLoaded, PATHINFO_EXTENSION );
$basename    = pathinfo ( $imageLoaded, PATHINFO_BASENAME );
$lifetime    = 60 * 60 * 24 * 360;
$isImage     = false;

ob_clean ();
ob_end_flush ();
session_write_close ();

switch ( $extension ) {
    case 'jpg':
    case 'jpeg':
        header ( 'Content-Type: image/jpeg' );
        $isImage = true;
        break;
    case 'png':
    case 'gif':
    case 'svg':
    case 'bmp':
    case 'tiff':
        header ( 'Content-Type: image/' . $extension );
        $isImage = true;
        break;

    default:
        header ( 'Content-Type: application/octet-stream' );
        break;
}

if ( $isImage ) {
    header ( 'Content-disposition: inline; filename="' . $basename . '"' );
    header ( 'Content-disposition: inline; filename="' . $basename . '"' );
    header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s', filemtime ( $imageLoaded ) ) . ' GMT' );
    header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s', time () + $lifetime ) . ' GMT' );
    header ( 'Cache-Control: public, max-age=' . $lifetime . ', no-transform' );
} else {
    header ( 'Content-Transfer-Encoding: Binary' );
    header ( 'Content-disposition: attachment; filename="' . $basename . '"' );
}


readfile ( $imageLoaded );

