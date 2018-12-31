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
 * @created    31/01/17 06:35
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;


class report_foreach {

    /**
     * @param $item
     * @return mixed
     */
    public static function userfullname($item) {
        $item->userfullname = fullname($item);

        return $item;
    }

    /**
     * @param $item
     * @return mixed
     */
    public static function badge_status_text($item) {
        if ($item->status == 0 || $item->status == 2) {
            $item->statustext = false;
        } else if ($item->status == 1 || $item->status == 3) {
            $item->statustext = false;
        } else if ($item->status == 4) {
            $item->statustext = "-";
        }
        if ($item->type == 1) {
            $item->context = 'Sistema';
        }
        if ($item->type == 1) {
            $item->context = 'Curso';
        }

        return $item;
    }

    /**
     * @param $item
     * @return mixed
     * @throws \coding_exception
     */
    public static function badge_criteria_type($item) {
        $item->criteriatype = get_string('criteria_' . $item->criteriatype, 'badges');
        $item->name = fullname($item);

        return $item;
    }

    /**
     * @param $item
     * @return mixed
     * @throws \coding_exception
     */
    public static function courses_group_mode($item) {
        if ($item->groupmode == 0) {
            $item->groupname = get_string('groupsnone', 'group');
        } else if ($item->groupmode == 1) {
            $item->groupname = get_string('groupsseparate', 'group');
        } else if ($item->groupmode == 2) {
            $item->groupname = get_string('groupsvisible', 'group');
        }

        return $item;
    }

}