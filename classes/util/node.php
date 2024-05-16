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

    /**
     * @throws \coding_exception
     */
    public static function add_admin_code() {
        global $PAGE, $USER;

        if (self::is_enables()) {
            $urlnode = self::base_url();
            if (isset($USER->id) && $USER->id >= 2) {
                $id = $USER->id;
                $fullname = fullname($USER);
            } else {
                $id = 0;
                $fullname = "Visitante";
            }

            if (optional_param('classname', false, PARAM_TEXT) == 'useronline') {
                $PAGE->requires->js_call_amd('local_kopere_dashboard/online_app', 'connectServer',
                    [$id, $fullname, time(), $urlnode, 'z35admin']);
            } else {
                $PAGE->requires->js_call_amd('local_kopere_dashboard/online_app', 'connectServer',
                    [$id, $fullname, time(), $urlnode]);
            }
        }
    }

    /**
     * @return bool
     */
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
