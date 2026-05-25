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
$string['cannotreadcsv'] = 'Não foi possível ler o ficheiro CSV.';
$string['cap_manage'] = 'Gerir importações de utilizadores';
$string['cap_manage_desc'] = 'Carregar ficheiros CSV, pré-visualizar linhas e executar importações de utilizadores dentro do Kopere Dashboard.';
$string['confirm_import'] = 'Importar utilizadores agora';
$string['course_not_found'] = 'Disciplina não encontrada.';
$string['customfields'] = 'Campos personalizados do perfil';
$string['filename'] = 'Ficheiro';
$string['group_not_found'] = 'Grupo não encontrado na disciplina selecionada.';
$string['idnumbercourse'] = 'Número de identificação da disciplina';
$string['invalidcsv'] = 'O ficheiro não parece ser um CSV válido com cabeçalhos.';
$string['invalidemail'] = 'Endereço de email inválido.';
$string['invalidfiletype'] = 'Só são aceites ficheiros .csv ou .txt.';
$string['invalidtoken'] = 'O token de importação é inválido ou expirou.';
$string['invalidusername'] = 'Valor de nome de utilizador inválido.';
$string['manage_intro'] = 'Carregue um ficheiro CSV para criar utilizadores, reutilizar contas existentes, preencher campos personalizados do perfil e, opcionalmente, inscrevê-los em disciplinas e grupos.';
$string['manualinstance_missing'] = 'A disciplina {$a} não tem uma instância de inscrição manual ativada.';
$string['manualplugin_missing'] = 'O módulo de inscrição manual não está disponível neste site Moodle.';
$string['mappingerror_email'] = 'Mapeie a coluna de email.';
$string['mappingerror_firstname'] = 'Mapeie a coluna do nome próprio (ou a coluna do nome completo).';
$string['menu_desc'] = 'Importe utilizadores por CSV, pré-visualize o resultado, crie contas e, opcionalmente, inscreva-os em disciplinas/grupos.';
$string['menu_title'] = 'Importação de utilizadores';
$string['missingemail'] = 'Email em falta.';
$string['missingfirstname'] = 'Nome próprio em falta.';
$string['missingtempfile'] = 'O ficheiro CSV temporário já não existe.';
$string['missingusername'] = 'Nome de utilizador em falta.';
$string['pluginname'] = 'Importação de utilizadores';
$string['preview_intro'] = 'Reveja as primeiras linhas, mapeie cada coluna do CSV e execute uma pré-visualização antes de importar.';
$string['preview_title'] = 'Pré-visualização e mapeamento';
$string['result_alreadyenrolled'] = 'Já inscrito';
$string['result_courseenrolled'] = 'Inscrito em {$a}';
$string['result_groupadded'] = 'Adicionado ao grupo {$a}';
$string['result_usercreated'] = 'Utilizador criado';
$string['result_userexists'] = 'O utilizador já existia';
$string['run_preview'] = 'Executar pré-visualização';
$string['saveuploaderror'] = 'Não foi possível guardar o ficheiro carregado no armazenamento temporário do Moodle.';
$string['select_column'] = 'Selecione uma coluna';
$string['separator'] = 'Separador detetado';
$string['separator_comma'] = 'Vírgula (,)';
$string['separator_semicolon'] = 'Ponto e vírgula (;)';
$string['separator_tab'] = 'Tabulação';
$string['shortnamecourse'] = 'Nome curto/nome completo da disciplina';
$string['start_over'] = 'Iniciar uma nova importação';
$string['status_created'] = 'Criado';
$string['status_error'] = 'Erro';
$string['status_existing'] = 'Utilizador existente';
$string['status_ok'] = 'Pronto';
$string['status_willcreate'] = 'Será criado';
$string['summary_create'] = 'Será criado';
$string['summary_created'] = 'Utilizadores criados';
$string['summary_enrolled'] = 'Novas inscrições';
$string['summary_errors'] = 'Erros';
$string['summary_existing'] = 'Existentes';
$string['summary_total'] = 'Linhas';
$string['summary_withcourse'] = 'Linhas com disciplina';
$string['table_course'] = 'Disciplina';
$string['table_email'] = 'Email';
$string['table_firstname'] = 'Nome próprio';
$string['table_group'] = 'Grupo';
$string['table_lastname'] = 'Apelido';
$string['table_line'] = 'Linha';
$string['table_message'] = 'Mensagem';
$string['table_status'] = 'Estado';
$string['table_username'] = 'Nome de utilizador';
$string['tip_detectseparator'] = 'O módulo deteta automaticamente ficheiros separados por ponto e vírgula, vírgula e tabulação.';
$string['tip_existing'] = 'Os utilizadores existentes não são duplicados. Ainda podem receber dados de campos personalizados e inscrições em disciplinas.';
$string['tip_headers'] = 'A primeira linha do CSV é tratada como a linha de cabeçalho.';
$string['tip_password'] = 'Se um novo utilizador não tiver senha ou tiver uma senha muito curta, será gerada uma senha aleatória e será ativada a alteração obrigatória da senha.';
$string['upload_csv'] = 'Carregar ficheiro CSV';
$string['upload_submit'] = 'Continuar';
$string['uploaderror'] = 'Envie um ficheiro CSV válido.';
