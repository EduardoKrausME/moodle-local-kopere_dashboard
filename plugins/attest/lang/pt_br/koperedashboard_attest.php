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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actions_title'] = 'Ações';
$string['all_issues_button'] = 'Ver todos os atestados';
$string['all_issues_desc'] = '{$a} atestado(s) encontrado(s).';
$string['all_issues_title'] = 'Todos os atestados';
$string['already_has_valid'] = 'Já existe um válido';
$string['attest:manage'] = 'Gerenciar atestados';
$string['attest:view'] = 'Visualizar atestados';
$string['audit_issue_create'] = 'Atestado gerado.';
$string['audit_tpl_create'] = 'Modelo criado.';
$string['audit_tpl_delete'] = 'Modelo excluído.';
$string['audit_tpl_update'] = 'Modelo atualizado.';
$string['available_desc'] = 'Apenas atestados de cursos com inscrição.';
$string['available_title'] = 'Atestados disponíveis';
$string['back_to_templates'] = 'Voltar aos modelos';
$string['cap_manage'] = 'Gerenciador de atestados';
$string['cap_manage_desc'] = 'Pode criar e gerenciar modelos de atestado.';
$string['cap_view'] = 'Acesso aos atestados';
$string['cap_view_desc'] = 'Pode visualizar e gerar seus próprios atestados.';
$string['choose_course'] = 'Escolha um curso';
$string['choose_course_help'] = 'Primeiro selecione o curso para ver os atestados disponíveis.';
$string['choose_course_placeholder'] = 'Selecione um curso';
$string['close_modal'] = 'Fechar';
$string['course_removed'] = 'Curso removido';
$string['delete_template'] = 'Excluir';
$string['edit_template'] = 'Editar modelo';
$string['empty_all_issues'] = 'Nenhum atestado foi encontrado com os filtros selecionados.';
$string['empty_available'] = 'Nenhum atestado está disponível para suas inscrições.';
$string['empty_available_for_course'] = 'Nenhum atestado está disponível para o curso selecionado.';
$string['empty_available_select_course'] = 'Selecione um curso para listar os atestados disponíveis.';
$string['empty_courses'] = 'Você não está inscrito em nenhum curso no momento.';
$string['empty_issued'] = 'Você ainda não gerou nenhum atestado.';
$string['field_active'] = 'Ativo';
$string['field_allcourses'] = 'Válido para todos os cursos';
$string['field_courses'] = 'Cursos';
$string['field_courses_help'] = 'Selecione os cursos nos quais este modelo poderá ser usado. Se "Válido para todos os cursos" estiver marcado, esta configuração será ignorada.';
$string['field_html'] = 'Modelo HTML';
$string['field_html_help'] = 'Digite o HTML que será usado para gerar o PDF do atestado. Você pode usar os placeholders disponíveis para inserir dados dinâmicos do estudante, curso, data e validade.';
$string['field_name'] = 'Nome';
$string['field_validmonths'] = 'Válido por (meses)';
$string['filter_all_courses'] = 'Todos os cursos';
$string['filter_all_students'] = 'Todos os estudantes';
$string['filter_apply'] = 'Filtrar';
$string['filter_clear'] = 'Limpar';
$string['filter_course'] = 'Curso';
$string['filter_student'] = 'Estudante';
$string['footer_created'] = 'Criado em';
$string['footer_desc'] = 'Documento eletrônico com validação por QR Code ou pelo link abaixo.';
$string['footer_hash'] = 'Assinatura';
$string['footer_title'] = 'Assinatura digital';
$string['footer_validuntil'] = 'Válido até';
$string['generate'] = 'Gerar PDF';
$string['generate_new'] = 'Gerar atestado';
$string['generate_new_button'] = 'Gerar novo atestado';
$string['home_kpi_subtitle'] = 'Documentos emitidos e válidos';
$string['home_kpi_title'] = 'Atestados válidos';
$string['issued_desc'] = 'Veja os atestados já gerados e se eles ainda estão válidos.';
$string['issued_title'] = 'Atestados gerados';
$string['manage_title'] = 'Modelos de atestado';
$string['menu_desc'] = 'Gere atestados em PDF com base em modelos HTML.';
$string['menu_title'] = 'Atestados';
$string['new_template'] = 'Novo modelo';
$string['open_attestation'] = 'Abrir atestado';
$string['open_valid'] = 'Abrir atestado válido';
$string['placeholders_title'] = 'Placeholders disponíveis';
$string['pluginname'] = 'Atestados';
$string['recreate_valid'] = 'Recriar atestado válido';
$string['status_expired'] = 'Expirado';
$string['status_notgenerated'] = 'Ainda não gerado';
$string['status_title'] = 'Status';
$string['status_valid'] = 'Válido';
$string['status_valid_expiring'] = 'Válido e próximo do vencimento';
$string['student_removed'] = 'Estudante removido (#{$a})';
$string['student_title'] = 'Meus atestados';
$string['studentcard'] = 'Carteirinha de estudante';
$string['studentcard_back_placeholder_description'] = 'Texto explicativo traduzido exibido no lado de validação.';
$string['studentcard_back_placeholder_fullname'] = 'Nome completo do estudante.';
$string['studentcard_back_placeholder_hashlabel'] = 'Rótulo traduzido exibido antes do código de validação.';
$string['studentcard_back_placeholder_qrcode'] = 'QR Code de validação como uma URI de dados PNG Base64 incorporada.';
$string['studentcard_back_placeholder_sitefullname'] = 'Nome completo formatado do site Moodle.';
$string['studentcard_back_placeholder_title'] = 'Título traduzido do lado de validação.';
$string['studentcard_back_placeholder_userid'] = 'ID numérico do usuário no Moodle.';
$string['studentcard_back_placeholder_validationcode'] = 'Código público de validação gerado para a carteirinha.';
$string['studentcard_back_placeholder_verifyurl'] = 'URL pública usada para validar a carteirinha.';
$string['studentcard_back_placeholder_wwwroot'] = 'URL base do site Moodle.';
$string['studentcard_front_placeholder_coursefullname'] = 'Nome completo formatado do primeiro curso visível no qual o estudante está inscrito.';
$string['studentcard_front_placeholder_courselabel'] = 'Rótulo traduzido de curso.';
$string['studentcard_front_placeholder_cpf'] = 'CPF obtido do campo personalizado do perfil, usando o número de identificação como alternativa.';
$string['studentcard_front_placeholder_cpflabel'] = 'Rótulo do campo CPF.';
$string['studentcard_front_placeholder_email'] = 'Endereço de e-mail do estudante.';
$string['studentcard_front_placeholder_fullname'] = 'Nome completo do estudante.';
$string['studentcard_front_placeholder_photo'] = 'Foto do perfil como uma URI de imagem Base64 incorporada.';
$string['studentcard_front_placeholder_title'] = 'Título traduzido da carteirinha de estudante.';
$string['studentcard_front_placeholder_userid'] = 'ID numérico do usuário no Moodle.';
$string['studentcard_settings_back'] = 'Modelo do verso';
$string['studentcard_settings_back_template'] = 'Mustache do verso';
$string['studentcard_settings_back_template_help'] = 'Mustache e HTML renderizados pelo TCPDF na página 2 da carteirinha. Mantenha a URL de validação ou o QR Code no layout para que o documento continue verificável.';
$string['studentcard_settings_back_variables'] = 'Variáveis disponíveis no verso';
$string['studentcard_settings_back_variables_desc'] = 'Estas variáveis Mustache são substituídas ao gerar o lado de validação do PDF.';
$string['studentcard_settings_description'] = 'Edite os dois modelos Mustache/HTML usados para gerar o PDF da carteirinha. A frente contém a identidade do estudante e o curso; o verso contém o código, o link público e o QR Code de validação.';
$string['studentcard_settings_front'] = 'Modelo da frente';
$string['studentcard_settings_front_template'] = 'Mustache da frente';
$string['studentcard_settings_front_template_help'] = 'Mustache e HTML renderizados pelo TCPDF na página 1 da carteirinha. A imagem deve ser mantida por meio da variável de foto, fornecida como uma URI de dados incorporada.';
$string['studentcard_settings_front_variables'] = 'Variáveis disponíveis na frente';
$string['studentcard_settings_front_variables_desc'] = 'Estas variáveis Mustache são substituídas ao gerar o lado de identificação do PDF.';
$string['studentcard_settings_menu'] = 'Modelo da carteirinha';
$string['studentcard_settings_title'] = 'Modelo da carteirinha de estudante';
$string['studentcardgenerate'] = 'Gerar PDF';
$string['studentcardnophoto'] = '<h5>Você não tem uma foto de perfil.</h5>Edite seu perfil e adicione uma foto para gerar sua carteirinha de estudante.';
$string['studentcardnovisiblecourses'] = 'A carteirinha de estudante está disponível apenas para usuários inscritos em cursos visíveis.';
$string['studentcardsignaturedesc'] = 'Esta página contém o código de validação e o link público de validação do PDF da carteirinha de estudante.';
$string['studentcardsignaturetitle'] = 'Assinatura digital e validador';
$string['studentcardvalidationinvalid'] = 'Código de validação inválido.';
$string['studentcardvalidationlabel'] = 'Validador';
$string['studentcardvalidationtitle'] = 'Validação da carteirinha de estudante';
$string['studentcardvalidationvalid'] = 'Carteirinha de estudante válida.';
$string['template_removed'] = 'Modelo removido';
$string['title_view'] = 'Atestados';
$string['verify_title'] = 'Verificação do atestado';
