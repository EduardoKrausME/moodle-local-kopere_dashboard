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

$string['modulename'] = 'Kopere Dashboard';
$string['pluginname'] = 'Kopere Dashboard';
$string['kopere_dashboard:view'] = 'Ver Kopere Dashboard';
$string['kopere_dashboard:manage'] = 'Gerenciar Kopere Dashboard';
$string['dashboard'] = 'Dashboard';
$string['settings'] = 'Configurações';
$string['close'] = 'Fechar';
$string['crontask_tmp'] = 'Cron que limpa a pasta TMP';
$string['crontask_performance'] = 'Cron para salvar os dados de desempenho';
$string['crontask_db_report_login'] = 'Cron para guardar logins de usuários em tabela temporária';

$string['kopere_dashboard_menu'] = 'Mostrar menu na barra to topo';
$string['kopere_dashboard_menu_desc'] = 'Se marcado, o menu do topo contém um link do Kopere Dashboard';
$string['kopere_dashboard_menuwebpages'] = 'Mostrar páginas estáticas para logados';
$string['kopere_dashboard_menuwebpages_desc'] = 'Ative esta opção para exibir as páginas estáticas no menu de navegação para usuários logados.';

$string['add_report_user_fields'] = 'Nos relatórios acrecentar os seguintes campos do usuários';
$string['add_report_user_fields_alt'] = 'Selecione os campos do usuários que você quer mostrar nos relatórios do Kopere.<br>
    Mantenha pressionada a tecla CTRL para selecionar multiplos campos.';

$string['kopere_dashboard_monitor'] = 'Monitor do servidor';
$string['kopere_dashboard_monitor_desc'] = 'Deseja mostrar o monitor do servidor no topo do Kopere?';

$string['kopere_dashboard_pagefonts'] = 'Fontes Extras do Google';
$string['kopere_dashboard_pagefonts_desc'] = 'Adicione aqui o link do @import do Google para fontes extras.<br>Pode colocar vários import.<br><a href="https://fonts.google.com/selection/embed" target="google">Embed code</a><br><img src="{$a}" style="max-width: 100%;width: 420px;">';

$string['integracaoroot'] = 'Integrações';

$string['messageprovider:kopere_dashboard_messages'] = 'Envia Notificações';
$string['kopere_dashboard:emailconfirmsubmission'] = 'Envia Notificações';

$string['open_dashboard'] = 'Abrir Dashboard';

$string['dateformat'] = '%d de %B de %Y às %H:%M';
$string['datetime'] = '%d/%m/%Y %H:%M';
$string['php_datetime'] = 'd/m/Y H:i';

$string['help_title'] = 'Ajuda com esta página';

$string['colors'] = 'Cores';
$string['background'] = 'Cor de Fundo';
$string['color_red'] = 'Vermelho';
$string['color_blue'] = 'Azul';
$string['color_green'] = 'Verde';
$string['color_yellow'] = 'Amarelo';
$string['color_orange'] = 'Laranja';
$string['color_grey'] = 'Cinza';
$string['color_purple'] = 'Roxo';
$string['color_brown'] = 'Marrom';
$string['filemanager_title'] = 'Gerenciador de Arquivos';

// DataTables.
$string['datatables_sEmptyTable'] = 'Nenhum registro encontrado';
$string['datatables_sInfo'] = '_START_ até _END_ de _TOTAL_';
$string['datatables_sInfoEmpty'] = '0 registros';
$string['datatables_sInfoFiltered'] = '(Filtrados de _MAX_ registros)';
$string['datatables_sInfoPostFix'] = '';
$string['datatables_sInfoThousands'] = '.';
$string['datatables_sLengthMenu'] = '_MENU_ <span>por página</span>';
$string['datatables_sLoadingRecords'] = 'Carregando...';
$string['datatables_sProcessing'] = 'Processando...';
$string['datatables_sErrorMessage'] = '<strong>Erro ao carregar os dados</strong><div>Tentando novamente em {$a} segundos</div>';
$string['datatables_sZeroRecords'] = 'Nenhum registro encontrado';
$string['datatables_sSearch'] = 'Pesquisar';
$string['datatables_oPaginate_sNext'] = 'Próximo';
$string['datatables_oPaginate_sPrevious'] = 'Anterior';
$string['datatables_oPaginate_sFirst'] = 'Primeiro';
$string['datatables_oPaginate_sLast'] = 'Último';
$string['datatables_oAria_sSortAscending'] = ': Ordenar colunas de forma ascendente';
$string['datatables_oAria_sSortDescending'] = ': Ordenar colunas de forma descendente';
$string['datatables_buttons_print_text'] = 'Imprimir';
$string['datatables_buttons_copy_text'] = 'Copiar dados';
$string['datatables_buttons_csv_text'] = 'Baixar CSV';
$string['datatables_buttons_copySuccess1'] = 'Copiou uma linha para a área de transferência';
$string['datatables_buttons_copySuccess_'] = '%d linhas copiadas para a área de transferência';
$string['datatables_buttons_copyTitle'] = 'Copiar para a área de transferência';
$string['datatables_buttons_copyKeys'] = 'Pressione <i>ctrl</i> ou <i>\u2318</i> + <i>C</i> para copiar os dados da tabela<br>para a área de transferência do sistema.<br><br>Para cancelar, clique nesta mensagem ou pressione escape.';
$string['datatables_buttons_select_rows_'] = '%d linhas selecionadas';
$string['datatables_buttons_select_rows1'] = '1 linha selecionada';

