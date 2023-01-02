<?php
/**
 * User: Eduardo Kraus
 * Date: 29/12/2022
 * Time: 11:24
 */

namespace local_kopere_dashboard\report;


class user_field {
    public static function get_all_user_name_fields($returnsql = false, $tableprefix = null) {
        $alternatenames = [
            // Para a função fullname()
            'firstnamephonetic' => 'firstnamephonetic',
            'lastnamephonetic'  => 'lastnamephonetic',
            'middlename'        => 'middlename',
            'alternatename'     => 'alternatename',
            'firstname'         => 'firstname',
            'lastname'          => 'lastname',

            // Para as configurações do setting
            'institution'       => 'institution',
            'department'        => 'department',
            'phone1'            => 'phone1',
            'phone2'            => 'phone2',
            'address'           => 'address',
            'description'       => 'description',
            'city'              => 'city',
            'country'           => 'country',
            'timezone'          => 'timezone',
            'firstaccess'       => 'firstaccess',
            'lastaccess'        => 'lastaccess',
            'lastlogin'         => 'lastlogin',
            'lastip'            => 'lastip',
        ];

        // Create an sql field snippet if requested.
        if ($returnsql) {
            if ($tableprefix) {
                foreach ($alternatenames as $key => $altname) {
                    $alternatenames[$key] = $tableprefix . '.' . $altname;
                }
            }
            $alternatenames = implode(',', $alternatenames);
        }
        return $alternatenames;
    }
}