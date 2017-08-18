<?php
/**
 * User: Eduardo Kraus
 * Date: 15/08/17
 * Time: 09:54
 */

namespace local_kopere_dashboard\install;


use local_kopere_dashboard\vo\kopere_dashboard_events;

class UsersImportInstall {
    public static function installOrUpdate() {

        $event = new kopere_dashboard_events();
        $event->module = 'local_kopere_dashboard';
        $event->event = '\\local_kopere_dashboard\\event\\import_course_enrol';
        $event->userfrom = 'admin';
        $event->userto = 'student';
        $event->subject = 'Seja Bem Vindo(a) - {[course.fullname]}';
        $event->status = 0;
        $event->message = "<p>Ol&aacute; {[to.fullname]},</p>\n" .
            "<p>Voc&ecirc; foi cadastrado com sucesso no {[course.fullname]}. Agora, voc&ecirc; j&aacute; pode fazer o login na " .
            "&aacute;rea do aluno para come&ccedil;ar estudar quando e onde quiser.</p>\n" .
            "<p>&Eacute; com imensa satisfa&ccedil;&atilde;o que o {[moodle.fullname]} lhe d&aacute; as boas-vindas.</p>\n" .
            "<p>Acesse {[course.link]}, e bons estudos.</p>\n" .
            "<p>D&uacute;vidas estamos a disposi&ccedil;&atilde;o.</p>\n" .
            "<p>Cordialmente,<br />\n" .
            "Equipe de Suporte</p>";
        self::insert($event);


        $event = new \local_kopere_dashboard\vo\kopere_dashboard_events();
        $event->module = 'local_kopere_dashboard';
        $event->event = '\\local_kopere_dashboard\\event\\import_user_created';
        $event->userfrom = 'admin';
        $event->userto = 'student';
        $event->subject = 'Seja Bem Vindo(a) - {[moodle.fullname]}';
        $event->status = 0;
        $event->message = "<p>Ol&aacute; {[to.fullname]},</p>\n" .
            "<p>Uma conta foi criado para voc&ecirc; no site {[moodle.fullname]}.</p>\n" .
            "<p>Agora, convido voc&ecirc; para fazer o login na &aacute;rea do aluno com os seguintes dados:</p>\n" .
            "<p><strong>Site:</strong> {[moodle.link]}<br />\n" .
            "<strong>Login:</strong> {[to.username]}<br />\n" .
            "<strong>Senha:</strong> {[to.password]}</p>\n" .
            "<p>D&uacute;vidas estamos a disposi&ccedil;&atilde;o.</p>\n" .
            "<p>Cordialmente,<br />\n" .
            "Equipe de Suporte</p>";
        self::insert($event);


        $event = new \local_kopere_dashboard\vo\kopere_dashboard_events();
        $event->module = 'local_kopere_dashboard';
        $event->event = '\\local_kopere_dashboard\\event\\import_user_created_and_enrol';
        $event->userfrom = 'admin';
        $event->userto = 'student';
        $event->subject = 'Seja Bem Vindo(a) - {[course.fullname]}';
        $event->status = 0;
        $event->message = "<p>Ol&aacute; {[to.fullname]},</p>\n" .
            "<p>Voc&ecirc; foi cadastrado com sucesso no {[course.fullname]}. Agora, voc&ecirc; j&aacute; pode fazer o login na " .
            "&aacute;rea do aluno para come&ccedil;ar estudar quando e onde quiser.</p>\n" .
            "<p>Agora, convido voc&ecirc; para fazer o login na &aacute;rea do aluno com os seguintes dados:</p>\n" .
            "<p><strong>Site:</strong> {[moodle.link]}<br />\n" .
            "<strong>Login:</strong> {[to.username]}<br />\n" .
            "<strong>Senha:</strong> {[to.password]}</p>\n" .
            "<p>D&uacute;vidas estamos a disposi&ccedil;&atilde;o.</p>\n" .
            "<p>Cordialmente,<br />\n" .
            "Equipe de Suporte</p>";
        self::insert($event);
    }

    public static function insert($event) {
        global $DB;

        $evento = $DB->get_record('kopere_dashboard_events',
            array(
                'module' => $event->module,
                'event' => $event->event
            ));
        if (!$evento)
            $DB->insert_record('kopere_dashboard_events', $event);
    }
}