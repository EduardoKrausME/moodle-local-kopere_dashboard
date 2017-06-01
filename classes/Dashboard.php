<?php

/**
 * User: Eduardo Kraus
 * Date: 30/01/17
 * Time: 09:39
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\report\Files;
use local_kopere_dashboard\util\BytesUtil;
use local_kopere_dashboard\util\DashboardUtil;

class Dashboard
{
    public function start ()
    {
        DashboardUtil::startPage ( array() );

        echo '<div class="element-content">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="element-box">
                            <div class="label">Usuários / Ativos</div>
                            <div class="value"><a href="?Users::dashboard">' . Users::countAll ( true ) . ' / ' . Users::countAllLearners ( true ) . '</a></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="element-box">
                            <div class="label">Online / Última hora</div>
                            <div class="value"><a href="?UsersOnline::dashboard"><span id="user-count-online">' . UsersOnline::countOnline ( 10 ) . '</span> / ' . UsersOnline::countOnline ( 60 ) . '</a></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="element-box">
                            <div class="label">Cursos / Visíveis</div>
                            <div class="value"><a href="?Courses::dashboard">' . Courses::countAll ( true ) . ' / ' . Courses::countAllVisibles ( true ) . '</a></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="element-box">
                            <div class="label">Uso de Disco</div>
                            <div class="value"><a href="?ReportsDisk::all">' . BytesUtil::sizeToByte ( Files::countAllSpace () ) . '</a></div>
                        </div>
                    </div>
                </div>
            </div>';

        echo '<div class="row">';
        $this->lastGrades ();
        $this->lastMatriculas ();
        echo '</div>';

        DashboardUtil::endPage ();
    }

    private function lastGrades ()
    {
        global $DB, $PAGE;
        echo '<div class="col-sm-6">
                  <div class="element-box">
                      <h3>Últimas notas</h3>';

        $grade = new Grade();
        $lastGrades = $grade->getLastGrades ();

        foreach ( $lastGrades as $grade ) {

            $user = $DB->get_record ( 'user', array( 'id' => $grade->userid ) );

            $userpicture       = new \user_picture( $user );
            $userpicture->size = 1;
            $profileimageurl   = $userpicture->get_url ( $PAGE )->out ( false );

            if ( $grade->itemtype == 'mod' )
                $avaliacao = " no módulo <a>{$grade->itemname}</a> do curso <a href='?Courses::details&courseid={$grade->courseid}'>{$grade->coursename}</a>";
            elseif ( $grade->itemtype == 'course' )
                $avaliacao = " no curso <a href='?Courses::details&courseid={$grade->courseid}'>{$grade->coursename}</a>";
            else
                continue;

            $nota = number_format ( $grade->finalgrade, 1, ',', '' ) . ' de ' . intval ( $grade->rawgrademax );

            echo '<div class="media dashboard-grade-list">
                      <div class="media-left">
                          <img title="' . fullname ( $user ) . '" src="' . $profileimageurl . '" class="media-object" >
                      </div>
                      <div class="media-body">
                          <h4 class="media-heading">
                              <a data-toggle="modal" data-target="#modal-details" 
                                 href="?Users::details&userid=' . $user->id . '"
                                 data-href="open-ajax-table.php?Users::details&userid=' . $user->id . '">' . fullname ( $user ) . '</a>
                          </h4>
                          <p>Recebeu nota ' . $nota . $avaliacao . '</p>
                          <div class="date"><small>Em <i>' . userdate ( $grade->timemodified, '%d de %B de %Y às %H:%M' ) . '</i></small></div>
                      </div>
                      <div class="clear"></div>
                  </div>';

        }
        echo '    </div>
              </div>';
    }

    private function lastMatriculas ()
    {
        global $DB, $PAGE;
        echo '<div class="col-sm-6 ">
                  <div class="element-box">
                      <h3>Últimas Matrículas</h3>';

        $enrol     = new Enrol();
        $lastEnrol = $enrol->lastEnrol ();

        foreach ( $lastEnrol as $enrol ) {

            $user = $DB->get_record ( 'user', array( 'id' => $enrol->userid ) );

            $userpicture       = new \user_picture( $user );
            $userpicture->size = 1;
            $profileimageurl   = $userpicture->get_url ( $PAGE )->out ( false );


            echo '<div class="media dashboard-grade-list">
                      <div class="media-left">
                          <img title="' . fullname ( $user ) . '" src="' . $profileimageurl . '" class="media-object" >
                      </div>
                      <div class="media-body">
                          <h4 class="media-heading">
                              <a data-toggle="modal" data-target="#modal-details" 
                                 href="?Users::details&userid=' . $user->id . '"
                                 data-href="open-ajax-table.php?Users::details&userid=' . $user->id . '">' . fullname ( $user ) . '</a>
                          </h4>
                          <p>
                              Matriculou-se no curso <a href=\'?Courses::details&courseid=' . $enrol->courseid . '\'>' . $enrol->fullname . '</a> e a 
                              <span class="status">' . ( $enrol->status == 0 ? '<span class="btn-success">matrícula esta ativa</span>' : '<span class="btn-danger">matrícula esta inativa</span>' ) . '</span>
                          </p>
                          <div class="date"><small>Última alteração em <i>' . userdate ( $enrol->timemodified, '%d de %B de %Y às %H:%M' ) . '</i></small></div>
                      </div>
                      <div class="clear"></div>
                  </div>';
        }

        echo '    </div>
              </div>';
    }
}