// Util/navigation.
$string['navigation_page'] = 'Página {$a->atualPage} de {$a->countPages}';

// About.
$string['about_title'] = 'Sobre';
$string['about_project'] = 'Projeto open-source desenvolvido e mantido por';
$string['about_code'] = 'Código disponível em';
$string['about_help'] = 'Ajuda está no';
$string['about_bug'] = 'Achou algum BUG ou gostaria de sugerir melhorias abra uma';

// Backup.
$string['backup_title'] = 'Backup';
$string['backup_windows'] = 'Não disponível em Servidor Windows!';
$string['backup_hours'] = 'Não execute backup em horários de picos!';
$string['backup_sleep'] = 'Backup podem demorar vários minutos para executar.';
$string['backup_newnow'] = 'Criar novo Backup agora';
$string['backup_newsqlnow'] = 'Criar novo Backup do banco de dados agora';
$string['backup_noshell'] = 'Função shell_exec esta desativada!';
$string['backup_list'] = 'Lista de backups';
$string['backup_list_file'] = 'Arquivo';
$string['backup_list_created'] = 'Criado em';
$string['backup_list_size'] = 'Tamanho';
$string['backup_list_action'] = 'Ações';
$string['backup_none'] = 'Nenhum backup localizado!';
$string['backup_execute_success'] = 'Backup criado com sucesso!';
$string['backup_execute_exec'] = 'Execução do Backup';
$string['backup_execute_date'] = 'Data da geração:';
$string['backup_execute_database'] = 'Banco de dados:';
$string['backup_execute_table'] = 'Executando Backup da tabela';
$string['backup_execute_structure'] = 'Estrutura para tabela';
$string['backup_execute_dump'] = 'Fazendo dump de dados da tabela';
$string['backup_execute_dump_error'] = 'Erro ao capturar a tabela';
$string['backup_execute_complete'] = 'Backup concluído!';
$string['backup_returnlist'] = 'Voltar para a lista de Backups';
$string['backup_deletesucessfull'] = 'Backup excluído com sucesso!';
$string['backup_deleting'] = 'Excluíndo Backup';
$string['backup_delete_confirm'] = 'Exclusão do Backup';
$string['backup_delete_title'] = 'Deseja realmente excluir o backup <strong>{$a}</strong>';
$string['backup_notound'] = 'Arquivo não localizado!';

// Report_benchmark.
$string['benchmark_title'] = 'Teste de desempenho';
$string['benchmark_based'] = 'Plug-in baseado em';
$string['benchmark_info'] = '<p>Este teste pode demorar até 1 minutos para executar.</p><p>Tente fazer mais de uma ves o teste para ter uma média.</p><p>E, não execute em horário de picos.</p>';
$string['benchmark_execute'] = 'Executar o teste';
$string['benchmark_executing'] = 'Executando o teste';
$string['benchmark_title2'] = 'Teste da performance da hospedagem';
$string['benchmark_timetotal'] = 'Tempo total:';
$string['benchmark_decription'] = 'Descrição';
$string['benchmark_timesec'] = 'Tempo, em segundos';
$string['benchmark_seconds'] = 'segundos';
$string['benchmark_max'] = 'Valor máximo aceitável';
$string['benchmark_critical'] = 'Limite crítico';
$string['benchmark_testconf'] = 'Teste das configurações do Moodle';
$string['benchmark_testconf_problem'] = 'Problema';
$string['benchmark_testconf_status'] = 'Status';
$string['benchmark_testconf_description'] = 'Descrição';
$string['benchmark_testconf_action'] = 'Ação';

