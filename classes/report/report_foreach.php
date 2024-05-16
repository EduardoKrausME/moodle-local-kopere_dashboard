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
     * @return \stdClass
     * @throws \coding_exception
     */
    public static function userfullname($item) {
        $item->userfullname = self::internal_userfullname($item);

        return $item;
    }

    /**
     * @param $user
     * @return string
     * @throws \coding_exception
     */
    private static function internal_userfullname($user) {
        global $CFG, $SESSION;

        if (!isset($user->firstname) && !isset($user->lastname)) {
            return '';
        }

        $allnames = ['firstnamephonetic', 'lastnamephonetic', 'middlename', 'alternatename', 'firstname', 'lastname'];

        if (!empty($SESSION->fullnamedisplay)) {
            $CFG->fullnamedisplay = $SESSION->fullnamedisplay;
        }

        $template = null;
        // If the fullnamedisplay setting is available, set the template to that.
        if (isset($CFG->fullnamedisplay)) {
            $template = $CFG->fullnamedisplay;
        }
        // If the template is empty, or set to language, return the language string.
        if ((empty($template) || $template == 'language')) {
            return get_string('fullnamedisplay', null, $user);
        }

        $requirednames = [];
        // With each name, see if it is in the display name template, and add it to the required names array if it is.
        foreach ($allnames as $allname) {
            if (strpos($template, $allname) !== false) {
                $requirednames[] = $allname;
            }
        }

        $displayname = $template;
        // Switch in the actual data into the template.
        foreach ($requirednames as $altname) {
            if (isset($user->$altname)) {
                // Using empty() on the below if statement causes breakages.
                if ((string)$user->$altname == '') {
                    $displayname = str_replace($altname, 'EMPTY', $displayname);
                } else {
                    $displayname = str_replace($altname, $user->$altname, $displayname);
                }
            } else {
                $displayname = str_replace($altname, 'EMPTY', $displayname);
            }
        }
        // Tidy up any misc. characters (Not perfect, but gets most characters).
        // Don't remove the "u" at the end of the first expression unless you want garbled characters when combining hiragana or
        // katakana and parenthesis.
        $patterns = [];
        // This regular expression replacement is to fix problems such as 'James () Kirk' Where 'Tiberius' (middlename) has not been
        // filled in by a user.
        // The special characters are Japanese brackets that are common enough to make allowances for them (not covered by :punct:).
        $patterns[] = '/[[:punct:]「」]*EMPTY[[:punct:]「」]*/u';
        // This regular expression is to remove any double spaces in the display name.
        $patterns[] = '/\s{2,}/u';
        foreach ($patterns as $pattern) {
            $displayname = preg_replace($pattern, ' ', $displayname);
        }

        // Trimming $displayname will help the next check to ensure that we don't have a display name with spaces.
        $displayname = trim($displayname);
        if (empty($displayname)) {
            // Going with just the first name if no alternate fields are filled out. May be changed later depending on what
            // people in general feel is a good setting to fall back on.
            $displayname = $user->firstname;
        }
        return $displayname;
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
        } else {
            $item->statustext = "";
        }

        switch ($item->type) {
            case 0:
                $item->context = 'Função';
                break;
            case 1:
                $item->context = 'Atividade';
                break;
            case 2:
                $item->context = 'Duração';
                break;
            case 3:
                $item->context = 'Nota';
                break;
            case 4:
            case 6:
                $item->context = 'Curso';
                break;
            case 5:
                $item->context = 'Conjunto de cursos';
                break;
            case 7:
                $item->context = 'Preenchimento do Emblema';
                break;
            case 8:
                $item->context = 'Coorte';
                break;
            case 9:
                $item->context = 'Competência';
                break;
            default:
                $item->context = '...';
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
        $item->criteriatype = get_string("criteria_{$item->criteriatype}", 'badges');
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
