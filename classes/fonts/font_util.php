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
 * font_util file
 *
 * @package   local_kopere_dashboard
 * @copyright 2024 Eduardo Kraus https://eduardokraus.com/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * introduced 10/01/2024 17:58
 */

namespace local_kopere_dashboard\fonts;

/**
 * Class font_util
 *
 * @package local_kopere_dashboard\fonts
 */
class font_util {

    /**
     * Function list_fonts
     *
     * @return array
     * @throws \dml_exception
     */
    private static function list_fonts() {
        static $fontlist;
        if ($fontlist) {
            return $fontlist;
        }

        $fontsdefault = [
            "family=Alex+Brush",
            "family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;" .
            "1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900",
            "family=Bebas+Neue",
            "family=Cabin:ital,wght@0,400..700;1,400..700",
            "family=Caveat+Brush",
            "family=Caveat:wght@400..700",
            "family=Cinzel+Decorative:wght@400;700;900",
            "family=Cinzel:wght@400..900",
            "family=Dancing+Script:wght@400..700",
            "family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000",
            "family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;1,100;1,200;1,300;1,400",
            "family=Hind+Siliguri:wght@300;400;500;600;700",
            "family=Inter+Tight:ital,wght@0,100..900;1,100..900",
            "family=Inter:wght@100..900",
            "family=Jacquard+24",
            "family=Jersey+10",
            "family=Josefin+Sans:ital,wght@0,100..700;1,100..700",
            "family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900",
            "family=Lora:ital,wght@0,400..700;1,400..700",
            "family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;" .
            "1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900",
            "family=Montserrat:ital,wght@0,100..900;1,100..900",
            "family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000",
            "family=Nunito:ital,wght@0,200..1000;1,200..1000",
            "family=Open+Sans:ital,wght@0,300..800;1,300..800",
            "family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;" .
            "1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900",
            "family=Raleway+Dots",
            "family=Raleway:ital,wght@0,100..900;1,100..900",
            "family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900",
            "family=Rubik:ital,wght@0,300..900;1,300..900",
            "family=Sedan:ital@0;1",
            "family=Sedgwick+Ave+Display",
            "family=Source+Code+Pro:ital,wght@0,200..900;1,200..900",
            "family=Source+Sans+3:ital,wght@0,200..900;1,200..900",
            "family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700",
            "family=Vibur",
            "family=Work+Sans:ital,wght@0,100..900;1,100..900",
        ];

        preg_match_all('/(family=.*?)&/', get_config("local_kopere_dashboard", "pagefonts"), $fontsuser);
        if (isset($fontsuser[1])) {
            $fonts = array_merge($fontsuser[1], $fontsdefault);

            $fontlist = ["css" => [], "grapsjs" => [], "ckeditor" => []];
            foreach ($fonts as $font) {

                preg_match('/family=([A-Z,a-z\+]{2,30})/', $font, $fontinfo);
                if ($fontinfo[1]) {
                    $fontlist["css"][] = $font;
                    $fontname = urldecode($fontinfo[1]);

                    $fontlist["grapsjs"][] = "
                        {
                            'id'    : \"'{$fontname}'\",
                            'label' : '{$fontname}',
                        }";
                    $fontlist["ckeditor"][] = "{$fontname}/'{$fontname}'";
                }
            }
        }

        $fontlist["css"] = 'https://fonts.googleapis.com/css2?' . implode('&', $fontlist["css"]) . '&display=swap';
        $fontlist["grapsjs"] = implode(",", $fontlist["grapsjs"]);
        $fontlist["ckeditor"] = implode(";", $fontlist["ckeditor"]);

        return $fontlist;
    }

    /**
     * Function css
     *
     * @return mixed
     * @throws \dml_exception
     */
    public static function css() {
        return self::list_fonts()["css"];
    }

    /**
     * Function grapsjs
     *
     * @return mixed
     * @throws \dml_exception
     */
    public static function grapsjs() {
        return self::list_fonts()["grapsjs"];
    }

    /**
     * Function ckeditor
     *
     * @return mixed
     * @throws \dml_exception
     */
    public static function ckeditor() {
        return self::list_fonts()["ckeditor"];
    }

    /**
     * Function print_only_unique
     *
     * @return string
     * @throws \dml_exception
     */
    public static function print_only_unique() {
        static $printed = false;
        if ($printed) {
            return "";
        }
        $printed = true;

        return "<link rel=\"stylesheet\" href='" . self::css() . "'>";
    }
}
