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
 * @package   koperedashboard_benchmark
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_to_benchmark'] = 'Torna al benchmark';
$string['cap_run'] = 'Esegui benchmark';
$string['cap_run_desc'] = 'Può eseguire test benchmark sintetici in Kopere Dashboard.';
$string['cap_view'] = 'Visualizza benchmark';
$string['cap_view_desc'] = 'Può accedere all\'area benchmark e visualizzare le raccomandazioni sulle prestazioni.';
$string['check_backup_auto_active'] = 'Backup automatico';
$string['check_cachejs'] = 'Cache JavaScript';
$string['check_debug'] = 'Livello di debug';
$string['check_debugdisplay'] = 'Mostra messaggi di debug';
$string['check_themedesignermode'] = 'Modalità designer del tema';
$string['debug_value'] = 'Abilitato ({$a})';
$string['environment_db'] = 'Database';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Dettagli OPcache';
$string['environment_opcache_warning'] = 'Mantieni OPcache abilitato in produzione. Archivia in memoria gli script PHP compilati, riduce l\'uso della CPU e migliora il tempo di risposta.';
$string['environment_os'] = 'Sistema operativo';
$string['environment_os_windows_warning'] = 'Windows non è consigliato per ambienti Moodle in produzione. Preferisci Linux per maggiore compatibilità, stabilità e prestazioni. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Documentazione Moodle: il pacchetto completo di installazione per Windows non è consigliato per la produzione</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Ambiente';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Mantieni X-Sendfile abilitato in produzione. Consente al server web di distribuire direttamente i file, riducendo l\'uso di memoria PHP e migliorando i download di file grandi.';
$string['execute_title'] = 'Esegui benchmark';
$string['help_recommendations'] = 'Queste raccomandazioni aiutano a interpretare se l\'ambiente è configurato per la produzione. Non sostituiscono un\'analisi dettagliata di database, Redis, cron, dischi o cache inversa.';
$string['iterations'] = 'Iterazioni';
$string['label_disabled'] = 'Disabilitato';
$string['label_enabled'] = 'Abilitato';
$string['manage_intro'] = 'Esegui una breve serie di test sintetici per ottenere una rapida panoramica delle prestazioni generali del server Moodle. I test misurano semplici letture del database, andata e ritorno su disco, JSON, hash ed elaborazione delle stringhe.';
$string['manage_warning'] = 'I risultati sono comparativi. Idealmente, eseguili sempre sullo stesso server e confronta prima/dopo le modifiche a PHP, database, disco, cache, Redis, Nginx o plugin.';
$string['menu_desc'] = 'Misura il tempo di database, disco e CPU con rapide raccomandazioni per la produzione.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>memoria: {$a->memory} MB<br>file max: {$a->maxfiles}<br>valida timestamp: {$a->timestamps}<br>freq. rivalidazione: {$a->revalidate}';
$string['peakmemory'] = 'Picco di memoria';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Evita backup automatici durante le ore di punta. Preferisci finestre fuori dagli orari di maggiore utilizzo.';
$string['recommend_cachejs'] = 'In produzione, mantieni abilitata la cache JavaScript per ridurre elaborazione e trasferimento.';
$string['recommend_debug'] = 'Il debug attivo aumenta il costo di elaborazione e il rumore. Mantienilo disabilitato in produzione.';
$string['recommend_debugdisplay'] = 'La visualizzazione dei messaggi di debug direttamente sullo schermo deve essere disabilitata in produzione.';
$string['recommend_themedesignermode'] = 'La modalità designer del tema deve essere disabilitata in produzione per evitare la ricompilazione CSS e cali di prestazioni.';
$string['recommendation'] = 'Raccomandazione';
$string['recommendations_title'] = 'Controlli rapidi di configurazione';
$string['result_status'] = 'Stato';
$string['results_title'] = 'Risultati dei test';
$string['run_benchmark'] = 'Esegui benchmark';
$string['status_attention'] = 'Attenzione';
$string['status_fast'] = 'Veloce';
$string['status_slow'] = 'Lento';
$string['summary_title'] = 'Riepilogo';
$string['test_db_desc'] = 'Letture ripetute di piccoli record di configurazione dal database.';
$string['test_db_name'] = 'Database';
$string['test_files_desc'] = 'Scrittura, lettura e rimozione di un file temporaneo locale.';
$string['test_files_name'] = 'File system';
$string['test_hash_desc'] = 'Cicli ripetuti SHA-256 per misurare le prestazioni grezze della CPU.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Codifica e decodifica di strutture JSON di dimensione media.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Test';
$string['test_string_desc'] = 'Pulizia e analisi semplice di contenuti simili a HTML.';
$string['test_string_name'] = 'Stringhe / HTML';
$string['time_elapsed'] = 'Tempo';
$string['total_time'] = 'Tempo totale';
$string['value'] = 'Valore';
$string['xsendfile_value'] = 'Abilitato ({$a->header}<br>alias: {$a->aliases})';
