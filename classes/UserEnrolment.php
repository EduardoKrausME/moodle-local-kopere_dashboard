<?php
/**
 * User: Eduardo Kraus
 * Date: 15/05/17
 * Time: 14:26
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;

class UserEnrolment
{
    public function mathedit ()
    {
        global $DB;

        $ueid      = optional_param ( 'ueid', 0, PARAM_INT );
        $enrolment = $DB->get_record ( 'user_enrolments', array( 'id' => $ueid ), '*' );

            Header::notfoundNull ($enrolment, 'UE não localizado!' );

        ob_clean ();
        DashboardUtil::startPopup ( 'Editar data da inscrição', 'UserEnrolment::matheditSave' );

        $form = new Form();
        $form->createHiddenInput ( 'ueid', $ueid );

        $statusValues = array(
            array( 0, 'Ativo' ),
            array( 1, 'Inativo' )
        );
        $form->createSelectInput ( 'Matrícula esta', 'status', $statusValues, $enrolment->status );

        echo '<div class="area-inscricao-times">';

        $form->createDataInput ( 'A inscrição começa em', 'timestart', userdate ( $enrolment->timestart, '%d/%m/%Y %H:%M' ), 'required' );

        $form->printSpacer ( 10 );
        $form->createCheckboxInput ( 'Ativar termino da inscrição', 'timeend-status', 1, $enrolment->timeend );
        $form->createDataInput ( 'A inscrição termina em', 'timeend', userdate ( $enrolment->timeend ? $enrolment->timeend : time (), '%d/%m/%Y %H:%M' ), 'required' );

        echo '</div>';

        $form->printSpacer ( 10 );
        $form->printRow ( "Inscrição criada em", userdate ( $enrolment->timecreated, '%d de %B de %Y às %H:%M' ) );
        $form->printRow ( "Inscrição modificadao por último em", userdate ( $enrolment->timemodified, '%d de %B de %Y às %H:%M' ) );

        $form->close ();

        ?>
        <script>
            $ ( '#timeend-status' ).click ( timeendStatusClick );
            $ ( '#status' ).change ( statusChange );

            function timeendStatusClick ( delay ) {
                if ( delay != 0 )
                    delay = 400;
                if ( $ ( '#timeend-status' ).is ( ":checked" ) ) {
                    $ ( '.area_timeend' ).show ( delay );
                } else {
                    $ ( '.area_timeend' ).hide ( delay );
                }
            }
            function statusChange ( delay ) {
                if ( delay != 0 )
                    delay = 400;
                if ( $ ( '#status' ).val () == 0 )
                    $ ( '.area-inscricao-times' ).show ( delay );
                else
                    $ ( '.area-inscricao-times' ).hide ( delay );
            }

            timeendStatusClick ( 0 );
            statusChange ( 0 );
        </script>
        <?php
        DashboardUtil::endPopup ();
    }

    public function matheditSave ()
    {
        global $DB;

        $ueid          = optional_param ( 'ueid', 0, PARAM_INT );
        $status        = optional_param ( 'status', 0, PARAM_INT );
        $timestart     = optional_param ( 'timestart', '', PARAM_TEXT );
        $timeendStatus = optional_param ( 'timeend-status', 0, PARAM_INT );
        $timeend       = optional_param ( 'timeend', '', PARAM_TEXT );

        $enrolment = $DB->get_record ( 'user_enrolments', array( 'id' => $ueid ), '*' );

        $enrolment->status    = $status;
        $enrolment->timestart = \DateTime::createFromFormat ( 'd/m/Y H:i', $timestart )->format ( 'U' );
        if ( $timeendStatus )
            $enrolment->timeend = \DateTime::createFromFormat ( 'd/m/Y H:i', $timeend )->format ( 'U' );
        else
            $enrolment->timeend = 0;

        $DB->update_record ( 'user_enrolments', $enrolment );

        Mensagem::agendaMensagemSuccess ( 'Inscrição alterada com sucesso!' );
        Header::reload ();
    }
}