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

$string['back_to_benchmark'] = 'Späť na benchmark';
$string['cap_run'] = 'Spustiť benchmark';
$string['cap_run_desc'] = 'Môže spúšťať syntetické benchmark testy v Kopere Dashboard.';
$string['cap_view'] = 'Zobraziť benchmark';
$string['cap_view_desc'] = 'Môže pristupovať do oblasti benchmarku a zobrazovať odporúčania k výkonu.';
$string['check_backup_auto_active'] = 'Automatické zálohovanie';
$string['check_cachejs'] = 'JavaScript cache';
$string['check_debug'] = 'Úroveň ladenia';
$string['check_debugdisplay'] = 'Zobrazovať ladiace správy';
$string['check_themedesignermode'] = 'Režim návrhára témy';
$string['debug_value'] = 'Povolené ({$a})';
$string['environment_db'] = 'Databáza';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Podrobnosti OPcache';
$string['environment_opcache_warning'] = 'V produkcii ponechajte OPcache povolený. Ukladá skompilované PHP skripty do pamäte, znižuje využitie CPU a zlepšuje čas odozvy.';
$string['environment_os'] = 'Operačný systém';
$string['environment_os_windows_warning'] = 'Windows sa neodporúča pre produkčné prostredia Moodle. Uprednostnite Linux pre lepšiu kompatibilitu, stabilitu a výkon. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Dokumentácia Moodle: kompletný inštalačný balík pre Windows sa neodporúča pre produkciu</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Prostredie';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'V produkcii ponechajte X-Sendfile povolený. Umožňuje webovému serveru doručovať súbory priamo, čím znižuje využitie pamäte PHP a zlepšuje sťahovanie veľkých súborov.';
$string['execute_title'] = 'Spustiť benchmark';
$string['help_recommendations'] = 'Tieto odporúčania pomáhajú posúdiť, či je prostredie nakonfigurované pre produkciu. Nenahrádzajú podrobnú analýzu databázy, Redis, cronu, diskov ani reverznej cache.';
$string['iterations'] = 'Iterácie';
$string['label_disabled'] = 'Zakázané';
$string['label_enabled'] = 'Povolené';
$string['manage_intro'] = 'Spustite krátku sériu syntetických testov, aby ste získali rýchly prehľad o všeobecnom výkone servera Moodle. Testy merajú jednoduché čítania databázy, diskový cyklus, JSON, hash a spracovanie reťazcov.';
$string['manage_warning'] = 'Výsledky sú porovnávacie. Ideálne ich vždy spúšťajte na tom istom serveri a porovnávajte stav pred/po zmenách v PHP, databáze, disku, cache, Redis, Nginx alebo pluginoch.';
$string['menu_desc'] = 'Meria čas databázy, disku a CPU s rýchlymi produkčnými odporúčaniami.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>pamäť: {$a->memory} MB<br>max. súborov: {$a->maxfiles}<br>overovať časové značky: {$a->timestamps}<br>frekvencia revalidácie: {$a->revalidate}';
$string['peakmemory'] = 'Špičková pamäť';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Vyhnite sa automatickým zálohám počas špičky. Uprednostnite časové okná mimo najväčšieho zaťaženia.';
$string['recommend_cachejs'] = 'V produkcii ponechajte JavaScript cache povolenú, aby sa znížilo spracovanie a prenos.';
$string['recommend_debug'] = 'Aktívne ladenie zvyšuje náklady na spracovanie a šum. V produkcii ho nechajte vypnuté.';
$string['recommend_debugdisplay'] = 'Zobrazovanie ladiacich správ priamo na obrazovke by malo byť v produkcii vypnuté.';
$string['recommend_themedesignermode'] = 'Režim návrhára témy by mal byť v produkcii vypnutý, aby sa predišlo rekompilácii CSS a poklesom výkonu.';
$string['recommendation'] = 'Odporúčanie';
$string['recommendations_title'] = 'Rýchle kontroly konfigurácie';
$string['result_status'] = 'Stav';
$string['results_title'] = 'Výsledky testov';
$string['run_benchmark'] = 'Spustiť benchmark';
$string['status_attention'] = 'Pozor';
$string['status_fast'] = 'Rýchle';
$string['status_slow'] = 'Pomalé';
$string['summary_title'] = 'Súhrn';
$string['test_db_desc'] = 'Opakované čítania malých konfiguračných záznamov z databázy.';
$string['test_db_name'] = 'Databáza';
$string['test_files_desc'] = 'Zápis, čítanie a odstránenie lokálneho dočasného súboru.';
$string['test_files_name'] = 'Súborový systém';
$string['test_hash_desc'] = 'Opakované kolá SHA-256 na meranie hrubého výkonu CPU.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Kódovanie a dekódovanie stredne veľkých štruktúr JSON.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Test';
$string['test_string_desc'] = 'Jednoduché čistenie a analýza obsahu podobného HTML.';
$string['test_string_name'] = 'Reťazce / HTML';
$string['time_elapsed'] = 'Čas';
$string['total_time'] = 'Celkový čas';
$string['value'] = 'Hodnota';
$string['xsendfile_value'] = 'Povolené ({$a->header}<br>aliasy: {$a->aliases})';
