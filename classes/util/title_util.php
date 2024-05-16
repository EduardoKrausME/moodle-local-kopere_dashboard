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
 * Date: 16/07/17
 * Time: 14:05
 */

namespace local_kopere_dashboard\util;

/**
 * Class title_util
 *
 * @package local_kopere_dashboard\util
 */
class title_util {
    /**
     * @param string $title
     * @param bool $iskeylang
     *
     * @throws \coding_exception
     */
    public static function print_h1($title, $iskeylang = true) {
        if ($iskeylang) {
            echo "<h1>" . get_string_kopere($title) . "</h1>";
        } else {
            echo "<h1>{$title}</h1>";
        }
    }

    /**
     * @param string $title
     * @param bool $iskeylang
     *
     * @throws \coding_exception
     */
    public static function print_h2($title, $iskeylang = true) {
        if ($iskeylang) {
            echo "<h2>" . get_string_kopere($title) . "</h2>";
        } else {
            echo "<h2>{$title}</h2>";
        }
    }

    /**
     * @param string $title
     * @param bool $iskeylang
     *
     * @throws \coding_exception
     */
    public static function print_h3($title, $iskeylang = true) {
        if ($iskeylang) {
            echo "<h3>" . get_string_kopere($title) . "</h3>";
        } else {
            echo "<h3>{$title}</h3>";
        }
    }

    /**
     * @param string $title
     * @param bool $iskeylang
     *
     * @throws \coding_exception
     */
    public static function print_h4($title, $iskeylang = true) {
        if ($iskeylang) {
            echo "<h4>" . get_string_kopere($title) . "</h4>";
        } else {
            echo "<h4>{$title}</h4>";
        }
    }

    /**
     * @param string $title
     * @param bool $iskeylang
     *
     * @throws \coding_exception
     */
    public static function print_h5($title, $iskeylang = true) {
        if ($iskeylang) {
            echo "<h5>" . get_string_kopere($title) . "</h5>";
        } else {
            echo "<h5>{$title}</h5>";
        }
    }

    /**
     * @param string $title
     * @param bool $iskeylang
     *
     * @throws \coding_exception
     */
    public static function print_h6($title, $iskeylang = true) {
        if ($iskeylang) {
            echo "<h6>" . get_string_kopere($title) . "</h6>";
        } else {
            echo "<h6>{$title}</h6>";
        }
    }
}
