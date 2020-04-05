<?php
/**
 * User: Eduardo Kraus
 * Date: 21/03/2020
 * Time: 12:49
 */

namespace local_kopere_dashboard\util;


/**
 * Class node
 * @package local_kopere_dashboard\util
 */
class node {

    public static function add_admin_code() {
        global $PAGE, $USER;

        if (node::is_enables()) {
            echo "<script src=\"" . node::geturl_socketio() . "\"></script>";
            // $PAGE->requires->js("/local/kopere_dashboard/node/app.js");

            $userid = intval($USER->id);
            $fullname = fullname($USER);
            $servertime = time();
            $urlnode = node::base_url();

            $PAGE->requires->js_init_call("startServer", array($userid, $fullname, $servertime, $urlnode, 'z35admin'));
            //    echo "<script type=\"text/javascript\">
            //              startServer ( {$userid}, \"{$fullname}\", {$servertime}, \"{$urlnode}\", 'z35admin' );
            //          </script>";
        }
    }

    public static function is_enables() {
        if (config::get_key_int('nodejs-status')) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function is_ssl() {
        if (config::get_key_int('nodejs-ssl')) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public static function base_url() {
        if (self::is_ssl()) {
            $url = "https://" . config::get_key('nodejs-url');
        } else {
            $url = config::get_key('nodejs-url');
        }

        return $url . ':' . config::get_key('nodejs-port');
    }

    /**
     * @return string
     */
    public static function geturl_socketio() {
        return self::base_url() . '/node_modules/socket.io-client/dist/socket.io.js';
    }
}