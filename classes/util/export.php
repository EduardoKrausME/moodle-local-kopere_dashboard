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
 * @created    22/05/17 05:32
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class export
 *
 * @package local_kopere_dashboard\util
 */
class export {
    /**
     * @var
     */
    private static $format;

    public static function is_export() {
        return self::$format == 'xls';
    }

    /**
     * @param      $format
     * @param null $filename
     */
    public static function header($format, $filename = null) {
        if ($filename == null) {
            $filename = dashboard_util::$currenttitle;
        }

        self::$format = $format;
        if (self::$format == 'xls') {
            ob_clean();
            ob_end_flush();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
            header('Cache-Control: max-age=0');

            echo "<html>
                  <head>
                      <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">
                      <title>$filename</title>
                      <!--table
                          {mso-displayed-decimal-separator:\"\,\";
                          mso-displayed-thousand-separator:\"\.\";}
                      -->
                  </head>
                  <body>";
            @ob_flush();
        }
    }

    public static function close() {
        if (self::$format == 'xls') {
            echo '</body></html>';
            end_util::end_script_show();
        }
    }
}
