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
 * @created    28/05/17 03:21
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class user_util
 * @package local_kopere_dashboard\util
 */
class user_util {

    /**
     * @param string $firstname
     * @param string $lastname
     */
    public static function explode_name($newuser) {
        if ($newuser->lastname == null) {
            $nomes = explode(' ', $newuser->firstname);
            $newuser->firstname = $nomes[0];
            array_shift($nomes);
            $newuser->lastname = implode(' ', $nomes);
        }

        return $newuser;
    }

    /**
     * @param $result
     * @param string $colname
     * @return mixed
     */
    public static function column_fullname($result, $colname = 'fullname') {
        foreach ($result as $key => $row) {
            $row->$colname = fullname($row);
            $result[$key] = $row;
        }

        return $result;
    }

    /**
     * @param $newuser
     * @return string
     * @throws \Exception
     */
    public static function validate_new_user($newuser) {
        global $CFG, $DB;

        $errors = [];
        if (!empty($newuser->password)) {
            $errmsg = '';
            if (!check_password_policy($newuser->password, $errmsg)) {
                $errors[] = $errmsg;
            }
        } else {
            $errors[] = get_string('password') . ": " . get_string('required');
        }
        if (empty($newuser->username)) {
            $errors[] = get_string('username') . ": " . get_string('required');
        }
        if (!validate_email($newuser->email)) {
            $errors[] = get_string('invalidemail');
        } else if (empty($CFG->allowaccountssameemail)
            && $DB->record_exists('user', [
                'email' => $newuser->email,
                'mnethostid' => $CFG->mnet_localhost_id
            ])) {
            $errors[] = get_string('emailexists');
        }

        return implode("<br>", $errors);
    }
}
