<?php
// This file is part of the local_kopere_dashboard plugin for Moodle - http://moodle.org/
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
 * @package     local_kopere_dashboard
 * @copyright   2024 Eduardo Kraus https://eduardokraus.com/
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @date        10/01/2024 17:58
 */

namespace local_kopere_dashboard\fonts;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once("{$CFG->dirroot}/local/kopere_dashboard/classes/fonts/font_attributes.php");

class font_util {

    /**
     * @return array
     */
    public static function list_fonts() {
        global $CFG;

        $fonts = ["css" => "", "editor-list" => ""];
        $fontsitens = [];
        $fontfiles = glob("{$CFG->dirroot}/local/kopere_dashboard/_editor/fonts/*/*.ttf");

        foreach ($fontfiles as $fontfile) {
            try {
                $ttfinfo = new font_attributes($fontfile);

                $fontnameid = $ttfinfo->get_font_name_id();
                $fontname = $ttfinfo->get_font_name();
                $fontfamily = $ttfinfo->get_font_family();

                $fonturl = str_replace($CFG->dirroot, $CFG->wwwroot, $fontfile);

                if (strpos($fontfile, '_signature-') === false) {
                    if ($fontname == $fontfamily) {
                        $value = "'{$fontfamily}'";
                    } else {
                        $value = "'{$fontfamily}', '{$fontname}'";
                    }
                    $fonts["css"] .= "
                        @font-face {
                            font-family : {$fontfamily};
                            src         : local(\"{$fontfamily}\"), url({$fonturl}) format('truetype');
                        }";
                    $fontsitens[$fontname] = "
                        {
                            id    : '\'{$fontfamily}\'',
                            label : '{$fontname}',
                            value : \"{$value}\"
                        },";
                }
            } catch (\Exception $e) {
            }
        }

        ksort($fontsitens);
        $fonts["editor-list"] = implode("\n", $fontsitens);

        return $fonts;
    }

    /**
     */
    public static function print_only_unique() {
        static $printed = false;
        if ($printed) return;
        $printed = true;

        $fontList = self::list_fonts();
        echo "<style>{$fontList['css']}</style>";
    }
}
