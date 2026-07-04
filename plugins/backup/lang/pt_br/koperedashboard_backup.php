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
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['action_delete'] = 'Excluir';
$string['action_download'] = 'Baixar';
$string['action_generate_database'] = 'Exportar banco de dados';
$string['action_generate_moodledata'] = 'Gerar backup do moodledata';
$string['cannotopenexportfile'] = 'Não foi possível abrir o arquivo de exportação: {$a}';
$string['cap_generate'] = 'Gerar backups';
$string['cap_generate_desc'] = 'Permite que os usuários gerem arquivos de backup do moodledata e backups do banco de dados.';
$string['cap_view'] = 'Visualizar central de backups';
$string['cap_view_desc'] = 'Permite que os usuários acessem a central de backups e baixem os arquivos gerados.';
$string['col_actions'] = 'Ações';
$string['col_created'] = 'Criado em';
$string['col_file'] = 'Arquivo';
$string['col_size'] = 'Tamanho';
$string['col_type'] = 'Tipo';
$string['commandnotfound'] = 'O comando de sistema necessário não foi encontrado: {$a}.';
$string['current_source_label'] = 'Banco de dados atual:';
$string['database_desc'] = 'Exporta a estrutura e os dados do banco de dados em PHP, permitindo escolher o formato de saída e, opcionalmente, separar os logs em um arquivo próprio.';
$string['database_success'] = 'Exportação do banco de dados gerada com sucesso: {$a}';
$string['database_title'] = 'Exportação do banco de dados';
$string['delete_cancel'] = 'Cancelar';
$string['delete_confirm_button'] = 'Sim, excluir arquivo';
$string['delete_confirm_message'] = 'Tem certeza que deseja excluir este arquivo de backup gerado? Esta ação não poderá ser desfeita.';
$string['delete_confirm_title'] = 'Excluir arquivo de backup';
$string['delete_success'] = 'Arquivo de backup excluído com sucesso: {$a}';
$string['deletefailed'] = 'Não foi possível excluir o arquivo de backup: {$a}';
$string['emptyfiles'] = 'Nenhum arquivo de backup foi gerado ainda.';
$string['exportscope_full'] = 'Banco de dados completo';
$string['exportscope_logs'] = 'Apenas logs';
$string['exportscope_main'] = 'Banco de dados sem logs';
$string['filenotfound'] = 'Arquivo de backup não encontrado: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['friendly_installation_alternative_not_desc'] = 'Instale e configure o <a href="https://moodle.org/plugins/local_alternative_file_system" target="_blank">Alternative File System</a> para otimizar o tempo de migração, manter os arquivos do Moodle em armazenamento remoto, reduzir a pressão no disco local, facilitar ambientes em cluster, melhorar a resiliência e permitir entrega por CDN quando aplicável. Sem ele, esta exportação incluirá os arquivos locais do moodledata no ZIP gerado.';
$string['friendly_installation_alternative_not_title'] = 'Alternative File System não instalado ou não configurado';
$string['friendly_installation_alternative_ok_desc'] = 'Verifique no <a href="{$a}" target="_blank">Sistema de arquivos alternativo</a> se todos os arquivos estão no storage remoto antes de realizar a restauração.';
$string['friendly_installation_alternative_ok_title'] = 'Alternative File System instalado';
$string['friendly_installation_desc'] = 'Exporta o banco de dados em formato compatível com o <a href="https://github.com/EduardoKrausME/moodle_friendly_installation" target="_blank">Instalador do software Moodle™</a> e MoodleData.';
$string['friendly_installation_generate'] = 'Exportar';
$string['friendly_installation_success'] = 'Exportação gerada com sucesso!';
$string['friendly_installation_title'] = 'Exportação para o Instalador do software Moodle™';
$string['gzipnotavailable'] = 'A extensão PHP zlib/gzip não está disponível neste servidor.';
$string['history_title'] = 'Arquivos gerados';
$string['home_kpi_empty_subtitle'] = 'Nenhum backup gerado';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Arquivo mais recente: {$a}';
$string['home_kpi_title'] = 'Último backup';
$string['invalidaction'] = 'A ação solicitada é inválida.';
$string['invalidfilename'] = 'O nome de arquivo especificado é inválido.';
$string['invalidoutputformat'] = 'O formato de saída especificado é inválido: {$a}';
$string['menu_desc'] = 'Geração manual de backup do moodledata e exportação do banco de dados';
$string['menu_title'] = 'Backups';
$string['moodledata_desc'] = 'Gera um pacote completo da pasta moodledata, excluindo apenas a pasta onde os próprios backups são armazenados.';
$string['moodledata_success'] = 'Backup da moodledata gerado com sucesso: {$a}';
$string['moodledata_title'] = 'Backup da moodledata';
$string['notablesfound'] = 'Nenhuma tabela foi encontrada para exportação.';
$string['outputformat_desc'] = 'Você pode exportar para o formato do banco de dados atual ou converter a saída entre MySQL/MariaDB e PostgreSQL.';
$string['outputformat_label'] = 'Formato de saída';
$string['page_title'] = 'Central de backups';
$string['pluginname'] = 'Backups';
$string['processfailed'] = 'Falha ao executar o processo de backup: {$a}';
$string['processstartfailed'] = 'Não foi possível iniciar o processo de backup no servidor.';
$string['separatelogs_desc'] = 'Quando ativado, o sistema gera um pacote ZIP com um arquivo para o banco de dados principal e outro arquivo apenas para as tabelas de log.';
$string['separatelogs_label'] = 'Deseja exportar os logs separadamente?';
$string['type_database'] = 'Banco de dados';
$string['type_friendly_installation'] = 'Instalador do software Moodle™';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'O processo retornou um erro, mas não forneceu detalhes.';
$string['unsupporteddbtype'] = 'Tipo de banco de dados não suportado por este plugin de backup: {$a}';
$string['zipcreatefailed'] = 'Não foi possível criar o arquivo ZIP.';