$string['cloadname'] = 'Tempo de carregamento do Moodle';
$string['cloadmoreinfo'] = 'Tempo de carregando o arquivo de configuração "config.php"';
$string['processorname'] = 'Uma função chamada muitas vezes';
$string['processormoreinfo'] = 'Uma função é chamada em um loop para testar a velocidade do processador';
$string['filereadname'] = 'Leitura de arquivos';
$string['filereadmoreinfo'] = 'Testar a velocidade de leitura na pasta temporária da MoodleData';
$string['filewritename'] = 'Criação de arquivos';
$string['filewritemoreinfo'] = 'Testar a velocidade de gravação na pasta temporária da MoodleData';
$string['coursereadname'] = 'Leitura de um curso';
$string['coursereadmoreinfo'] = 'Testar a velocidade de leitura ao ler um curso';
$string['coursewritename'] = 'Criando um curso';
$string['coursewritemoreinfo'] = 'Testar a velocidade do banco de dados para escrever um curso';
$string['querytype1name'] = 'Solicitação complexa (n°1)';
$string['querytype1moreinfo'] = 'Testar a velocidade do banco de dados para executar um pedido complexo';
$string['querytype2name'] = 'Solicitação complexa (n°2)';
$string['querytype2moreinfo'] = 'Testar a velocidade do banco de dados para executar outro pedido mais complexo';
$string['loginguestname'] = 'Tempo para se conectar como convidado';
$string['loginguestmoreinfo'] = 'Medindo o tempo para logar no Moodle com a conta de convidado';
$string['loginusername'] = 'Tempo para se conectar com uma conta de usuário falsa';
$string['loginusermoreinfo'] = 'Medindo o tempo para logar no Moodle com uma conta de usuário falsa';

// Performancemonitor.
$string['cachedef_performancemonitor_cache'] = 'Performance monitor cache';
$string['performancemonitor_cpu'] = 'Uso do CPU';
$string['performancemonitor_memory'] = 'Memória';
$string['performancemonitor_hd'] = 'Moodledata';
$string['performancemonitor_performance'] = 'Desempenho';
$string['performancemonitor_min'] = '{$a} min: ';

// Courses.
$string['courses_title'] = 'Cursos';
$string['courses_title1'] = 'Lista de Cursos';
$string['courses_name'] = 'Nome do Curso';
$string['courses_shortname'] = 'Nome curto';
$string['courses_visible'] = 'Visível';
$string['courses_invisible'] = 'Oculto';
$string['courses_enrol'] = 'Nº alunos inscritos';
$string['courses_invalid'] = 'CourseID inválido!';
$string['courses_notound'] = 'Curso não localizado!';
$string['courses_sumary'] = 'Sumário';
$string['courses_edit'] = 'Editar';
$string['courses_access'] = 'Acessar';
$string['courses_titleenrol'] = 'Alunos matrículados';
$string['courses_enrol_new'] = 'Cadastrar novo Aluno e inscrever';
$string['courses_enrol_new_form'] = 'Usuário não encontrado, Então vamos cadastrar um';
$string['courses_user_create'] = 'Cadastrar novo aluno';
$string['courses_validate_user'] = 'Matrícular aluno no Curso';
$string['courses_student_name'] = 'Nome do aluno';
$string['courses_student_email'] = 'E-mail do aluno';
$string['courses_student_password'] = 'Sugestão de senha para o aluno';
$string['courses_student_ok'] = 'Usuário criado com sucesso:<br><strong>Login:</strong> {$a->login}<br><strong>Senha:</strong> {$a->senha}';
$string['courses_student_cadastrado'] = 'Usuário já está cadastrado no Curso. <a href="{$a}">clique aqui</a> para verificar';
$string['courses_student_cadastrar'] = 'Matrícular aluno neste curso';
$string['courses_student_cadastrar_ok'] = 'Usuário matrículado com sucesso!';
$string['courses_student_status'] = 'Status da matrícula';
$string['courses_page_title'] = 'Páginas já criadas';
$string['courses_page_create'] = 'Criar página com base neste sumário';

