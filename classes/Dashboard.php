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
 * @created    30/01/17 09:39
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\report\Files;
use local_kopere_dashboard\util\BytesUtil;
use local_kopere_dashboard\util\DashboardUtil;

class Dashboard {
    public function start() {
        DashboardUtil::startPage(array());

        echo '<div class="element-content">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="element-box color1">
                            <div class="label">Usuários / Ativos</div>
                            <div class="value"><a href="?Users::dashboard">
                                ' . Users::countAll(true) . ' / ' . Users::countAllLearners(true) . '</a></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="element-box color2">
                            <div class="label">Online / Última hora</div>
                            <div class="value"><a href="?UsersOnline::dashboard">
                                <span id="user-count-online">' . UsersOnline::countOnline(10) . '</span>
                                / ' . UsersOnline::countOnline(60) . '</a></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="element-box color3">
                            <div class="label">Cursos / Visíveis</div>
                            <div class="value"><a href="?Courses::dashboard">
                            ' . Courses::countAll(true) . '
                                / ' . Courses::countAllVisibles(true) . '</a></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="element-box color4">
                            <div class="label">Uso de Disco</div>
                            <div class="value"><a href="?ReportsDisk::all">
                            ' . BytesUtil::sizeToByte(Files::countAllSpace()) . '</a></div>
                        </div>
                    </div>
                </div>
            </div>';

        echo '<div class="row">';
        $this->lastGrades();
        $this->lastMatriculas();
        echo '</div>';

        DashboardUtil::endPage();
    }

    private function lastGrades() {
        global $DB, $PAGE;
        echo '<div class="col-sm-6">
                  <div class="element-box">
                      <h3>Últimas notas</h3>';

        $grade = new Grade();
        $lastGrades = $grade->getLastGrades();

        foreach ($lastGrades as $grade) {

            $user = $DB->get_record('user', array('id' => $grade->userid));

            $userpicture = new \user_picture($user);
            $userpicture->size = 1;
            $profileimageurl = $userpicture->get_url($PAGE)->out(false);

            if ($grade->itemtype == 'mod') {
                $avaliacao = " no módulo <a>{$grade->itemname}</a> do curso <a href='?Courses::details&courseid={$grade->courseid}'>{$grade->coursename}</a>";
            } else if ($grade->itemtype == 'course') {
                $avaliacao = " no curso <a href='?Courses::details&courseid={$grade->courseid}'>{$grade->coursename}</a>";
            } else {
                continue;
            }

            $nota = number_format($grade->finalgrade, 1, ',', '') . ' de ' . intval($grade->rawgrademax);

            echo '<div class="media dashboard-grade-list">
                      <div class="media-left">
                          <img title="' . fullname($user) . '" src="' . $profileimageurl . '" class="media-object" >
                      </div>
                      <div class="media-body">
                          <h4 class="media-heading">
                              <a data-toggle="modal" data-target="#modal-details"
                                 href="?Users::details&userid=' . $user->id . '"
                                 data-href="open-ajax-table.php?Users::details&userid=' . $user->id . '">' . fullname($user) . '</a>
                          </h4>
                          <p>Recebeu nota ' . $nota . $avaliacao . '</p>
                          <div class="date"><small>Em <i>' . userdate($grade->timemodified, '%d de %B de %Y às %H:%M') . '</i></small></div>
                      </div>
                      <div class="clear"></div>
                  </div>';

        }
        echo '    </div>
              </div>';
    }

    private function lastMatriculas() {
        global $DB, $PAGE;
        echo '<div class="col-sm-6 ">
                  <div class="element-box">
                      <h3>Últimas Matrículas</h3>';

        $enrol = new Enrol();
        $lastEnrol = $enrol->lastEnrol();

        foreach ($lastEnrol as $enrol) {

            $user = $DB->get_record('user', array('id' => $enrol->userid));

            $userpicture = new \user_picture($user);
            $userpicture->size = 1;
            $profileimageurl = $userpicture->get_url($PAGE)->out(false);

            $statusMatricula = '<span class="btn-danger">matrícula esta inativa</span>';
            if ($enrol->status == 0) {
                $statusMatricula = '<span class="btn-success">matrícula esta ativa</span>';
            }

            echo '<div class="media dashboard-grade-list">
                      <div class="media-left">
                          <img title="' . fullname($user) . '" src="' . $profileimageurl . '" class="media-object" >
                      </div>
                      <div class="media-body">
                          <h4 class="media-heading">
                              <a data-toggle="modal" data-target="#modal-details"
                                 href="?Users::details&userid=' . $user->id . '"
                                 data-href="open-ajax-table.php?Users::details&userid=' . $user->id . '">' . fullname($user) . '</a>
                          </h4>
                          <p>
                              Matriculou-se no curso <a href=\'?Courses::details&courseid=' . $enrol->courseid . '\'>' . $enrol->fullname . '</a> e a
                              <span class="status">' . $statusMatricula . '</span>
                          </p>
                          <div class="date"><small>Última alteração em <i>' . userdate($enrol->timemodified, '%d de %B de %Y às %H:%M') . '</i></small></div>
                      </div>
                      <div class="clear"></div>
                  </div>';
        }

        echo '    </div>
              </div>';
    }
}