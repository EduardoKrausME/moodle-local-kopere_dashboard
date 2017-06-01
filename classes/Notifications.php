<?php
/**
 * User: Eduardo Kraus
 * Date: 13/05/17
 * Time: 13:27
 */

namespace local_kopere_dashboard;


use core\event\base;
use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\html\TinyMce;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\vo\kopere_dashboard_events;

class Notifications
{
    public function dashboard ()
    {
        global $CFG, $DB;

        DashboardUtil::startPage ( 'Notificações', null, 'Notifications::settings' );

        echo '<div class="element-box">';
        echo '<h3>Notificações</h3>';

        echo '<p>Receba notificações sempre que uma ação acontecer no Moodle.</p>';

        if ( strlen ( get_config ( 'moodle', 'smtphosts' ) ) < 5 ) {
            echo Mensagem::printDanger ( '<p>Este recurso precisa do SMTP configurado.</p>
                    <p><a href="https://moodle.eduardokraus.com/configurar-o-smtp-no-moodle" 
                          target="_blank">Leia aqui como configurar o SMTP</a></p>
                    <p><a href="' . $CFG->wwwroot . '/admin/settings.php?section=messagesettingemail" 
                          target="_blank">Clique aqui para configurar a saída de e-mail</a></p>' );
        } else {
            Botao::add ( 'Nova notificação', '?Notifications::add', '', true, false, true );


            $events     = $DB->get_records ( 'kopere_dashboard_events' );
            $eventsList = array();
            foreach ( $events as $event ) {
                /** @var base $eventClass */
                $eventClass = $event->event;

                $event->module_name = $this->moduleName ( $event->module, false );
                $event->event_name  = $eventClass::get_name ();
                $event->acoes
                                    = "<div class=\"text-center\">
                                    " . Botao::icon ( 'edit', "?Notifications::addSegundaEtapa&id={$event->id}", false ) . "&nbsp;&nbsp;&nbsp;
                                    " . Botao::icon ( 'delete', "?Notifications::delete&id={$event->id}" ) . "
                                </div>";

                $eventsList[] = $event;
            }

            if ( $events ) {
                $table = new DataTable();
                $table->addHeader ( '#', 'id', TableHeaderItem::TYPE_INT );
                $table->addHeader ( 'Módulo', 'module_name' );
                $table->addHeader ( 'Ação', 'event_name' );
                $table->addHeader ( 'De', 'userfrom' );
                $table->addHeader ( 'Para', 'userto' );
                $table->addHeader ( '', 'acoes' );

                //$table->setClickRedirect ( '?Users::details&userid={id}', 'id' );
                $table->printHeader ();
                $table->setRow ( $events );
                $table->close ();
            } else {
                Mensagem::printWarning ( 'Nenhuma notificação!' );
            }
        }

        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function add ()
    {
        if ( !AJAX_SCRIPT ) {
            DashboardUtil::startPage ( array(
                array( '?Notifications::dashboard', 'Notificações' ),
                'Nova Notificações'
            ) );
        } else
            DashboardUtil::startPopup ( 'Nova Notificações' );

        echo '<div class="element-box">';
        echo '<h3>Notificações</h3>';

        $events      = $this->listEvents ();
        $modulesList = array( array( '', '..::Selecione::..' ) );
        foreach ( $events->components as $components ) {
            $moduleName = $this->moduleName ( $components, true );

            if ( $moduleName != null ) {
                $modulesList[] = array( $components, $moduleName );
            }
        }

        $form = new Form( '?Notifications::addSegundaEtapa' );
        $form->createSelectInput ( 'De qual módulo deseja receber notificação?', 'module', $modulesList, '', '',
            Form::createAdditionalText ( 'Recursos não utilizados não aparecem!' ) );
        echo '<div id="restante-form">Selecione o Módulo!</div>';
        $form->close ();

        ?>
        <script>
            $ ( '#module' ).change ( function () {
                var data = {
                    module : $ ( this ).val ()
                };
                $ ( '#restante-form' ).load ( 'open-ajax-table.php?Notifications::addForm', data );
            } );
        </script>
        <?php

        echo '</div>';
        if ( !AJAX_SCRIPT )
            DashboardUtil::endPage ();
        else
            DashboardUtil::endPopup ();
    }

    public function addForm ()
    {
        $module = optional_param ( 'module', '', PARAM_TEXT );

        if ( !isset( $module[ 1 ] ) )
            die( 'Selecione o Módulo!' );

        $events     = $this->listEvents ();
        $eventsList = array( array( '', '..::Selecione::..' ) );
        foreach ( $events->eventinformation as $eventinformation ) {
            if ( $eventinformation[ 'component_full' ] == $module ) {
                $eventsList[] = array( $eventinformation[ 'eventname' ], $eventinformation[ 'fulleventname' ] );
            }
        }

        $form = new Form();
        $form->createSelectInput ( 'De qual ação deseja receber notificações?', 'event', $eventsList );
        $form->createSubmitInput ( 'Criar Mensagem' );
    }

    public function addSegundaEtapa ()
    {
        global $CFG, $DB;

        /** @var base $eventClass */
        $eventClass = optional_param ( 'event', '', PARAM_RAW );
        $module     = optional_param ( 'module', '', PARAM_RAW );
        $id         = optional_param ( 'id', 0, PARAM_INT );


        if ( $id ) {
            /** @var kopere_dashboard_events $evento */
            $evento     = $DB->get_record ( 'kopere_dashboard_events', array( 'id' => $id ) );
            $eventClass = $evento->event;
            $module     = $evento->module;

            DashboardUtil::startPage ( array(
                array( '?Notifications::dashboard', 'Notificações' ),
                'Nova Notificação'
            ) );
            echo '<div class="element-box">';
            echo '<h3>Nova Notificação</h3>';
        } else {
            $evento = kopere_dashboard_events::createNew ();
            DashboardUtil::startPage ( array(
                array( '?Notifications::dashboard', 'Notificações' ),
                'Nova Notificação'
            ) );
            echo '<div class="element-box">';
            echo '<h3>Nova Notificação</h3>';
        }

        $form = new Form( '?Notifications::addSave' );
        $form->createHiddenInput ( 'event', $eventClass );
        $form->createHiddenInput ( 'module', $module );
        $form->printRow ( 'De qual ação deseja receber notificações?', $eventClass::get_name () );

        $valueDe = array(
            array( 'admin', 'Administrador do Site' )
        );
        $form->createSelectInput ( 'De', 'userfrom', $valueDe, $evento->userfrom, '',
            Form::createAdditionalText ( 'Quem será o remetente da mensagem?' ) );

        $valuePara = array(
            array( 'admin', 'Administrador do Site (Somente o principal)' ),
            array( 'admins', 'Administradores do Site (Todos os administradores)' ),
            array( 'teachers', 'Professores do curso (Somente se for dentro de um curso)' ),
            array( 'student', 'O Aluno (Envia ao próprio aluno que fez a ação)' )
        );
        $form->createSelectInput ( 'Para', 'userto', $valuePara, $evento->userto, '',
            Form::createAdditionalText ( 'Quem receberá estas mensagens?' ) );

        $template        = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/" . get_config ( 'local_kopere_dashboard', 'notificacao-template' );
        $templateContent = file_get_contents ( $template );

        if ( strpos ( $module, 'mod_' ) === 0 ) {
            $mailText   = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail-text/mod.html";
            $moduleName = get_string ( 'modulename', $module );
        } else {
            $mailText   = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail-text/{$module}.html";
            $moduleName = '';
        }

        $htmlText = file_get_contents ( $mailText );
        $htmlText = str_replace ( '{[event.name]}', $eventClass::get_name (), $htmlText );
        $htmlText = str_replace ( '{[module.name]}', $moduleName, $htmlText );

        $htmlTextarea    = '<textarea name="message" id="message" style="height:500px">' . htmlspecialchars ( $htmlText ) . '</textarea>';
        $templateContent = str_replace ( '{[message]}', $htmlTextarea, $templateContent );
        $form->printPanel ( 'Mensagem', $templateContent );
        TinyMce::createInputEditor ( '#message' );

        $form->createSubmitInput ( 'Criar alerta' );

        $form->close ();

        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function addSave ()
    {
        global $DB;

        $kopere_dashboard_events = kopere_dashboard_events::createNew ();

        $DB->insert_record ( 'kopere_dashboard_events', $kopere_dashboard_events );
        Mensagem::agendaMensagemSuccess ( 'Notificação criada!' );
        Header::location ( '?Notifications::dashboard' );
    }

    public function delete ()
    {
        global $DB;

        $status = optional_param ( 'status', '', PARAM_TEXT );
        $id     = optional_param ( 'id', 0, PARAM_INT );
        /** @var kopere_dashboard_events $event */
        $event = $DB->get_record ( 'kopere_dashboard_events', array( 'id' => $id ) );
        Header::notfoundNull ( $event, 'Notificação não localizada!' );

        if ( $status == 'sim' ) {
            $DB->delete_records ( 'kopere_dashboard_events', array( 'id' => $id ) );

            Mensagem::agendaMensagemSuccess ( 'Notificação excluída com sucesso!' );
            Header::location ( '?Notifications::dashboard' );
        }

        DashboardUtil::startPage ( array(
            array( '?Notifications::dashboard', 'Notificações' ),
            'Excluíndo Notificação'
        ) );

        echo "<p>Deseja realmente excluir esta Notificação?</p>";
        Botao::delete ( 'Sim', '?Notifications::delete&status=sim&id=' . $event->id, '', false );
        Botao::add ( 'Não', '?Notifications::dashboard', 'margin-left-10', false );

        DashboardUtil::endPage ();
    }

    public function settings ()
    {
        global $CFG;
        ob_clean ();
        DashboardUtil::startPopup ( 'Configurações do e-mail', 'Settings::settingsSave' );

        $form = new Form();

        $values    = array();
        $templates = glob ( "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/*.html" );
        foreach ( $templates as $template ) {
            $basename = pathinfo ( $template, PATHINFO_BASENAME );

            $values[] = array( $basename, $basename );
        }

        $form->createSelectInput ( 'Template', 'notificacao-template', $values,
            get_config ( 'local_kopere_dashboard', 'notificacao-template' ) );

        $form->printPanel ( 'Preview', "<div id=\"area-mensagem-preview\"></div>" );

        $form->printRow ( 'Templates estão na pasta', "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/" );

        $form->close ();

        ?>
        <script>
            $ ( '#notificacao-template' ).change ( notificacaoTemplateChange );

            function notificacaoTemplateChange ( delay ) {

                var data = {
                    template : $ ( '#notificacao-template' ).val ()
                };
                $ ( '#area-mensagem-preview' ).load ( 'open-ajax-table.php?Notifications::settingsLoadTemplate', data );
            }

            notificacaoTemplateChange ( 0 );
        </script>
        <style>
            .table-messagem {
                max-width : 600px;
            }
        </style>
        <?php

        DashboardUtil::endPopup ();
    }

    public function settingsLoadTemplate ()
    {
        global $CFG, $COURSE;

        $template     = optional_param ( 'template', 'blue.html', PARAM_RAW );
        $templateFile = "{$CFG->dirroot}/local/kopere_dashboard/assets/mail/{$template}";

        $content = file_get_contents ( $templateFile );


        $linkManager
            = "<a href=\"{$CFG->wwwroot}/local/kopere_dashboard/open-dashboard.php?Notifications::dashboard\" target=\"_blank\" style=\"border-bottom:1px #777777 dotted; text-decoration:none; color:#777777;\">
                            Gerenciar Mensagens
                        </a>";

        $content = str_replace ( '{[moodle.fullname]}', $COURSE->fullname, $content );
        $content = str_replace ( '{[moodle.shortname]}', $COURSE->shortname, $content );
        $content = str_replace ( '{[message]}', "<h2>Título</h2><p>Linha 1</p><p>Linha 2</p>", $content );
        $content = str_replace ( '{[date.year]}', userdate ( time (), '%Y' ), $content );
        $content = str_replace ( '{[manager]}"', $linkManager, $content );


        echo $content;
    }

    private function moduleName ( $component, $onlyUsed )
    {
        global $DB;

        switch ( $component ) {
            case 'core_course_category':
                return 'Categoria de Cursos';
            case 'core_course':
                return 'Cursos';
            //case 'core_course_completion':
            //    return 'Conclusão de Cursos';
            //case 'core_course_content':
            //    return 'Conteúdo de Cursos';
            //case 'core_course_module':
            //    return 'Módulo de cursos';
            //case 'core_course_section':
            //    return 'Sessões de cursos';

            case 'core_user':
                return 'Usuários';
            case 'core_user_enrolment':
                return 'Matrículas de Usuários';
            //case 'core_user_password':
            //    return 'Senhas';
        }

        if ( strpos ( $component, 'mod_' ) === 0 ) {
            $module = substr ( $component, 4 );

            $sql
                   = "SELECT COUNT(*) as num
                     FROM {course_modules} cm
                     JOIN {modules} m ON cm.module = m.id 
                    WHERE m.name = :name";
            $count = $DB->get_record_sql ( $sql, array( 'name' => $module ) );

            if ( $count->num || !$onlyUsed )
                return get_string ( 'resource' ) . ': ' . get_string ( 'modulename', $module );
        }

        return null;
    }

    private function listEvents ()
    {
        $eventClasss = array_merge (
            \report_eventlist_list_generator::get_core_events_list ( false ),
            \report_eventlist_list_generator::get_non_core_event_list ( false )
        );

        $components       = array();
        $eventinformation = array();

        /** @var base $eventClass */
        foreach ( $eventClasss as $eventClass => $file ) {
            if ( $file == 'base' || $file == 'legacy_logged' || $file === 'manager' || strpos ( $file, 'tool_' ) === 0 )
                continue;

            try {
                $ref = new \ReflectionClass( $eventClass );
                if ( !$ref->isAbstract () ) {

                    $data = $eventClass::get_static_info ();

                    if ( $data[ 'component' ] == 'core' ) {
                        $continue = true;
                        if ( strpos ( $data[ 'target' ], 'course' ) === 0 )
                            $continue = false;
                        elseif ( strpos ( $data[ 'target' ], 'user' ) === 0 )
                            $continue = false;

                        if ( $continue )
                            continue;
                    }

                    if ( $data[ 'component' ] == 'mod_lesson' )
                        continue;

                    $crud = $data[ 'crud' ];
                    if ( $crud == 'c' || $crud == 'u' || $crud == 'd' ) {
                        $tmp                    = $data;
                        $tmp[ 'fulleventname' ] = $eventClass::get_name ();
                        $tmp[ 'crudname' ]      = \report_eventlist_list_generator::get_crud_string ( $data[ 'crud' ] );

                        if ( $data[ 'component' ] == 'core' ) {
                            $components[ $data[ 'component' ] . '_' . $data[ 'target' ] ] = $data[ 'component' ] . '_' . $data[ 'target' ];
                            $tmp[ 'component_full' ]                                      = $data[ 'component' ] . '_' . $data[ 'target' ];
                        } else {
                            $components[ $data[ 'component' ] ] = $data[ 'component' ];
                            $tmp[ 'component_full' ]            = $data[ 'component' ];
                        }

                        $eventinformation[] = $tmp;
                    }
                }
            } catch ( \Exception $e ) {
            }
        }

        $returned                   = new \stdClass ();
        $returned->components       = $components;
        $returned->eventinformation = $eventinformation;

        return $returned;
    }
}