// Reports.
$string['cachedef_report_getdata_cache'] = 'report getdata cache';
$string['reports_title'] = 'Relatórios';
$string['reports_download'] = 'Baixar estes dados';
$string['reports_selectcourse'] = 'Selecione o curso para gerar o relatório';
$string['reports_notfound'] = 'Relatório não localizado!';
$string['reports_reportcat_badge'] = 'Relatório de Emblemas';
$string['reports_reportcat_courses'] = 'Relatório de cursos';
$string['reports_reportcat_enrol_cohort'] = 'Relatório de Coortes';
$string['reports_reportcat_enrol_guest'] = 'Relatório de Visitantes';
$string['reports_reportcat_server'] = 'Relatório do sistema';
$string['reports_reportcat_user'] = 'Relatório de usuários';
$string['reports_report_badge-1'] = 'Todos os Emblemas disponíveis no Moodle';
$string['reports_report_badge-2'] = 'Todos os Emblemas conquistado pelos Usuários';
$string['reports_report_courses-1'] = 'Progresso com percentual de conclusão';
$string['reports_report_courses-2'] = 'Cursos que possuem grupos ativados';
$string['reports_report_courses-3'] = 'Relatório de acesso ao curso';
$string['reports_report_courses-4'] = 'Relatório de acesso ao curso com notas';
$string['reports_report_courses-5'] = 'Último acesso ao curso';
$string['reports_report_enrol_cohort-1'] = 'Coortes e os usuários';
$string['reports_report_enrol_guest-1'] = 'Relatório de Logins dos visitantes';
$string['reports_report_server-1'] = 'Relatório de uso do Disco';
$string['reports_report_user-1'] = 'Contagem de alunos em cada curso';
$string['reports_report_user-2'] = 'Conclusão do curso com Critério';
$string['reports_report_user-3'] = 'Relatório diário de acessos dos usuários';
$string['reports_report_user-4'] = 'Relatório de Logins dos alunos';
$string['reports_report_user-5'] = 'Usuários que nunca logaram';
$string['reports_report_user-6'] = 'Usuários que concluíram curso';
$string['reports_report_user-7'] = 'Os usuários registrados, que não fizeram login no Curso';
$string['reports_report_user-8'] = 'Todos os usuários';
$string['reports_timecreated'] = 'Cadastrado em';
$string['reports_coursesize'] = 'Arquivos do Curso';
$string['reports_modulessize'] = 'Arquivos dos Módulos';
$string['reports_lastlogin'] = 'Login em';
$string['reports_cohort'] = 'Nome da Coortes';
$string['reports_groupnode'] = 'Group Mode';
$string['reports_groupname'] = 'Nome do Grupo';
$string['reports_datastudents'] = 'Dados do Aluno';
$string['reports_datacourses'] = 'Dados do Curso';
$string['reports_coursecreated'] = 'Data da inscrição';
$string['reports_activitiescomplete'] = 'Atividades Concluídas';
$string['reports_activitiesassigned'] = 'Atividades Atribuídas';
$string['reports_coursecompleted'] = '% Curso concluído';
$string['reports_badgename'] = 'Emblema';
$string['reports_criteriatype'] = 'Critério';
$string['reports_dateissued'] = 'Em';
$string['reports_context'] = 'Contexto';
$string['reports_export'] = 'Exportar para Excel';
$string['reports_noneaccess'] = 'Nenhum acesso';
$string['reports_access_n'] = 'acessou {$a} vezes';
$string['reports_disabled'] = 'Desativado: -';
$string['reports_add_new'] = 'Novo relatório';

$string['reports_settings_title'] = 'Editar relatório';
$string['reports_settings_form_title'] = 'Editar relatório';
$string['reports_settings_form_enable'] = 'Habilitado?';
$string['reports_settings_form_reportsql'] = 'SQL do relatório';
$string['reports_settings_form_prerequisit'] = 'Pré-requisito antes de carregar o relatório';
$string['reports_settings_form_none'] = 'Nenhum';
$string['reports_settings_form_prerequisit_listCourses'] = 'Lista de cursos';
$string['reports_settings_form_prerequisit_badge_status_text'] = 'Altera o status do Badge em Texto';
$string['reports_settings_form_prerequisit_badge_criteria_type'] = 'Altera o criteria do Badge em Texto';
$string['reports_settings_form_prerequisit_userfullname'] = 'Executa o fullname($user) em cada linha do relatório';
$string['reports_settings_form_prerequisit_courses_group_mode'] = 'Coloca o modo do grupo em texto';
$string['reports_settings_form_foreach'] = 'Alteração de colunas do SQL';
$string['reports_settings_form_colunas'] = 'Colunas';
$string['reports_settings_form_colunas_title'] = 'Título';
$string['reports_settings_form_colunas_chave'] = 'Coluna do SQL';
$string['reports_settings_form_colunas_type'] = 'Tipo de dado';
$string['reports_settings_form_colunas_type_int'] = 'Número';
$string['reports_settings_form_colunas_type_date'] = 'Data';
$string['reports_settings_form_colunas_type_currency'] = 'Moedas';
$string['reports_settings_form_colunas_type_text'] = 'Texto';
$string['reports_settings_form_colunas_type_bytes'] = 'Bytes';
$string['reports_settings_form_colunas_extra'] = 'Estes abaixo deixe em branco caso não precise!';
$string['reports_settings_savesuccess'] = 'Salvo com sucesso!';
$string['reports_settings_form_save'] = 'Salvar relatório';

// Dashboard.
$string['dashboard_title_user'] = 'Usuários / Ativos';
$string['dashboard_title_online'] = 'Online / Última hora';
$string['dashboard_title_course'] = 'Cursos / Visíveis';
$string['dashboard_title_disk'] = 'Uso de Disco';
$string['dashboard_grade_title'] = 'Últimas notas';
$string['dashboard_grade_inmod'] = 'no módulo <strong>{$a->itemname}</strong> do curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_incourse'] = 'no curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_of'] = 'de';
$string['dashboard_grade_text'] = 'Recebeu nota {$a->grade} em {$a->evaluation}';
$string['dashboard_grade_in'] = 'Em';
$string['dashboard_enrol_title'] = 'Últimas Matrículas';
$string['dashboard_enrol_inactive'] = 'a matrícula esta inativa';
$string['dashboard_enrol_active'] = 'a matrícula esta ativa';
$string['dashboard_enrol_text'] = 'Matriculou-se no curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->fullname}</a> e';
$string['dashboard_enrol_lastmodifield'] = 'Última alteração em';

