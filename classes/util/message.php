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
 * message file
 *
 * introduced 31/01/17 06:01
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class message
 *
 * @package local_kopere_dashboard\util
 */
class message {

    /**
     * Function schedule_message
     *
     * @param $message
     */
    public static function schedule_message($message) {
        $cookie = session::get("kopere_message");
        if (!$cookie) {
            session::set("kopere_message", $message);
        } else {
            session::set("kopere_message", $cookie . $message);
        }
    }

    /**
     * Function get_message_schedule
     *
     * @return null|string
     */
    public static function get_message_schedule() {
        $cookie = session::get("kopere_message");
        if ($cookie) {
            session::set("kopere_message", null);
            return $cookie;
        }
        return "";
    }

    /**
     * return void
     */
    public static function clear_message() {
        session::set("kopere_message", null);
    }

    /**
     * Function warning
     *
     * @param $texto
     *
     * @return string
     */
    public static function warning($texto) {
        return "
            <div class='alert alert-warning'>
                <i class='fa fa-exclamation-circle'></i>
                {$texto}
            </div>";
    }

    /**
     * Function print_warning
     *
     * @param $texto
     */
    public static function print_warning($texto) {
        echo self::warning($texto);
    }

    /**
     * Function schedule_message_warning
     *
     * @param $texto
     */
    public static function schedule_message_warning($texto) {
        self::schedule_message(self::warning($texto));
    }

    /**
     * Function success
     *
     * @param $texto
     *
     * @return string
     */
    public static function success($texto) {
        return "
            <div class='alert alert-success'>
                <i class='fa fa-check-circle'></i>
                {$texto}
            </div>";
    }

    /**
     * Function print_success
     *
     * @param $texto
     */
    public static function print_success($texto) {
        echo self::success($texto);
    }

    /**
     * Function schedule_message_success
     *
     * @param $texto
     */
    public static function schedule_message_success($texto) {
        self::schedule_message(self::success($texto));
    }

    /**
     * Function info
     *
     * @param $texto
     * @param string $extraclass
     *
     * @return string
     */
    public static function info($texto, $extraclass = "") {
        return "
            <div class='alert alert-info {$extraclass}'>
                <i class='fa fa-info-circle'></i>
                {$texto}
            </div>";
    }

    /**
     * Function print_info
     *
     * @param $texto
     * @param string $extraclass
     */
    public static function print_info($texto, $extraclass = "") {
        echo self::info($texto, $extraclass);
    }

    /**
     * Function schedule_message_info
     *
     * @param $texto
     */
    public static function schedule_message_info($texto) {
        self::schedule_message(self::info($texto));
    }

    /**
     * Function danger
     *
     * @param $texto
     *
     * @return string
     */
    public static function danger($texto) {
        return "
            <div class='alert alert-danger'>
                <i class='fa fa-times-circle'></i>
                {$texto}
            </div>";
    }

    /**
     * Function print_danger
     *
     * @param $texto
     */
    public static function print_danger($texto) {
        echo self::danger($texto);
    }

    /**
     * Function schedule_message_danger
     *
     * @param $texto
     */
    public static function schedule_message_danger($texto) {
        self::schedule_message(self::danger($texto));
    }
}
