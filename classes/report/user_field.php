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
 * Date: 29/12/2022
 * Time: 11:24
 */

namespace local_kopere_dashboard\report;

class user_field {
    public static function get_all_user_name_fields($returnsql = false, $tableprefix = null) {
        $alternatenames = [
            // Para a função fullname().
            'firstnamephonetic' => 'firstnamephonetic',
            'lastnamephonetic' => 'lastnamephonetic',
            'middlename' => 'middlename',
            'alternatename' => 'alternatename',
            'firstname' => 'firstname',
            'lastname' => 'lastname',

            // Para as configurações do setting.
            'institution' => 'institution',
            'department' => 'department',
            'phone1' => 'phone1',
            'phone2' => 'phone2',
            'address' => 'address',
            'description' => 'description',
            'city' => 'city',
            'country' => 'country',
            'timezone' => 'timezone',
            'firstaccess' => 'firstaccess',
            'lastaccess' => 'lastaccess',
            'lastlogin' => 'lastlogin',
            'lastip' => 'lastip',
        ];

        // Create an sql field snippet if requested.
        if ($returnsql) {
            if ($tableprefix) {
                foreach ($alternatenames as $key => $altname) {
                    $alternatenames[$key] = "{$tableprefix}.{$altname}";
                }
            }
            $alternatenames = implode(',', $alternatenames);
        }
        return $alternatenames;
    }
}
