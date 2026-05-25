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

$string['action_download'] = 'Scarica';
$string['action_generate_database'] = 'Esporta database';
$string['action_generate_moodledata'] = 'Genera backup di moodledata';
$string['cannotopenexportfile'] = 'Impossibile aprire il file di esportazione: {$a}';
$string['cap_generate'] = 'Genera backup';
$string['cap_generate_desc'] = 'Consente agli utenti di generare file di backup di moodledata e backup del database.';
$string['cap_view'] = 'Visualizza centro backup';
$string['cap_view_desc'] = 'Consente agli utenti di accedere al centro backup e scaricare i file generati.';
$string['col_actions'] = 'Azioni';
$string['col_created'] = 'Creato il';
$string['col_file'] = 'File';
$string['col_size'] = 'Dimensione';
$string['col_type'] = 'Tipo';
$string['commandnotfound'] = 'Il comando di sistema richiesto non è stato trovato: {$a}.';
$string['current_source_label'] = 'Database corrente:';
$string['database_desc'] = 'Esporta la struttura e i dati del database in PHP, consentendo di scegliere il formato di output e, facoltativamente, separare i log in un file dedicato.';
$string['database_success'] = 'Esportazione del database generata correttamente: {$a}';
$string['database_title'] = 'Esportazione database';
$string['emptyfiles'] = 'Non è stato ancora generato alcun file di backup.';
$string['exportscope_full'] = 'Database completo';
$string['exportscope_logs'] = 'Solo log';
$string['exportscope_main'] = 'Database senza log';
$string['filenotfound'] = 'File di backup non trovato: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'L’estensione PHP zlib/gzip non è disponibile su questo server.';
$string['history_title'] = 'File generati';
$string['home_kpi_empty_subtitle'] = 'Nessun backup generato';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'File più recente: {$a}';
$string['home_kpi_title'] = 'Ultimo backup';
$string['invalidaction'] = 'L’azione richiesta non è valida.';
$string['invalidfilename'] = 'Il nome file specificato non è valido.';
$string['invalidoutputformat'] = 'Il formato di output specificato non è valido: {$a}';
$string['menu_desc'] = 'Generazione manuale del backup di moodledata ed esportazione del database';
$string['menu_title'] = 'Backup';
$string['moodledata_desc'] = 'Genera un pacchetto completo della cartella moodledata, escludendo solo la cartella in cui sono archiviati i backup stessi.';
$string['moodledata_success'] = 'Backup di moodledata generato correttamente: {$a}';
$string['moodledata_title'] = 'Backup di moodledata';
$string['notablesfound'] = 'Non sono state trovate tabelle da esportare.';
$string['outputformat_desc'] = 'È possibile esportare nel formato del database corrente o convertire l’output tra MySQL/MariaDB e PostgreSQL.';
$string['outputformat_label'] = 'Formato di output';
$string['page_title'] = 'Centro backup';
$string['pluginname'] = 'Backup';
$string['processfailed'] = 'Impossibile eseguire il processo di backup: {$a}';
$string['processstartfailed'] = 'Impossibile avviare il processo di backup sul server.';
$string['separatelogs_desc'] = 'Quando abilitato, il sistema genera un pacchetto ZIP con un file per il database principale e un altro file solo per le tabelle di log.';
$string['separatelogs_label'] = 'Vuoi esportare i log separatamente?';
$string['storage_desc'] = 'I file generati vengono salvati nell’area protetta di moodledata.';
$string['storage_title'] = 'Posizione di archiviazione';
$string['type_database'] = 'Database';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Il processo ha restituito un errore, ma non ha fornito dettagli.';
$string['unsupporteddbtype'] = 'Tipo di database non supportato da questo plugin di backup: {$a}';
$string['zipcreatefailed'] = 'Impossibile creare il file ZIP.';
