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
 * Date: 18/05/2023
 * Time: 22:01
 */

namespace local_kopere_dashboard\util;

/**
 * Class photo
 * @package local_kopere_dashboard\util
 */
class photo {
    /**
     * @param $userid
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function get_photo_user($userid) {
        global $DB, $PAGE;
        $user = $DB->get_record('user', ['id' => $userid]);

        $userpicture = new \user_picture($user);
        $userpicture->size = 1;
        $profileimageurl = $userpicture->get_url($PAGE)->out(false);

        header("Location: {$profileimageurl}");
        exit();
    }
}
