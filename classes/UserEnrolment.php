<?php
/**
 * User: Eduardo Kraus
 * Date: 15/05/17
 * Time: 14:26
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputCheckbox;
use local_kopere_dashboard\html\inputs\InputDateRange;
use local_kopere_dashboard\html\inputs\InputSelect;
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

        Header::notfoundNull ( $enrolment, 'UE não localizado!' );

        ob_clean ();
        DashboardUtil::startPopup ( 'Editar data da inscrição', 'UserEnrolment::matheditSave' );

        $form = new Form();
        $form->createHiddenInput ( 'ueid', $ueid );

        $statusValues = array(
            array( 'key' => 0, 'value' => 'Ativo' ),
            array( 'key' => 1, 'value' => 'Inativo' )
        );
        $form->addInput (
            InputSelect::newInstance ()->setTitle ( 'Matrícula esta' )
                ->setName ( 'status' )
                ->setValues ( $statusValues )
                ->setValue ( $enrolment->status ) );

        echo '<div class="area-inscricao-times">';

        $form->addInput (
            InputDateRange::newInstance ()
                ->setTitle ( 'A inscrição começa em' )
                ->setName ( 'timestart' )
                ->setValue ( userdate ( $enrolment->timestart, '%d/%m/%Y %H:%M' ) )
                ->setDatetimeRange ()
                ->setRequired () );

        $form->printSpacer ( 10 );

        $form->addInput (
            InputCheckbox::newInstance ()->setTitle ( 'Ativar termino da inscrição' )
                ->setName ( 'timeend-status' )
                ->setChecked ( $enrolment->timeend ) );

        echo '<div class="area_timeend">';

        $form->addInput (
            InputDateRange::newInstance ()
                ->setTitle ( 'A inscrição termina em' )
                ->setName ( 'timeend' )
                ->setValue ( userdate ( $enrolment->timeend ? $enrolment->timeend : time (), '%d/%m/%Y %H:%M' ) )
                ->setDatetimeRange ()
                ->setRequired () );

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

                if ( $ ( '#timeend-status' ).is ( ":checked" ) )
                    $ ( '.area_timeend' ).show ( delay );
                else
                    $ ( '.area_timeend' ).hide ( delay );
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