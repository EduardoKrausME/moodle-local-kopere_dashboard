<?php
/**
 * User: Eduardo Kraus
 * Date: 16/07/17
 * Time: 14:05
 */

namespace local_kopere_dashboard\util;

defined('MOODLE_INTERNAL') || die();

/**
 * Class TitleUtil
 *
 * @package local_kopere_dashboard\util
 */
class TitleUtil {
    /**
     * @param      $title
     * @param bool $isKeyLang
     */
    public static function printH1( $title, $isKeyLang = true) {
        if ($isKeyLang)
            echo "<h1>".get_string_kopere($title)."</h1>";
        else
            echo "<h1>{$title}</h1>";
    }

    /**
     * @param      $title
     * @param bool $isKeyLang
     */
    public static function printH2($title, $isKeyLang = true) {
        if ($isKeyLang)
            echo "<h2>".get_string_kopere($title)."</h2>";
        else
            echo "<h2>{$title}</h2>";
    }

    /**
     * @param      $title
     * @param bool $isKeyLang
     */
    public static function printH3($title, $isKeyLang = true) {
        if ($isKeyLang)
            echo "<h3>".get_string_kopere($title)."</h3>";
        else
            echo "<h3>{$title}</h3>";
    }

    /**
     * @param      $title
     * @param bool $isKeyLang
     */
    public static function printH4($title, $isKeyLang = true) {
        if ($isKeyLang)
            echo "<h4>".get_string_kopere($title)."</h4>";
        else
            echo "<h4>{$title}</h4>";
    }

    /**
     * @param      $title
     * @param bool $isKeyLang
     */
    public static function printH5($title, $isKeyLang = true) {
        if ($isKeyLang)
            echo "<h5>".get_string_kopere($title)."</h5>";
        else
            echo "<h5>{$title}</h5>";
    }

    /**
     * @param      $title
     * @param bool $isKeyLang
     */
    public static function printH6($title, $isKeyLang = true) {
        if ($isKeyLang)
            echo "<h6>".get_string_kopere($title)."</h6>";
        else
            echo "<h6>{$title}</h6>";
    }
}