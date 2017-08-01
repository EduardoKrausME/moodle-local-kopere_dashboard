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
 * @created    31/01/17 05:32
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Html;
use local_kopere_dashboard\util\Json;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\vo\kopere_dashboard_webpages;

class Courses {
    public function dashboard() {
        DashboardUtil::startPage(get_string_kopere('courses_title'));

        echo '<div class="element-box">';
        TitleUtil::printH3('courses_title1');

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
        $table->addHeader(get_string_kopere('courses_name'), 'fullname');
        $table->addHeader(get_string_kopere('courses_shortname'), 'shortname');
        $table->addHeader(get_string_kopere('courses_visible'), 'visible', TableHeaderItem::RENDERER_VISIBLE);
        $table->addHeader(get_string_kopere('courses_enrol'), 'inscritos', TableHeaderItem::TYPE_INT, null, 'width:50px;white-space:nowrap;');
        // $table->addHeader ( 'Nº alunos que completaram', 'completed' );

        $table->setAjaxUrl('Courses::loadAllCourses');
        $table->setClickRedirect('Courses::details&courseid={id}', 'id');
        $table->printHeader();
        $table->close();

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function loadAllCourses() {
        global $DB;

        $data = $DB->get_records_sql(
            "SELECT c.id, c.fullname, c.shortname, c.visible,
                     (
                         SELECT COUNT( DISTINCT u.id )
                           FROM {user_enrolments} ue
                           JOIN {role_assignments} ra ON ue.userid = ra.userid
                           JOIN {enrol} e             ON e.id = ue.enrolid
                           JOIN {user} u              ON u.id = ue.userid
                          WHERE e.courseid = c.id
                            AND u.deleted = 0
                     ) AS inscritos
              FROM {course} c
             WHERE c.id > 1"
        );

        Json::encodeAndReturn($data);
    }

    public static function countAll($format = false) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) as num FROM {course} WHERE id > 1');

        if ($format) {
            return number_format($count->num, 0, ',', '.');
        }

        return $count->num;
    }

    public static function countAllVisibles($format = false) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) as num FROM {course} WHERE id > 1 AND visible = 1');

        if ($format) {
            return number_format($count->num, 0, ',', '.');
        }

        return $count->num;
    }

    public function details() {
        global $DB, $CFG;

        $courseid = optional_param('courseid', 0, PARAM_INT);
        if ($courseid == 0) {
            Header::notfound(get_string_kopere('courses_invalid'));
        }

        $course = $DB->get_record('course', array('id' => $courseid));
        Header::notfoundNull($course, get_string_kopere('courses_notound'));

        DashboardUtil::startPage(array(
            array('Courses::dashboard', get_string_kopere('courses_title')),
            $course->fullname
        ));

        echo '<div class="element-box">
                  <h3>'.get_string_kopere('courses_sumary').'
                      ' . Button::info(get_string_kopere('courses_edit'), $CFG->wwwroot . '/course/edit.php?id=' . $course->id . '#id_summary_editor', Button::BTN_PEQUENO, false, true) . '
                      ' . Button::primary(get_string_kopere('courses_acess'), $CFG->wwwroot . '/course/view.php?id=' . $course->id, Button::BTN_PEQUENO, false, true) . '
                  </h3>
                  <div class="panel panel-default">
                      <div class="panel-body">' . $course->summary . '</div>';
        $this->createStaticPage($course);
        echo '    </div>
              </div>';

        echo '<div class="element-box table-responsive">';
        TitleUtil::printH3('courses_titleenrol');

        $table = new DataTable();
        $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT);
        $table->addHeader(get_string_kopere('courses_student_name'), 'nome');
        $table->addHeader(get_string_kopere('courses_student_email'), 'email');
        $table->addHeader(get_string_kopere('courses_student_status'), 'status', TableHeaderItem::RENDERER_STATUS);

        $table->setAjaxUrl('Enrol::ajaxdashboard&courseid=' . $courseid);
        $table->setClickRedirect('Users::details&userid={id}', 'id');
        $table->printHeader();
        $table->close();
        // $table->close ( true, 'order:[[1,"asc"]]' );

        echo '</div>';
        DashboardUtil::endPage();
    }

    public function createStaticPage($course) {
        global $DB;

        $menus = WebPages::listMenus();
        if ($menus) {
            echo '<div class="panel-footer">';

            $webpagess = $DB->get_records('kopere_dashboard_webpages', array('courseid' => $course->id));

            if ($webpagess) {
                TitleUtil::printH4('courses_page_title');

                /** @var kopere_dashboard_webpages $webpages */
                foreach ($webpagess as $webpages) {
                    echo '<p><a href="?WebPages::details&id=' . $webpages->id . '">&nbsp;&nbsp;&nbsp;&nbsp;' . $webpages->title . '</a></p>';
                }
            }

            $form = new Form('WebPages::editPageSave', 'form-inline');
            $form->createHiddenInput('id', 0);
            $form->createHiddenInput('courseid', $course->id);
            $form->createHiddenInput('title', $course->fullname);
            $form->createHiddenInput('link', Html::link($course->fullname));
            $form->addInput(
                InputSelect::newInstance()
                    ->setTitle('Menu')
                    ->setName('menuid')
                    ->setValues($menus)
            );
            $form->createHiddenInput('theme', get_config('local_kopere_dashboard', 'webpages_theme'));
            $form->createHiddenInput('text', $course->summary);
            $form->createHiddenInput('visible', 1);
            $form->createSubmitInput(get_string_kopere('courses_page_create'), 'margin-left-15');
            $form->close();
            echo '</div>';
        }
    }
}