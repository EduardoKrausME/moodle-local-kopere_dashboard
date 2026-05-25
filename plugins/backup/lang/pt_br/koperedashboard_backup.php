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
$string['emptyfiles'] = 'Nenhum arquivo de backup foi gerado ainda.';
$string['exportscope_full'] = 'Banco de dados completo';
$string['exportscope_logs'] = 'Apenas logs';
$string['exportscope_main'] = 'Banco de dados sem logs';
$string['filenotfound'] = 'Arquivo de backup não encontrado: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
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
$string['moodledata_success'] = 'Backup do moodledata gerado com sucesso: {$a}';
$string['moodledata_title'] = 'Backup do moodledata';
$string['notablesfound'] = 'Nenhuma tabela foi encontrada para exportação.';
$string['outputformat_desc'] = 'Você pode exportar para o formato do banco de dados atual ou converter a saída entre MySQL/MariaDB e PostgreSQL.';
$string['outputformat_label'] = 'Formato de saída';
$string['page_title'] = 'Central de backups';
$string['pluginname'] = 'Backups';
$string['processfailed'] = 'Falha ao executar o processo de backup: {$a}';
$string['processstartfailed'] = 'Não foi possível iniciar o processo de backup no servidor.';
$string['separatelogs_desc'] = 'Quando ativado, o sistema gera um pacote ZIP com um arquivo para o banco de dados principal e outro arquivo apenas para as tabelas de log.';
$string['separatelogs_label'] = 'Deseja exportar os logs separadamente?';
$string['storage_desc'] = 'Os arquivos gerados são salvos dentro da área protegida do moodledata.';
$string['storage_title'] = 'Local de armazenamento';
$string['type_database'] = 'Banco de dados';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'O processo retornou um erro, mas não forneceu detalhes.';
$string['unsupporteddbtype'] = 'Tipo de banco de dados não suportado por este plugin de backup: {$a}';
$string['zipcreatefailed'] = 'Não foi possível criar o arquivo ZIP.';