// Notifications.
$string['notification_title'] = 'Notificações';
$string['notification_subtitle'] = '<p>Receba notificações sempre que uma ação acontecer no Moodle.</p>';
$string['notification_new'] = 'Nova notificação';
$string['notification_testsmtp'] = 'Testar se as configurações do SMTP estão corretas.';
$string['notification_testsmtp_message'] = '<p>Este é um teste de envio de E-mail.</p>';
$string['notification_testsmtp_error'] = 'Moodle não envia email quando destinatário e remetente são iguais! <br>E você é o administrador principal deste moodle. Então para testar você deve acessar com outro administrador.';
$string['notification_testsmtp_subject'] = 'Kopere Dashboard - Testando envio de e-mail - ';
$string['notification_table_module'] = 'Módulo';
$string['notification_table_action'] = 'Ação';
$string['notification_table_subject'] = 'Assunto';
$string['notification_table_active'] = 'Ativo';
$string['notification_table_empty'] = 'Nenhuma notificação!';
$string['notification_add_module'] = 'De qual módulo deseja receber notificação?';
$string['notification_add_moduledesc'] = 'Módulos/Atividades não utilizados não aparecem!';
$string['notification_add_selectmodule'] = 'Selecione o Módulo!';
$string['notification_add_action'] = 'De qual ação deseja receber notificações?';
$string['notification_add_create'] = 'Criar noticação';
$string['notification_notound'] = 'Notificação não localizado!';
$string['notification_editing'] = 'Editando Notificação';
$string['notification_from'] = 'De';
$string['notification_fromdesc'] = 'Quem será o remetente da mensagem?';
$string['notification_from_admin'] = 'Administrador do Site';
$string['notification_to'] = 'Para';
$string['notification_todesc'] = 'Quem receberá estas mensagens?';
$string['notification_todesc_admin'] = 'Administrador do Site (Somente o principal)';
$string['notification_todesc_admins'] = 'Administradores do Site (Todos os administradores)';
$string['notification_todesc_teachers'] = 'Professores do curso (Somente se for dentro de um curso)';
$string['notification_todesc_student'] = 'O Aluno (Envia ao próprio aluno que fez a ação)';
$string['notification_status'] = 'Status';
$string['notification_statusdesc'] = 'Se quiser interromper as notificações, marque como "Inativo" e salve!';
$string['notification_status_active'] = 'Ativo';
$string['notification_status_inactive'] = 'Inativo';
$string['notification_subject'] = 'Assunto';
$string['notification_subjectdesc'] = 'Assunto da mensagem';
$string['notification_message_html'] = '<p>Olá {[to.fullname]},</p><p>&nbsp;</p><p>Att,<br>{[from.fullname]}.</p>';
$string['notification_message'] = 'Mensagem';
$string['notification_update'] = 'Atualizar alerta';
$string['notification_create'] = 'Criar alerta';
$string['notification_created'] = 'Notificação criada!';
$string['notification_notfound'] = 'Notificação não localizada!';
$string['notification_delete_success'] = 'Notificação excluída com sucesso!';
$string['notification_delete_yes'] = 'Deseja realmente excluir esta Notificação?';
$string['notification_setting_config'] = 'Configurações do e-mail';
$string['notification_setting_template'] = 'Template';
$string['notification_setting_templatelocation'] = 'Templates estão na pasta';
$string['notification_setting_preview'] = 'Pré visualizar o template';
$string['notification_setting_edit'] = 'Editar HTML do template';
$string['notification_manager'] = 'Gerenciar Mensagens';
$string['notification_core_course_category'] = 'Categoria de Cursos';
$string['notification_core_course'] = 'Cursos';
$string['notification_core_user'] = 'Usuários';
$string['notification_core_user_enrolment'] = 'Matrículas de Usuários';
$string['notification_duplicate'] = 'Está combinação de módulo e evento já possui uma escuta!';
$string['notification_local_kopere_dashboard'] = 'Kopere Dashboard';
$string['notification_local_kopere_hotmoodle'] = 'Kopere HotMoodle';
$string['notification_local_kopere_moocommerce'] = 'Kopere MooCommerce';
$string['notification_local_kopere_pay'] = 'Kopere Pagamento';
$string['notification_error_smtp'] = '<p>Para que os alunos recebam as mensagens, o SMTP precisa estar configurado.</p>
                    <p><a href="https://moodle.eduardokraus.com/configurar-o-smtp-no-moodle"
                          target="_blank">Leia aqui como configurar o SMTP</a></p>
                    <p><a href="{$a->wwwroot}/admin/settings.php?section={$a->mail}"
                          target="_blank">Clique aqui para configurar a saída de e-mail</a></p>';
