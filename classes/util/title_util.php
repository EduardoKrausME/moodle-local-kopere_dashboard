<?php
/**
 * User: Eduardo Kraus
 * Date: 16/07/17
 * Time: 14:05
 */

namespace local_kopere_dashboard\util;

defined('MOODLE_INTERNAL') || die();

/**
 * Class title_util
 *
 * @package local_kopere_dashboard\util
 */
class title_util {
    /**
     * @param      $title
     * @param bool $is_key_lang
     */
    public static function print_h1( $title, $is_key_lang = true) {
        if ($is_key_lang)
            echo "<h1>".get_string_kopere($title)."</h1>";
        else
            echo "<h1>{$title}</h1>";
    }

    /**
     * @param      $title
     * @param bool $is_key_lang
     */
    public static function print_h2($title, $is_key_lang = true) {
        if ($is_key_lang)
            echo "<h2>".get_string_kopere($title)."</h2>";
        else
            echo "<h2>{$title}</h2>";
    }

    /**
     * @param      $title
     * @param bool $is_key_lang
     */
    public static function print_h3($title, $is_key_lang = true) {
        if ($is_key_lang)
            echo "<h3>".get_string_kopere($title)."</h3>";
        else
            echo "<h3>{$title}</h3>";
    }

    /**
     * @param      $title
     * @param bool $is_key_lang
     */
    public static function print_h4($title, $is_key_lang = true) {
        if ($is_key_lang)
            echo "<h4>".get_string_kopere($title)."</h4>";
        else
            echo "<h4>{$title}</h4>";
    }

    /**
     * @param      $title
     * @param bool $is_key_lang
     */
    public static function print_h5($title, $is_key_lang = true) {
        if ($is_key_lang)
            echo "<h5>".get_string_kopere($title)."</h5>";
        else
            echo "<h5>{$title}</h5>";
    }

    /**
     * @param      $title
     * @param bool $is_key_lang
     */
    public static function print_h6($title, $is_key_lang = true) {
        if ($is_key_lang)
            echo "<h6>".get_string_kopere($title)."</h6>";
        else
            echo "<h6>{$title}</h6>";
    }
}