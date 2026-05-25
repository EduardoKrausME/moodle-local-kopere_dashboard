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

$string['action_download'] = 'Transferir';
$string['action_generate_database'] = 'Exportar base de dados';
$string['action_generate_moodledata'] = 'Gerar cópia de segurança do moodledata';
$string['cannotopenexportfile'] = 'Não foi possível abrir o ficheiro de exportação: {$a}';
$string['cap_generate'] = 'Gerar cópias de segurança';
$string['cap_generate_desc'] = 'Permite que os utilizadores gerem ficheiros de cópia de segurança do moodledata e cópias de segurança da base de dados.';
$string['cap_view'] = 'Ver centro de cópias de segurança';
$string['cap_view_desc'] = 'Permite que os utilizadores acedam ao centro de cópias de segurança e transfiram os ficheiros gerados.';
$string['col_actions'] = 'Ações';
$string['col_created'] = 'Criado em';
$string['col_file'] = 'Ficheiro';
$string['col_size'] = 'Tamanho';
$string['col_type'] = 'Tipo';
$string['commandnotfound'] = 'O comando de sistema necessário não foi encontrado: {$a}.';
$string['current_source_label'] = 'Base de dados atual:';
$string['database_desc'] = 'Exporta a estrutura e os dados da base de dados em PHP, permitindo escolher o formato de saída e, opcionalmente, separar os registos num ficheiro próprio.';
$string['database_success'] = 'Exportação da base de dados gerada com sucesso: {$a}';
$string['database_title'] = 'Exportação da base de dados';
$string['emptyfiles'] = 'Ainda não foram gerados ficheiros de cópia de segurança.';
$string['exportscope_full'] = 'Base de dados completa';
$string['exportscope_logs'] = 'Apenas registos';
$string['exportscope_main'] = 'Base de dados sem registos';
$string['filenotfound'] = 'Ficheiro de cópia de segurança não encontrado: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'A extensão PHP zlib/gzip não está disponível neste servidor.';
$string['history_title'] = 'Ficheiros gerados';
$string['home_kpi_empty_subtitle'] = 'Nenhuma cópia de segurança gerada';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Ficheiro mais recente: {$a}';
$string['home_kpi_title'] = 'Última cópia de segurança';
$string['invalidaction'] = 'A ação solicitada é inválida.';
$string['invalidfilename'] = 'O nome de ficheiro especificado é inválido.';
$string['invalidoutputformat'] = 'O formato de saída especificado é inválido: {$a}';
$string['menu_desc'] = 'Geração manual de cópia de segurança do moodledata e exportação da base de dados';
$string['menu_title'] = 'Cópias de segurança';
$string['moodledata_desc'] = 'Gera um pacote completo da pasta moodledata, excluindo apenas a pasta onde as próprias cópias de segurança são armazenadas.';
$string['moodledata_success'] = 'Cópia de segurança do moodledata gerada com sucesso: {$a}';
$string['moodledata_title'] = 'Cópia de segurança do moodledata';
$string['notablesfound'] = 'Não foram encontradas tabelas para exportação.';
$string['outputformat_desc'] = 'Pode exportar para o formato da base de dados atual ou converter a saída entre MySQL/MariaDB e PostgreSQL.';
$string['outputformat_label'] = 'Formato de saída';
$string['page_title'] = 'Centro de cópias de segurança';
$string['pluginname'] = 'Cópias de segurança';
$string['processfailed'] = 'Falha ao executar o processo de cópia de segurança: {$a}';
$string['processstartfailed'] = 'Não foi possível iniciar o processo de cópia de segurança no servidor.';
$string['separatelogs_desc'] = 'Quando ativado, o sistema gera um pacote ZIP com um ficheiro para a base de dados principal e outro ficheiro apenas para as tabelas de registo.';
$string['separatelogs_label'] = 'Pretende exportar os registos separadamente?';
$string['storage_desc'] = 'Os ficheiros gerados são guardados dentro da área protegida do moodledata.';
$string['storage_title'] = 'Localização de armazenamento';
$string['type_database'] = 'Base de dados';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'O processo devolveu um erro, mas não forneceu detalhes.';
$string['unsupporteddbtype'] = 'Tipo de base de dados não suportado por este plugin de cópia de segurança: {$a}';
$string['zipcreatefailed'] = 'Não foi possível criar o ficheiro ZIP.';
