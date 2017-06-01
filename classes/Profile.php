<?php
/**
 * User: Eduardo Kraus
 * Date: 15/05/17
 * Time: 03:13
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;

class Profile
{
    public  function details ()
    {
        global $DB, $PAGE, $CFG;

        $userid = optional_param ( 'userid', 0, PARAM_INT );
        if ( $userid == 0 )
            Header::notfound ( 'UserId inválido!' );

        $user = $DB->get_record ( 'user', array( 'id' => $userid ) );

            Header::notfoundNull ( $user, 'Usuário não localizado!' );


        DashboardUtil::startPage ( array(
            array( '?Users::dashboard', 'Usuários' ),
            fullname ( $user )
        ) );

        echo '<div class="element-box">';

        $userpicture       = new \user_picture( $user );
        $userpicture->size = 110;
        $profileimageurl   = $userpicture->get_url ( $PAGE )->out ( false );

        $courses = enrol_get_all_users_courses ( $user->id );

        $courseHtml = '';
        if ( !count ( $courses ) )
            $courseHtml = Mensagem::warning ( 'Usuário não possui nenhuma matrícula!' );
        foreach ( $courses as $course ) {
            $sql
                    = "SELECT ue.*
                         FROM {user_enrolments} ue
                         JOIN {enrol} e ON ( e.id = ue.enrolid AND e.courseid = :courseid )
                        WHERE ue.userid = :userid";
            $params = array( 'userid'   => $user->id,
                             'courseid' => $course->id
            );

            $enrolment = $DB->get_record_sql ( $sql, $params );

            if ( $enrolment->timeend == 0 )
                $expirationEnd = 'e nunca expira';
            else
                $expirationEnd = 'expira em <em>' . userdate ( $enrolment->timeend, '%d de %B de %Y às %H:%M' ) . '</em>';

            $roleAssignments = $DB->get_records ( 'role_assignments',
                array( 'contextid' => $course->ctxid,
                       'userid'    => $user->id
                ), '', 'DISTINCT roleid' );

            $roleHtml = '';
            foreach ( $roleAssignments as $roleAssignment ) {
                $role = $DB->get_record ( 'role', array( 'id' => $roleAssignment->roleid ) );
                $roleHtml .= '<span class="btn btn-default">' . role_get_name ( $role ) . '</span>';
            }

            $courseHtml
                .= '<li>
                    <h4 class="title">' . $course->fullname . '
                        <span class="status">' . ( $enrolment->status == 0 ? '<span class="btn-success">Matrícula esta ativa</span>' : '<span class="btn-danger">Matrícula esta inativa</span>' ) . '</span>
                    </h4>
                    <div>Início em <em>' . userdate ( $enrolment->timestart, '%d de %B de %Y às %H:%M' ) . '</em> ' . $expirationEnd . ' - 
                        <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-edit"
                                data-href="open-ajax-table.php?UserEnrolment::mathedit&courseid=' . $course->id . '&ueid=' . $enrolment->id . '">Editar</button>
                    </div>
                    <div class="roles">Perfis: ' . $roleHtml . '</div>
                </li>';
        }


        echo '<div class="profile-content">
                  <div class="table">
                      <div class="profile">
                          <img src="' . $profileimageurl . '" alt="' . fullname ( $user ) . '">
                          <span class="name">' . $user->firstname . '
                                <span class="last">' . $user->lastname . '</span>
                                <span class="city">' . $user->city . '</span>
                          </span>
                          <div class="desc">' . $user->description . '</div>
                          
                          <h2>Cursos inscritos</h2>
                          <ul class="personalDev">
                              ' . $courseHtml . '
                          </ul>
                          
                      </div>
                       <div class="info">
                          <h3>Acessos</h3>
                          <p>Primeiro acesso em:<br/> <strong>' . userdate ( $user->firstaccess, '%d de %B de %Y às %H:%M' ) . '</strong></p>
                          <p>Último acesso em:<br/>   <strong>' . userdate ( $user->lastaccess,  '%d de %B de %Y às %H:%M' ) . '</strong></p>
                          <p>Último login em:<br/>    <strong>' . userdate ( $user->lastlogin,   '%d de %B de %Y às %H:%M' ) . '</strong></p>
                          <h3>Dados</h3>
                          <p><a href="mailto:' . $user->email . '">' . $user->email . '</a></p>
                          <p>' . $user->phone1 . '</p>
                          <p>' . $user->phone2 . '</p>
                          <h3>Links Úteis</h3>
                          <p><a target="_blank" href="' . $CFG->wwwroot . '/user/profile.php?id=' . $user->id . '">Ver perfil</a></p>
                          <p><a target="_blank" href="' . $CFG->wwwroot . '/user/editadvanced.php?id=' . $user->id . '">Editar perfil</a></p>
                          <p><a target="_blank" href="' . $CFG->wwwroot . '/course/loginas.php?id=1&user=' . $user->id . '&sesskey=' . sesskey () . '">Acessar como</a></p>
                      </div>';
        echo '    </div>    
              </div>';

        echo '</div>';
        DashboardUtil::endPage ();
    }
}