$string['notification_message_not'] = 'Primeiro, salve a notificação para poder criar a mensagem.';
$string['notification_message_edit'] = 'Edite o conteúdo da mensagem';

// Profile.
$string['profile_invalid'] = 'UserId inválido!';
$string['profile_notfound'] = 'Usuário não localizado!';
$string['profile_title'] = 'Usuários';
$string['profile_notenrol'] = 'Usuário não possui nenhuma matrícula!';
$string['profile_edit'] = 'Editar está inscrição';
$string['profile_enrol_inactive'] = 'Matrícula esta inativa';
$string['profile_enrol_active'] = 'Matrícula esta ativa';
$string['profile_enrol_expires'] = 'Expira em';
$string['profile_enrol_notexpires'] = 'e nunca expira';
$string['profile_enrol_start'] = 'Início em';
$string['profile_enrol_profile'] = 'Perfis';
$string['profile_access_title'] = 'Acessos';
$string['profile_access_first'] = 'Primeiro acesso em:';
$string['profile_access_last'] = 'Último acesso em:';
$string['profile_access_lastlogin'] = 'Último login em:';
$string['profile_userdate_title'] = 'Dados';
$string['profile_link_title'] = 'Links Úteis';
$string['profile_link_profile'] = 'Ver perfil';
$string['profile_link_edit'] = 'Editar perfil';
$string['profile_access'] = 'Acessar como';
$string['profile_courses_title'] = 'Cursos inscritos';

// Settings.
$string['setting_saved'] = 'Configurações salvas!';

// Userenrolment.
$string['userenrolment_notfound'] = 'User Enrolment não localizado!';
$string['userenrolment_edit'] = 'Editar data da inscrição';
$string['userenrolment_detail'] = 'Detalhes da inscrição';
$string['userenrolment_status'] = 'Matrícula esta';
$string['userenrolment_status_active'] = 'Ativo';
$string['userenrolment_status_inactive'] = 'Inativo';
$string['userenrolment_timestart'] = 'A inscrição começa em';
$string['userenrolment_timeendstatus'] = 'Ativar termino da inscrição';
$string['userenrolment_timeend'] = 'A inscrição termina em';
$string['userenrolment_created'] = 'Inscrição criada em';
$string['userenrolment_updated'] = 'Inscrição modificadao por último em';
$string['userenrolment_updatesuccess'] = 'Inscrição alterada com sucesso!';

// User.
$string['user_title'] = 'Usuários';
$string['user_table_fullname'] = 'Nome';
$string['user_table_username'] = 'Username';
$string['user_table_email'] = 'E-mail';
$string['user_table_phone'] = 'Telefone Fixo';
$string['user_table_celphone'] = 'Celular';
$string['user_table_city'] = 'Cidade';

// Useronline.
$string['useronline_title'] = 'Usuários Online';
$string['useronline_subtitle'] = 'Abas abertas com o Moodle';
$string['useronline_table_fullname'] = 'Nome';
$string['useronline_table_date'] = 'Data';
$string['useronline_table_page'] = 'Página';
$string['useronline_table_focus'] = 'Foco';
$string['useronline_table_screen'] = 'Monitor';
$string['useronline_table_navigator'] = 'Navegador';
$string['useronline_table_os'] = 'Sistema Operacional';
$string['useronline_table_device'] = 'Device';
$string['useronline_settings_title'] = 'Configurações do servidor sincronização de Usuários On-line';
$string['useronline_settings_status'] = 'Habilitar Servidor de sincronização de Usuários On-line';
$string['useronline_settings_ssl'] = 'Habilitar SSL?';
$string['useronline_settings_url'] = 'URL do servidor';
$string['useronline_settings_port'] = 'Porta do servidor';

// Acessos dos usuários.
$string['useraccess_title'] = 'Acessos dos usuários';

