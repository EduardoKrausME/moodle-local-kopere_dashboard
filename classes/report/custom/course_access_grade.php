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
 * @created    25/05/17 18:31
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report\custom;

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\util\export;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\url_util;

/**
 * Class course_access_grade
 *
 * @package local_kopere_dashboard\report\custom
 */
class course_access_grade {

    /**
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function name() {
        $cursosid = optional_param('courseid', 0, PARAM_INT);
        return get_string_kopere('reports_report_courses-4') . ' ' . get_course($cursosid)->fullname;
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

        require_once("{$CFG->libdir}/gradelib.php");

        $courseid = optional_param('courseid', 0, PARAM_INT);
        if ($courseid == 0) {
            header::notfound(get_string_kopere('courses_notound'));
        }

        $perpage = 10;
        $atualpage = optional_param('page', 1, PARAM_INT);
        $startlimit = ($atualpage - 1) * $perpage;

        $course = $DB->get_record('course', ['id' => $courseid]);
        header::notfound_null($course, get_string_kopere('courses_notound'));

        $groups = $DB->get_records('groups', ['courseid' => $course->id]);

        echo '<script>
                  document.body.className += " menu-w-90";
              </script>';

        $sections = $DB->get_records('course_sections', ['course' => $courseid], 'section asc');

        button::info(get_string_kopere('reports_export'), url_util::querystring() . "&export=xls");

        session_write_close();
        $export = optional_param('export', '', PARAM_TEXT);
        export::header($export, $course->fullname);

        echo '<table id="list-course-access" class="table table-bordered table-hover" border="1">';
        echo '<thead>';
        $printsessoes = '';

        $courseinfo = [];
        $modinfo = [];
        foreach ($sections as $key => $section) {
            $partesmodinfo = explode(',', $section->sequence);
            $countmodinfo = 0;
            foreach ($partesmodinfo as $parte) {
                $sql = "SELECT cm.*, cm.id AS course_modules_id, m.*, m.id AS modules_id, cm.instance
                          FROM {course_modules} cm
                          JOIN {modules} m ON cm.module = m.id
                         WHERE cm.id = :cmid
                           AND cm.deletioninprogress = 0";

                $module = $DB->get_record_sql($sql, ['cmid' => intval($parte)]);

                if ($module != null) {

                    if ($module->name == 'label') {
                        continue;
                    }
                    if ($module->name == 'videobusca') {
                        continue;
                    }

                    $moduleinfo = $DB->get_record($module->name, ['id' => $module->instance]);
                    $module->moduleinfo = $moduleinfo;

                    if (isset($courseinfo[$module->course])) {
                        $courseinfo[$module->course]++;
                    } else {
                        $courseinfo[$module->course] = 1;
                    }

                    if ($module->instance) {
                        $module->grade = $DB->get_record('grade_items',
                            [
                                'courseid' => $courseid,
                                'iteminstance' => $moduleinfo->id,
                                'itemtype' => 'mod',
                                'itemmodule' => $module->name,
                            ]);
                    }

                    $modinfo[] = $module;

                    if ($module->grade) {
                        $countmodinfo += 3;
                    } else {
                        $countmodinfo += 2;
                    }
                }
            }

            if (strlen($section->sequence)) {
                $printsessoes .= '<th bgcolor="#979797" align="center"
                                       colspan="' . $countmodinfo . '" style="text-align:center;">';

                if (strlen($section->name)) {
                    $printsessoes .= $section->name;
                } else {
                    $printsessoes .= "Sessão {$key}";
                }
                $printsessoes .= '</th>';
            }
        }

        $groupscols = '';
        if ($groups) {
            $groupscols = '<th rowspan="2" align="center" bgcolor="#979797" style="text-align:center;" >' .
                get_string_kopere('reports_groupname') . '</th>';
        }

        echo '<tr bgcolor="#979797" style="background-color: #979797;">
                  <th colspan="2" align="center" bgcolor="#979797" style="text-align:center;" >' .
            get_string_kopere('courses_titleenrol') . '</th>
                  ' . $groupscols . $printsessoes . '
              </tr>';

        echo '<tr bgcolor="#C5C5C5" style="background-color: #c5c5c5;" >
                <td align="center" bgcolor="#979797" style="text-align:center;">' .
            get_string_kopere('user_table_fullname') . '</td>
                <td align="center" bgcolor="#979797" style="text-align:center;">' .
            get_string_kopere('user_table_email') . '</td>';

        foreach ($modinfo as $infos) {
            $link = "{$CFG->wwwroot}/course/view.php?id={$infos->course}#module-{$infos->course_modules_id}";

            $colspan = 2;
            if ($infos->grade) {
                $colspan = 3;
            }

            echo "<th bgcolor='#c5c5c5' colspan='{$colspan}' align='center' style='text-align: center' >
                      <a href='{$link}' target='_blank'>{$infos->moduleinfo->name}</a>
                  </th>";
            @ob_flush();
        }
        echo '</tr>';
        echo '</thead>';

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
                'instanceid' => $courseid,
            ]);

        $total = $DB->get_record_sql("SELECT FOUND_ROWS() as num_itens");

        foreach ($allusercourse as $user) {
            echo '<tr>';
            $link = "{$CFG->wwwroot}/user/view.php?id={$user->id}&course={$courseid}";
            $this->td("<a href='{$link}' target='moodle'>" . fullname($user) . "</a>", 'bg-info text-nowrap', '#D9EDF7');
            $this->td($user->email, 'bg-info text-nowrap', '#D9EDF7');

            if ($groups) {

                $sql = "SELECT *
                          FROM {groups_members} gm
                          JOIN {groups} g ON g.id = gm.groupid
                         WHERE g.courseid = :courseid
                           AND gm.userid  = :userid";
                $groupsuser = $DB->get_records_sql($sql,
                    [
                        'courseid' => $course->id,
                        'userid' => $user->id,
                    ]);
                $groupsuserprint = [];
                foreach ($groupsuser as $groupuser) {
                    $groupsuserprint[] = $groupuser->name;
                }
                $this->td(implode('<br>', $groupsuserprint), 'bg-info text-nowrap', '#D9EDF7');
            }

            foreach ($modinfo as $infos) {

                $sql = "SELECT COUNT(*) AS contagem, timecreated
                          FROM {logstore_standard_log}
                         WHERE courseid          = :courseid
                           AND contextinstanceid = :contextinstanceid
                           AND action            = :action
                           AND userid            = :userid
                      GROUP BY timecreated
                      ORDER BY timecreated DESC
                         LIMIT 1";

                $logresult = $DB->get_record_sql($sql,
                    [
                        'courseid' => $courseid,
                        'contextinstanceid' => $infos->course_modules_id,
                        'action' => 'viewed',
                        'userid' => $user->id,
                    ]);

                if ($logresult && $logresult->contagem) {
                    $this->td(get_string_kopere('reports_access_n', $logresult->contagem), 'text-nowrap bg-success', 'DFF0D8');
                    $this->td(userdate($logresult->timecreated, get_string('strftimedatetime')),
                        'text-nowrap bg-success', '#DFF0D8');

                    if ($infos->grade) {
                        $gradinginfo = grade_get_grades($courseid, 'mod', $infos->name, $infos->instance, $user->id);
                        foreach ($gradinginfo->items[0]->grades as $grade) {
                            $this->td($grade->str_grade, 'text-nowrap bg-success', '#D000D8');
                            break;
                        }
                    }
                } else {
                    $this->td2('<span style="color: #282828">' . get_string_kopere('reports_noneaccess') .
                        '</span>', 'bg-warning text-nowrap', '#FCF8E3');

                    if ($infos->grade) {
                        $this->td('--', 'text-nowrap bg-success', '#D000D8');
                    }
                }
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

    /**
     * @param        $value
     * @param string $class
     * @param        $bgcolor
     */
    private function td2($value, $class, $bgcolor) {
        echo "<td colspan='2' class='{$class}' bgcolor='{$bgcolor}'>{$value}</td>";
    }

    public function list_data() {
    }
}
