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
 * Date: 09/11/17
 * Time: 16:36
 */

namespace local_kopere_dashboard\report\custom;

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\util\export;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\url_util;

/**
 * Class course_last_access
 *
 * @package local_kopere_dashboard\report\custom
 */
class course_last_access {
    /**
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function name() {
        $cursosid = optional_param('courseid', 0, PARAM_INT);
        return get_string_kopere('reports_report_courses-3') . ' ' . get_course($cursosid)->fullname;
    }

    /**
     * @return boolean
     */
    public function is_enable() {
        return true;
    }

    /**
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function generate() {
        global $DB, $CFG;

        $cursosid = optional_param('courseid', 0, PARAM_INT);
        if ($cursosid == 0) {
            header::notfound(get_string_kopere('courses_notound'));
        }

        $perpage = 10;
        $atualpage = optional_param('page', 1, PARAM_INT);
        $startlimit = ($atualpage - 1) * $perpage;

        $course = $DB->get_record('course', ['id' => $cursosid]);
        header::notfound_null($course, get_string_kopere('courses_notound'));

        button::info(get_string_kopere('reports_export'), url_util::querystring() . "&export=xls");

        session_write_close();
        $export = optional_param('export', '', PARAM_TEXT);
        export::header($export, $course->fullname);

        echo '<table id="list-course-access" class="table table-bordered table-hover" border="1">';

        $userinfofields = $DB->get_records('user_info_field', null, 'sortorder asc');

        $filds = '';
        foreach ($userinfofields as $userinfofield) {
            $filds .= "<td align='center' bgcolor='#979797' style='text-align:center;'>{$userinfofield->name}</td>";
        }

        echo '<thead>
                  <tr bgcolor="#C5C5C5" style="background-color: #c5c5c5;" >
                      <td align="center" bgcolor="#979797" style="text-align:center;">' .
            get_string_kopere('user_table_fullname') . '</td>
                      <td align="center" bgcolor="#979797" style="text-align:center;">' .
            get_string_kopere('user_table_email') . '</td>
                      ' . $filds . '
                      <td align="center" bgcolor="#979797" style="text-align:center;">Primeiro acesso</td>
                      <td align="center" bgcolor="#979797" style="text-align:center;">Último acesso</td>
                  </th>
              </thead>';

        if ($export == 'xls') {
            $sql = "
               SELECT DISTINCT SQL_CALC_FOUND_ROWS u.*
                 FROM {context} c
                 JOIN {role_assignments} ra ON ra.contextid = c.id
                 JOIN {user}             u  ON ra.userid    = u.id
                 JOIN {user_lastaccess}  ul ON ul.courseid  = c.instanceid AND ul.userid = u.id
                WHERE c.contextlevel = :contextlevel
		          AND u.id      NOT IN ({$CFG->siteadmins})
                  AND c.instanceid   = :instanceid";
        } else {
            $sql = "
               SELECT DISTINCT SQL_CALC_FOUND_ROWS u.*
                 FROM {context} c
                 JOIN {role_assignments} ra ON ra.contextid = c.id
                 JOIN {user}             u  ON ra.userid    = u.id
                 JOIN {user_lastaccess}  ul ON ul.courseid  = c.instanceid AND ul.userid = u.id
                WHERE c.contextlevel = :contextlevel
		          AND u.id      NOT IN ({$CFG->siteadmins})
                  AND c.instanceid   = :instanceid
                LIMIT {$startlimit}, {$perpage}";
        }
        $allusercourse = $DB->get_records_sql($sql,
            [
                'contextlevel' => CONTEXT_COURSE,
                'instanceid' => $cursosid,
            ]);

        $total = $DB->get_record_sql("SELECT FOUND_ROWS() as num_itens");

        foreach ($allusercourse as $user) {
            echo '<tr>';
            $link = "{$CFG->wwwroot}/user/view.php?id={$user->id}&course={$cursosid}";
            $this->td("<a href='{$link}' target='moodle'>" . fullname($user) . '</a>', 'bg-info text-nowrap', '#D9EDF7');
            $this->td($user->email, 'bg-info text-nowrap', '#D9EDF7');

            foreach ($userinfofields as $userinfofield) {
                $userinfodata = $DB->get_record('user_info_data',
                    ['userid' => $user->id, 'fieldid' => $userinfofield->id]);
                if ($userinfodata) {
                    $this->td($userinfodata->data, 'text-nowrap bg-success', 'D9EDF7');
                } else {
                    $this->td('', 'text-nowrap bg-success', 'D9EDF7');
                }
            }

            $sql
                = "SELECT timecreated
                      FROM {logstore_standard_log}
                     WHERE courseid = :courseid
                       AND action   = :action
                       AND userid   = :userid
                  ORDER BY timecreated ASC
                     LIMIT 1";

            $logresult = $DB->get_record_sql($sql,
                [
                    'courseid' => $cursosid,
                    'action' => 'viewed',
                    'userid' => $user->id,
                ]);
            if ($logresult && $logresult->timecreated) {
                $this->td(userdate($logresult->timecreated, get_string('strftimedatetime')), 'text-nowrap bg-success', 'DFF0D8');
            } else {
                $this->td('<span style="color: #282828">' . get_string_kopere('reports_noneaccess') .
                    '</span>', 'bg-warning text-nowrap', '#FCF8E3');
            }

            $sql
                = "SELECT timecreated
                      FROM {logstore_standard_log}
                     WHERE courseid = :courseid
                       AND action   = :action
                       AND userid   = :userid
                  ORDER BY timecreated DESC
                     LIMIT 1";

            $logresult = $DB->get_record_sql($sql,
                [
                    'courseid' => $cursosid,
                    'action' => 'viewed',
                    'userid' => $user->id,
                ]);
            if ($logresult && $logresult->timecreated) {
                $this->td(userdate($logresult->timecreated, get_string('strftimedatetime')), 'text-nowrap bg-success', 'DFF0D8');
            } else {
                $this->td('<span style="color: #282828">' . get_string_kopere('reports_noneaccess') .
                    '</span>', 'bg-warning text-nowrap', '#FCF8E3');
            }

            echo '</tr>';
        }

        echo '</table>';

        export::close();
        pagination::create($atualpage, $total->num_itens, $perpage);
    }

    /**
     * @param        $value
     * @param string $class
     * @param        $bgcolor
     */
    private function td($value, $class, $bgcolor) {
        echo "<td class='{$class}' bgcolor='{$bgcolor}'>{$value}</td>";
    }

    public function list_data() {
    }
}