// UserImport.
$string['userimport_title'] = 'Importar Usuários';
$string['userimport_upload'] = 'Arraste arquivos CSV aqui ou clique para abrir a caixa de busca.';
$string['userimport_moveuploadedfile_error'] = 'ERROR ao mover arquivo!';
$string['userimport_title_proccess'] = 'Processando arquivo "{$a}"';
$string['userimport_separator_error'] = 'Você deve exportar CSV com separador ";" ou ","!';
$string['userimport_first10'] = 'Primeiros 10 registros do seu CSV';
$string['userimport_linkall'] = 'Clique aqui para ver todos os registros do CSV';
$string['userimport_colname'] = 'Coluna {$a}';
$string['userimport_colselect'] = '..::Selecione a coluna::..';
$string['userimport_empty'] = 'Se não selecionar, usará o padrão "{$a}"';
$string['userimport_userdata'] = 'Dados de Usuário';
$string['userimport_userfields'] = 'Campos de perfil extras';
$string['userimport_firstname'] = 'Primeiro nome ou nome completo';
$string['userimport_firstname_desc'] = 'Se no CSV você possuir o nome completo, preencha apenas este campo que o Kopere se encarrega de gerar os dois campos. Se o seu CSV possuir primeiro nome e Sobrenome, selecione este e Sobrenome.';
$string['userimport_courseenrol'] = 'Matrícular em um curso';
$string['userimport_courseenrol_desc'] = 'Se você deseja que o aluno seja matrículado em um curso, selecione a coluna identificadora do curso.';
$string['userimport_date_desc'] = 'O sistema detecta automáticamente os principais formato de data.';
$string['userimport_group_desc'] = 'Se deseja que o aluno seja vinculado a um grupo no curso, a coluna deve ser idêntica ao nome do Grupo ou ID interno.';
$string['userimport_next'] = 'Processar';
$string['userimport_import_user_created_name'] = 'Usuário importado e cadastrado no Moodle';
$string['userimport_import_course_enrol_name'] = 'Usuário importado foi cadastrado no Curso';
$string['userimport_import_user_created_and_enrol_name'] = 'Usuário importado, cadastrado no Moodle e no Curso';
$string['userimport_messages'] = 'Mensagens que os usuários receberão durante a importação';
$string['userimport_receivemessage'] = 'Usuário receberá a mensagem com o título {$a}';
$string['userimport_messageinactive'] = 'Mensagem com o título {$a} esta inativo e não será enviado';
$string['userimport_notreceivemessage'] = 'Usuário não receberá nenhuma mensagem nesta ação!';
$string['userimport_referencedata'] = 'Referenciar os dados do Moodle com o CSV';
$string['userimport_dataok'] = 'Dados OK, Inserir no Moodle';
$string['userimport_datanotok'] = 'Não esta OK, esqueci de algo';
$string['userimport_wait'] = 'Aguarde o processamento dos dados. Após processar será disponibilizado planilha com dados inseridos.';
$string['userimport_noterror'] = 'Nenhum erro encontrado';
$string['userimport_inserted'] = 'Usuário inserido';
$string['userimport_cript'] = '--criptografado--';
$string['userimport_exist'] = 'Usuário já existe. Ignorado';
$string['userimport_passcreate'] = '--Será criado--';
$string['userimport_filenotfound'] = 'Arquivo "{$a}" não foi localizado. Faça upload novamente do arquivo CSV!';

$string['userimport_event_import_course_enrol_subject'] = 'Seja Bem Vindo(a) - {[course.fullname]}';
$string['userimport_event_import_course_enrol_message'] = '<p>Ol&aacute; {[to.fullname]},</p>
<p>Voc&ecirc; foi cadastrado com sucesso no {[course.fullname]}. Agora, voc&ecirc; j&aacute; pode fazer o login na
   &aacute;rea do aluno para come&ccedil;ar estudar quando e onde quiser.</p>
<p>&Eacute; com imensa satisfa&ccedil;&atilde;o que o {[moodle.fullname]} lhe d&aacute; as boas-vindas.</p>
<p>Acesse {[course.link]}, e bons estudos.</p>
<p>D&uacute;vidas estamos a disposi&ccedil;&atilde;o.</p>
<p>Cordialmente, <br>
   Equipe de Suporte</p>';

$string['userimport_event_import_user_created_subject'] = 'Seja Bem Vindo(a) - {[moodle.fullname]}';
$string['userimport_event_import_user_created_message'] = '<p>Ol&aacute; {[to.fullname]},</p>
<p>Uma conta foi criado para voc&ecirc; no site {[moodle.fullname]}.</p>
<p>Agora, convido voc&ecirc; para fazer o login na &aacute;rea do aluno com os seguintes dados:</p>
<p><strong>Site:</strong> {[moodle.link]}<br>
   <strong>Login:</strong> {[to.username]}<br>
   <strong>Senha:</strong> {[to.password]}</p>
<p>D&uacute;vidas estamos a disposi&ccedil;&atilde;o.</p>
<p>Cordialmente,<br>
   Equipe de Suporte</p>';

$string['userimport_event_import_user_created_and_enrol_subject'] = 'Seja Bem Vindo(a) - {[course.fullname]}';
$string['userimport_event_import_user_created_and_enrol_message'] = '<p>Ol&aacute; {[to.fullname]},</p>
<p>Voc&ecirc; foi cadastrado com sucesso no {[course.fullname]}. Agora, voc&ecirc; j&aacute; pode fazer o login na
   &aacute;rea do aluno para come&ccedil;ar estudar quando e onde quiser.</p>
<p>Agora, convido voc&ecirc; para fazer o login na &aacute;rea do aluno com os seguintes dados:</p>
<p><strong>Site:</strong> {[moodle.link]}<br>
   <strong>Login:</strong> {[to.username]}<br>
   <strong>Senha:</strong> {[to.password]}</p>
