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
 * @package  local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['coursereadname'] = 'Leitura de uma disciplina';
$string['coursereadmoreinfo'] = 'Testar a velocidade de leitura ao ler uma disciplina';
$string['coursewritename'] = 'Criando uma disciplina';
$string['coursewritemoreinfo'] = 'Testar a velocidade do banco de dados para escrever uma disciplina';

// Courses.
$string['courses_title'] = 'Disciplinas';
$string['courses_title1'] = 'Lista de Disciplinas';
$string['courses_name'] = 'Nome da Disciplina';
$string['courses_notound'] = 'Disciplina não localizada!';
$string['courses_validate_user'] = 'Matrícular aluno na Disciplina';
$string['courses_student_cadastrado'] = 'Usuário já está cadastrado na Disciplina. <a href="{$a}">clique aqui</a> para verificar';
$string['courses_student_cadastrar'] = 'Matrícular aluno nesta disciplina';

// Reports.
$string['reports_selectcourse'] = 'Selecione a disciplina para gerar o relatório';
$string['reports_reportcat_courses'] = 'Relatório de disciplinas';
$string['reports_report_courses-2'] = 'Disciplinas que possuem grupos ativados';
$string['reports_report_courses-3'] = 'Relatório de acesso à disciplina';
$string['reports_report_courses-4'] = 'Relatório de acesso ao disciplina com notas';
$string['reports_report_courses-5'] = 'Último acesso à disciplina';
$string['reports_report_user-1'] = 'Contagem de alunos em cada disciplina';
$string['reports_report_user-2'] = 'Conclusão da disciplina com Critério';
$string['reports_report_user-6'] = 'Usuários que concluíram a disciplina';
$string['reports_report_user-7'] = 'Os usuários registrados, que não fizeram login na Disciplina';
$string['reports_coursesize'] = 'Arquivos da Disciplina';
$string['reports_datacourses'] = 'Dados da Disciplina';
$string['reports_coursecompleted'] = '% Disciplina concluído';

$string['reports_settings_form_prerequisit_listCourses'] = 'Lista de disciplinas';

// Dashboard.
$string['dashboard_title_course'] = 'Disciplinas / Visíveis';
$string['dashboard_grade_inmod'] = 'no módulo <strong>{$a->itemname}</strong> da disciplina <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_incourse'] = 'na disciplina <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_enrol_text'] = 'Matriculou-se na disciplina <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->fullname}</a> e';

// Notifications.
$string['notification_todesc_teachers'] = 'Professores da disciplina (Somente se for dentro de uma disciplina)';
$string['notification_core_course_category'] = 'Curso de Disciplinas';
$string['notification_core_course'] = 'Disciplinas';

// Profile.
$string['profile_courses_title'] = 'Disciplinas inscritos';

// UserImport.
$string['userimport_courseenrol'] = 'Matrícular em uma disciplina';
$string['userimport_courseenrol_desc'] = 'Se você deseja que o aluno seja matrículado em uma disciplina, selecione a coluna identificadora da disciplina.';
$string['userimport_group_desc'] = 'Se deseja que o aluno seja vinculado a um grupo na disciplina, a coluna deve ser idêntica ao nome do Grupo ou ID interno.';
$string['userimport_import_course_enrol_name'] = 'Usuário importado foi cadastrado na Disciplina';
$string['userimport_import_user_created_and_enrol_name'] = 'Usuário importado, cadastrado no Moodle e na Disciplina';

// WebPages.
$string['webpages_subtitle_help'] = 'Estes menus aparecem em Navegação, abaixo de "Minhas disciplinas"';
$string['webpages_page_course'] = 'Disciplina Vinculada';
