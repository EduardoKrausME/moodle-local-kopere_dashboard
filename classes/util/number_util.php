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
 * Date: 06/05/2018
 * Time: 12:45
 */

namespace local_kopere_dashboard\util;

class number_util {
    public static function only_number ( $number ){
        $number = preg_replace ( '/[^0-9]/', '', $number );

        return $number;
    }

    public static function phonecell_to_number ( $phone )
    {
        $phone = preg_replace ( '/[^0-9]/', '', $phone );

        if ( preg_replace ( '/\d{2}[6-9]\d{7}/', '', $phone ) == '' )
            return preg_replace ( '/(\d{2})(\d{8})/', '($1) 9$2', $phone );

        // já baseado na regra futura da 404/05
        if ( preg_replace ( '/\d{2}[6-9]{2}\d{7}/', '', $phone ) == '' )
            return preg_replace ( '/(\d{2})(\d{9})/', '($1) $2', $phone );

        return '';
    }
}