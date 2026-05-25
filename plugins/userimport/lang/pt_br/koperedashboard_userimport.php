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
 * Lang file
 *
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_mapping'] = 'Voltar ao mapeamento';
$string['cannotreadcsv'] = 'Não foi possível ler o arquivo CSV.';
$string['cap_manage'] = 'Gerenciar importações de usuários';
$string['cap_manage_desc'] = 'Enviar arquivos CSV, visualizar linhas e executar importações de usuários dentro do Kopere Dashboard.';
$string['confirm_import'] = 'Importar usuários agora';
$string['course_not_found'] = 'Curso não encontrado.';
$string['customfields'] = 'Campos personalizados do perfil';
$string['filename'] = 'Arquivo';
$string['group_not_found'] = 'Grupo não encontrado no curso selecionado.';
$string['idnumbercourse'] = 'Número de identificação do curso';
$string['invalidcsv'] = 'O arquivo não parece ser um CSV válido com cabeçalhos.';
$string['invalidemail'] = 'Endereço de e-mail inválido.';
$string['invalidfiletype'] = 'Somente arquivos .csv ou .txt são aceitos.';
$string['invalidtoken'] = 'O token de importação é inválido ou expirou.';
$string['invalidusername'] = 'Valor de nome de usuário inválido.';
$string['manage_intro'] = 'Envie um arquivo CSV para criar usuários, reutilizar contas existentes, preencher campos personalizados do perfil e, opcionalmente, matriculá-los em cursos e grupos.';
$string['manualinstance_missing'] = 'O curso {$a} não possui uma instância de inscrição manual habilitada.';
$string['manualplugin_missing'] = 'O plugin de inscrição manual não está disponível neste site Moodle.';
$string['mappingerror_email'] = 'Mapeie a coluna de e-mail.';
$string['mappingerror_firstname'] = 'Mapeie a coluna de nome (ou a coluna de nome completo).';
$string['menu_desc'] = 'Importe usuários por CSV, visualize o resultado, crie contas e, opcionalmente, matricule-os em cursos/grupos.';
$string['menu_title'] = 'Importação de usuários';
$string['missingemail'] = 'E-mail ausente.';
$string['missingfirstname'] = 'Nome ausente.';
$string['missingtempfile'] = 'O arquivo CSV temporário não existe mais.';
$string['missingusername'] = 'Nome de usuário ausente.';
$string['pluginname'] = 'Importação de usuários';
$string['preview_intro'] = 'Revise as primeiras linhas, mapeie cada coluna do CSV e execute uma pré-visualização antes de importar.';
$string['preview_title'] = 'Pré-visualização e mapeamento';
$string['result_alreadyenrolled'] = 'Já matriculado';
$string['result_courseenrolled'] = 'Matriculado em {$a}';
$string['result_groupadded'] = 'Adicionado ao grupo {$a}';
$string['result_usercreated'] = 'Usuário criado';
$string['result_userexists'] = 'O usuário já existia';
$string['run_preview'] = 'Executar pré-visualização';
$string['saveuploaderror'] = 'Não foi possível salvar o arquivo enviado no armazenamento temporário do Moodle.';
$string['select_column'] = 'Selecione uma coluna';
$string['separator'] = 'Separador detectado';
$string['separator_comma'] = 'Vírgula (,)';
$string['separator_semicolon'] = 'Ponto e vírgula (;)';
$string['separator_tab'] = 'Tabulação';
$string['shortnamecourse'] = 'Nome breve/nome completo do curso';
$string['start_over'] = 'Iniciar uma nova importação';
$string['status_created'] = 'Criado';
$string['status_error'] = 'Erro';
$string['status_existing'] = 'Usuário existente';
$string['status_ok'] = 'Pronto';
$string['status_willcreate'] = 'Será criado';
$string['summary_create'] = 'Será criado';
$string['summary_created'] = 'Usuários criados';
$string['summary_enrolled'] = 'Novas matrículas';
$string['summary_errors'] = 'Erros';
$string['summary_existing'] = 'Existentes';
$string['summary_total'] = 'Linhas';
$string['summary_withcourse'] = 'Linhas com curso';
$string['table_course'] = 'Curso';
$string['table_email'] = 'E-mail';
$string['table_firstname'] = 'Nome';
$string['table_group'] = 'Grupo';
$string['table_lastname'] = 'Sobrenome';
$string['table_line'] = 'Linha';
$string['table_message'] = 'Mensagem';
$string['table_status'] = 'Status';
$string['table_username'] = 'Nome de usuário';
$string['tip_detectseparator'] = 'O plugin detecta automaticamente arquivos separados por ponto e vírgula, vírgula e tabulação.';
$string['tip_existing'] = 'Usuários existentes não são duplicados. Eles ainda podem receber dados de campos personalizados e matrículas em cursos.';
$string['tip_headers'] = 'A primeira linha do CSV é tratada como a linha de cabeçalho.';
$string['tip_password'] = 'Se um novo usuário não tiver senha ou tiver uma senha muito curta, uma senha aleatória será gerada e a alteração obrigatória de senha será ativada.';
$string['upload_csv'] = 'Enviar arquivo CSV';
$string['upload_submit'] = 'Continuar';
$string['uploaderror'] = 'Envie um arquivo CSV válido.';
