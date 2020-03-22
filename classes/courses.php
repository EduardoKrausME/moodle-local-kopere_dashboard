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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\config;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\html;
use local_kopere_dashboard\util\json;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\vo\kopere_dashboard_webpages;

/**
 * Class courses
 * @package local_kopere_dashboard
 */
class courses {

    /**
     * @throws \coding_exception
     */
    public function dashboard() {
        dashboard_util::add_breadcrumb(get_string_kopere('courses_title'));
        dashboard_util::add_breadcrumb(get_string_kopere('courses_title1'));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $table = new data_table();
        $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width: 20px');
        $table->add_header(get_string_kopere('courses_name'), 'fullname');
        $table->add_header(get_string_kopere('courses_shortname'), 'shortname');
        $table->add_header(get_string_kopere('courses_visible'), 'visible', table_header_item::RENDERER_VISIBLE);
        $table->add_header(get_string_kopere('courses_enrol'), 'inscritos',
            table_header_item::TYPE_INT, null, 'width:50px;white-space:nowrap;');

        $table->set_ajax_url('?classname=courses&method=load_all_courses');
        $table->set_click_redirect('?classname=courses&method=details&courseid={id}', 'id');
        $table->print_header();
        $table->close();

        echo '</div>';
        dashboard_util::end_page();
    }


    /**
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public function load_all_courses() {
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

        json::encode($data);
    }

    /**
     * @param bool $format
     * @return string
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function count_all($format) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) as num FROM {course} WHERE id > 1');

        if ($format) {
            return number_format($count->num, 0, get_string('decsep', 'langconfig'),
                get_string('thousandssep', 'langconfig'));
        }

        return $count->num;
    }

    /**
     * @param bool $format
     * @return string
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function count_all_visibles($format) {
        global $DB;

        $count = $DB->get_record_sql('SELECT count(*) as num FROM {course} WHERE id > 1 AND visible = 1');

        if ($format) {
            return number_format($count->num, 0, get_string('decsep', 'langconfig'),
                get_string('thousandssep', 'langconfig'));
        }

        return $count->num;
    }


    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function details() {
        global $DB, $CFG;

        $courseid = optional_param('courseid', 0, PARAM_INT);
        if ($courseid == 0) {
            header::notfound(get_string_kopere('courses_invalid'));
        }

        $course = $DB->get_record('course', array('id' => $courseid));
        header::notfound_null($course, get_string_kopere('courses_notound'));

        dashboard_util::add_breadcrumb(get_string_kopere('courses_title'), '?classname=courses&method=dashboard');
        dashboard_util::add_breadcrumb($course->fullname);
        dashboard_util::start_page();

        echo '<div class="element-box">
                  <h3>' . get_string_kopere('courses_sumary') . '
                      ' . button::info(get_string_kopere('courses_edit'), $CFG->wwwroot . '/course/edit.php?id=' .
                $course->id . '#id_summary_editor', button::BTN_PEQUENO, false, true) . '
                      ' . button::primary(get_string_kopere('courses_acess'), $CFG->wwwroot . '/course/view.php?id=' .
                $course->id, button::BTN_PEQUENO, false, true) . '
                  </h3>
                  <div class="panel panel-default">
                      <div class="panel-body">' . $course->summary . '</div>';
        $this->create_static_page($course);
        echo '    </div>
              </div>';

        echo '<div class="element-box table-responsive">';
        title_util::print_h3('courses_titleenrol');

        $table = new data_table();
        $table->add_header('#', 'id', table_header_item::TYPE_INT);
        $table->add_header(get_string_kopere('courses_student_name'), 'nome');
        $table->add_header(get_string_kopere('courses_student_email'), 'email');
        $table->add_header(get_string_kopere('courses_student_status'), 'status', table_header_item::RENDERER_STATUS);

        $table->set_ajax_url('?classname=enroll&method=ajax_dashboard&courseid=' . $courseid);
        $table->set_click_redirect('?classname=users&method=details&userid={id}', 'id');
        $table->print_header();
        $table->close();

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * @param $course
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public function create_static_page($course) {
        global $DB;

        $menus = webpages::list_menus();
        if ($menus) {
            echo '<div class="panel-footer">';

            $webpagess = $DB->get_records('kopere_dashboard_webpages', array('courseid' => $course->id));

            if ($webpagess) {
                title_util::print_h4('courses_page_title');

                /** @var kopere_dashboard_webpages $webpages */
                foreach ($webpagess as $webpages) {
                    echo '<p><a href="?classname=webpages&method=page_details&id=' . $webpages->id . '">&nbsp;&nbsp;&nbsp;&nbsp;' .
                        $webpages->title . '</a></p>';
                }
            }

            $form = new form('?classname=webpages&method=page_edit_save', 'form-inline');
            $form->create_hidden_input('id', 0);
            $form->create_hidden_input('courseid', $course->id);
            $form->create_hidden_input('title', $course->fullname);
            $form->create_hidden_input('link', html::link($course->fullname));
            $form->add_input(
                input_select::new_instance()
                    ->set_title('Menu')
                    ->set_name('menuid')
                    ->set_values($menus)
            );
            $form->create_hidden_input('theme', config::get_key('webpages_theme'));
            $form->create_hidden_input('text', $course->summary);
            $form->create_hidden_input('visible', 1);
            $form->create_submit_input(get_string_kopere('courses_page_create'), 'margin-left-15');
            $form->close();
            echo '</div>';
        }
    }
}