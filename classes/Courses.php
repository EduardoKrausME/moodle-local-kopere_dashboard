<?php

/**
 * User: Eduardo Kraus
 * Date: 31/01/17
 * Time: 05:32
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Html;
use local_kopere_dashboard\util\Json;
use local_kopere_dashboard\vo\kopere_dashboard_webpages;

class Courses
{
    public function dashboard ()
    {
        DashboardUtil::startPage ( 'Cursos' );

        echo '<div class="element-box">';
        echo '<h3>Lista de Cursos</h3>';

        $table = new DataTable();
        $table->addHeader ( '#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px' );
        $table->addHeader ( 'Nome do Curso', 'fullname' );
        $table->addHeader ( 'Nome curto', 'shortname' );
        $table->addHeader ( 'Visível', 'visible', TableHeaderItem::RENDERER_VISIBLE );
        $table->addHeader ( 'Nº alunos inscritos', 'inscritos', TableHeaderItem::TYPE_INT, null, 'width:50px;white-space:nowrap;' );
        //$table->addHeader ( 'Nº alunos que completaram', 'completed' );

        $table->setAjaxUrl ( 'Courses::loadAllCourses' );
        $table->setClickRedirect ( 'Courses::details&courseid={id}', 'id' );
        $table->printHeader ();
        $table->close ();

        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function loadAllCourses ()
    {
        global $DB;

        $data = $DB->get_records_sql (
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

        Json::encodeAndReturn ( $data );
    }

    public static function countAll ( $format = false )
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT count(*) as num FROM {course} WHERE id > 1' );

        if ( $format )
            return number_format ( $count->num, 0, ',', '.' );

        return $count->num;
    }

    public static function countAllVisibles ( $format = false )
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT count(*) as num FROM {course} WHERE id > 1 AND visible = 1' );

        if ( $format )
            return number_format ( $count->num, 0, ',', '.' );

        return $count->num;
    }

    public function details ()
    {
        global $DB, $CFG;

        $courseid = optional_param ( 'courseid', 0, PARAM_INT );
        if ( $courseid == 0 )
            Header::notfound ( 'CourseID inválido!' );

        $course = $DB->get_record ( 'course', array( 'id' => $courseid ) );
        Header::notfoundNull ( $course, 'Curso não localizado!' );

        DashboardUtil::startPage ( array(
            array( 'Courses::dashboard', 'Cursos' ),
            $course->fullname
        ) );

        echo '<div class="element-box">
                  <h3>Sumário 
                      ' . Botao::info ( 'Editar', $CFG->wwwroot . '/course/edit.php?id=' . $course->id . '#id_summary_editor', Botao::BTN_PEQUENO, false, true ) . '
                      ' . Botao::primary ( 'Acessar', $CFG->wwwroot . '/course/view.php?id=' . $course->id, Botao::BTN_PEQUENO, false, true ) . '
                  </h3>
                  <div class="panel panel-default">
                      <div class="panel-body">' . $course->summary . '</div>';
        $this->createStaticPage ( $course );
        echo '    </div>
              </div>';

        echo '<div class="element-box table-responsive">
                  <h3>Alunos matrículados</h3>';

        $table = new DataTable();
        $table->addHeader ( '#', 'id', TableHeaderItem::TYPE_INT );
        $table->addHeader ( 'Nome', 'nome' );
        $table->addHeader ( 'E-mail', 'email' );
        $table->addHeader ( 'Status da matrícula', 'status', TableHeaderItem::RENDERER_STATUS );

        $table->setAjaxUrl ( 'Enrol::ajaxdashboard&courseid=' . $courseid );
        $table->setClickRedirect ( 'Users::details&userid={id}', 'id' );
        $table->printHeader ();
        $table->close ();
        // $table->close ( true, 'order:[[1,"asc"]]' );

        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function createStaticPage ( $course )
    {
        global $DB;

        $menus = WebPages::listMenus ();
        if ( $menus ) {
            echo '<div class="panel-footer">';

            $webpagess = $DB->get_records ( 'kopere_dashboard_webpages', array( 'courseid' => $course->id ) );

            if ( $webpagess ) {
                echo '<h4>Páginas já criadas</h4>';

                /** @var kopere_dashboard_webpages $webpages */
                foreach ( $webpagess as $webpages )
                    echo '<p><a href="?WebPages::details&id=' . $webpages->id . '">&nbsp;&nbsp;&nbsp;&nbsp;' . $webpages->title . '</a></p>';
            }

            $form = new Form( 'WebPages::editSave', 'form-inline' );
            $form->createHiddenInput ( 'id', 0 );
            $form->createHiddenInput ( 'courseid', $course->id );
            $form->createHiddenInput ( 'title', $course->fullname );
            $form->createHiddenInput ( 'link', Html::link ( $course->fullname ) );
            $form->addInput (
                InputSelect::newInstance ()
                    ->setTitle ( 'Menu' )
                    ->setName ( 'menuid' )
                    ->setValues ( $menus )
            );
            $form->createHiddenInput ( 'theme', get_config ( 'local_kopere_dashboard', 'webpages_theme' ) );
            $form->createHiddenInput ( 'text', $course->summary );
            $form->createHiddenInput ( 'visible', 1 );
            $form->createSubmitInput ( 'Criar página com base neste sumário', 'margin-left-15' );
            $form->close ();
            echo '</div>';
        }
    }
}



