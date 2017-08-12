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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\util\Export;
use local_kopere_dashboard\util\Header;

class ReportsCourseAccess {

    /**
     * @return string
     */
    public function name() {
        return get_string_kopere('reports_report_courses-3');
    }

    /**
     * @return boolean
     */
    public function isEnable() {
        return true;
    }

    /**
     * @return void
     */
    public function generate() {
        global $DB, $CFG;

        $cursosId = optional_param('id', 0, PARAM_INT);
        if ($cursosId == 0) {
            Header::notfound(get_string_kopere('courses_notound'));
        }

        $course = $DB->get_record('course', array('id' => $cursosId));
        Header::notfoundNull($course, get_string_kopere('courses_notound'));

        $groups = $DB->get_records('groups', array('courseid'=>$course->id));

        echo '<script>
                  document.body.className += " menu-w-90";
              </script>';

        $sections = $DB->get_records('course_sections', array('course' => $cursosId), 'section asc');

        Button::info(get_string_kopere('reports_export'), "?{$_SERVER['QUERY_STRING']}&export=xls");

        $export = optional_param('export', '', PARAM_TEXT);
        Export::exportHeader($export, "Lista de alunos - $course->fullname");

        echo '<table id="list-course-access" class="table table-bordered table-hover" border="1">';
        echo '<thead>';
        $printSessoes = '';

        $courseinfo = array();
        $modinfo = array();
        foreach ($sections as $key => $section) {
            $partesModInfo = explode(',', $section->sequence);
            $countModInfo = 0;
            foreach ($partesModInfo as $parte) {
                $sql
                    = "SELECT cm.*, cm.id AS course_modules_id, m.*, m.id AS modules_id
                         FROM {course_modules} cm
                         JOIN {modules} m ON cm.module = m.id
                        WHERE cm.id = :cmid";

                $module = $DB->get_record_sql($sql, array('cmid' => $parte));

                if ($module != null) {

                    if ($module->name == 'label') {
                        continue;
                    }
                    if ($module->name == 'videobusca') {
                        continue;
                    }

                    $moduleinfo = $DB->get_record($module->name, array('id' => $module->instance));
                    $module->moduleinfo = $moduleinfo;

                    $modinfo[] = $module;

                    if (isset($courseinfo[$module->course])) {
                        $courseinfo[$module->course]++;
                    } else {
                        $courseinfo[$module->course] = 1;
                    }

                    $countModInfo++;
                }
            }

            if (strlen($section->sequence)) {
                $printSessoes .= '<th  bgcolor="#979797" align="center" colspan="' . ($countModInfo * 2) . '" style="text-align:center;">';

                if (strlen($section->name)) {
                    $printSessoes .= $section->name;
                } else {
                    $printSessoes .= 'Sessão ' . $key;
                }
                $printSessoes .= '</th>';
            }
        }

        $groupsCols = '';
        if( $groups ){
            $groupsCols = '<th rowspan="2" align="center" bgcolor="#979797" style="text-align:center;" >'.get_string_kopere('reports_groupname').'</th>';
        }

        echo '<tr bgcolor="#979797" style="background-color: #979797;">
                  <th colspan="2" align="center" bgcolor="#979797" style="text-align:center;" >'.get_string_kopere('courses_titleenrol').'</th>
                  '.$groupsCols . $printSessoes . '
              </tr>';

        echo '<tr bgcolor="#C5C5C5" style="background-color: #c5c5c5;" >
                <td align="center" bgcolor="#979797" style="text-align:center;">'.get_string_kopere('user_table_fullname').'</td>
                <td align="center" bgcolor="#979797" style="text-align:center;">'.get_string_kopere('user_table_email').'</td>';

        foreach ($modinfo as $infos) {
            $link = $CFG->wwwroot . '/course/view.php?id=' . $infos->course . '#module-' . $infos->course_modules_id;
            echo '<th bgcolor="#c5c5c5" colspan="2" align="center" style="text-align: center" >
              <a href="' . $link . '" target="_blank">' . $infos->moduleinfo->name . '</a>
          </th>';
        }
        echo '</tr>';
        echo '</thead>';

        $sql
            = "SELECT DISTINCT u.*
                 FROM {context} c
                 JOIN {role_assignments} ra ON ra.contextid = c.id
                 JOIN {user} u              ON ra.userid    = u.id
                WHERE c.contextlevel = :contextlevel
                  AND c.instanceid   = :instanceid";

        $allUserCourse = $DB->get_records_sql($sql,
            array(
                'contextlevel' => CONTEXT_COURSE,
                'instanceid' => $cursosId
            ));

        foreach ($allUserCourse as $user) {
            echo '<tr>';
            $link = $CFG->wwwroot . '/user/view.php?id=' . $user->id . '&course=' . $cursosId;
            $this->td('<a href="' . $link . '" target="moodle">' . fullname($user) . '</a>', 'bg-info text-nowrap', '#D9EDF7');
            $this->td($user->email, 'bg-info text-nowrap', '#D9EDF7');


            if( $groups ){
                
                $sql = "SELECT * 
                          FROM {groups_members} gm
                          JOIN {groups} g ON g.id = gm.groupid
                         WHERE g.courseid = :courseid
                           AND gm.userid  = :userid";
                $groupsUser = $DB->get_records_sql($sql,
                    array(
                        'courseid' => $course->id,
                        'userid'   => $user->id
                    ));
                $groupsUserPrint = array();
                foreach($groupsUser as $groupUser){
                    $groupsUserPrint[] = $groupUser->name;
                }
                $this->td(implode('<br/>', $groupsUserPrint), 'bg-info text-nowrap', '#D9EDF7');
            }

            foreach ($modinfo as $infos) {

                $sql
                    = "SELECT COUNT(*) as contagem, date_format(from_unixtime(MAX(timecreated)), '%d/%m/%Y %H:%i') as datavisualiza
                         FROM {logstore_standard_log}
                        WHERE courseid = :courseid
                          AND contextinstanceid = :contextinstanceid
                          AND action = :action
                          AND userid = :userid";

                $logResult = $DB->get_record_sql($sql,
                    array(
                        'courseid' => $cursosId,
                        'contextinstanceid' => $infos->course_modules_id,
                        'action' => 'viewed',
                        'userid' => $user->id
                    ));

                if ($logResult->contagem) {
                    $this->td( get_string_kopere('reports_access_n',  $logResult->contagem), 'text-nowrap bg-success', 'DFF0D8');
                    $this->td($logResult->datavisualiza, 'text-nowrap bg-success', '#DFF0D8');
                } else {
                    $this->td2('<span style="color: #282828">'.get_string_kopere('reports_noneaccess').'</span>', 'bg-warning text-nowrap', '#FCF8E3');
                }
            }
            echo '</tr>';
        }

        echo '</table>';

        Export::exportClose();
    }

    private function td($value, $class = '', $bgcolor) {
        echo '<td class="' . $class . '" bgcolor="' . $bgcolor . '">' . $value . '</td>';
    }

    private function td2($value, $class = '', $bgcolor) {
        echo '<td colspan="2" class="' . $class . '" bgcolor="' . $bgcolor . '">' . $value . '</td>';
    }

    public function listData() {
    }
}