<p>D&uacute;vidas estamos a disposi&ccedil;&atilde;o.</p>
<p>Cordialmente,<br>
   Equipe de Suporte</p>';

// WebPages.
$string['webpages_title'] = 'Páginas estáticas';
$string['webpages_subtitle'] = 'Menus de navegação';
$string['webpages_subtitle_help'] = 'Estes menus aparecem em Navegação, abaixo de "Meus cursos"';
$string['webpages_table_link'] = 'Link';
$string['webpages_table_menutitle'] = 'Menu';
$string['webpages_table_title'] = 'Título';
$string['webpages_table_visible'] = 'Visível';
$string['webpages_table_image'] = 'Escolha uma image ou arraste-o aqui.';
$string['webpages_error_page'] = 'Página não localizada!';
$string['webpages_error_menu'] = 'Menu não localizado!';
$string['webpages_table_order'] = 'Ordem';
$string['webpages_table_theme'] = 'Layout';
$string['webpages_table_text'] = 'Conteúdo da página';
$string['webpages_table_text_not'] = 'Primeiro, salve o conteúdo para poder criar a página depois.';
$string['webpages_table_text_edit'] = 'Editar o conteúdo da página';
$string['webpages_page_title'] = 'Título';
$string['webpages_page_menu'] = 'Menu';
$string['webpages_page_create'] = 'Criar nova página';
$string['webpages_page_crash'] = 'Se alterar a URL do Moodle e as imagens derem CRASH, clique aqui';
$string['webpages_page_notfound'] = 'Página não localizada!';
$string['webpages_page_nomenudelete'] = '<p>Este menu possui páginas internas e não é possível apagar!</p>';
$string['webpages_page_confirmdeletemenu'] = '<p>Deseja realmente excluir o menu <strong>{$a}</strong>?</p>';
$string['webpages_page_view'] = 'Visualizar a página';
$string['webpages_page_edit'] = 'Editar página';
$string['webpages_page_delete'] = 'Excluir página';
$string['webpages_page_course'] = 'Curso Vinculado';
$string['webpages_page_new'] = 'Nova página';
$string['webpages_page_edit'] = 'Editar a página';
$string['webpages_page_save'] = 'Salvar página';
$string['webpages_page_error'] = 'Todos os dados devem ser preenchidos!';
$string['webpages_page_created'] = 'Página criada!';
$string['webpages_page_updated'] = 'Página atualizada!';
$string['webpages_page_deleted'] = 'Página excluída com sucesso!';
$string['webpages_page_delete'] = 'Excluíndo Página';
$string['webpages_page_delete_confirm'] = 'Deseja realmente excluir a página <strong>{$a->title}</strong>?';
$string['webpages_menu_create'] = 'Criar novo Menu';
$string['webpages_menu_help'] = 'Ajuda com Menus';
$string['webpages_menu_new'] = 'Novo Menu';
$string['webpages_menu_edit'] = 'Editando Menu';
$string['webpages_menu_title'] = 'Título do Menu';
$string['webpages_menu_link'] = 'Link do Menu';
$string['webpages_menu_save'] = 'Salvar';
$string['webpages_menu_error'] = 'Todos os dados devem ser preenchidos!';
$string['webpages_menu_link_duplicate'] = '"Link" esta duplicado!';
$string['webpages_menu_updated'] = 'Menu atualizado!';
$string['webpages_menu_created'] = 'Menu criado!';
$string['webpages_menu_deleted'] = 'Menu excluída com sucesso!';
$string['webpages_menu_subtitle'] = 'Menu estáticas';
$string['webpages_menu_delete'] = 'Excluíndo Menu';
$string['webpages_menu_nodelete'] = 'Não é possível excluir um menu que possui páginas cadastradas!';
$string['webpages_page_settigs'] = 'Configurações das páginas estáticas';
$string['webpages_page_theme'] = 'Layout da página "Todas as páginas"';
$string['webpages_page_analytics'] = 'ID de acompanhamento do Google Analytics';
$string['webpages_page_analyticsdesc'] = 'Sequencia de 13 caracteres, iniciando em UA';
$string['webpages_theme_base'] = 'O layout sem os blocos';
$string['webpages_theme_standard'] = 'Layout padrão com blocos';
$string['webpages_theme_frontpage'] = 'Layout da home page do site.';
$string['webpages_theme_popup'] = 'Sem navegação, sem blocos, sem cabeçalho';
$string['webpages_theme_frametop'] = 'Sem blocos e rodapé mínimo';
$string['webpages_theme_print'] = 'Deve exibir apenas o conteúdo e os cabeçalhos básicos';
$string['webpages_theme_report'] = 'O layout da página usado para relatórios';
$string['webpages_allpages'] = 'Todas as páginas';

$string['privacy:metadata'] = 'O plugin do Kopere Dashboard não armazena nenhum dado pessoal.';
