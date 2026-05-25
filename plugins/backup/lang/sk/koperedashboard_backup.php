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

$string['action_download'] = 'Stiahnuť';
$string['action_generate_database'] = 'Exportovať databázu';
$string['action_generate_moodledata'] = 'Vygenerovať zálohu moodledata';
$string['cannotopenexportfile'] = 'Nepodarilo sa otvoriť exportný súbor: {$a}';
$string['cap_generate'] = 'Generovať zálohy';
$string['cap_generate_desc'] = 'Umožňuje používateľom generovať záložné súbory moodledata a zálohy databázy.';
$string['cap_view'] = 'Zobraziť centrum zálohovania';
$string['cap_view_desc'] = 'Umožňuje používateľom pristupovať do centra zálohovania a sťahovať vygenerované súbory.';
$string['col_actions'] = 'Akcie';
$string['col_created'] = 'Vytvorené';
$string['col_file'] = 'Súbor';
$string['col_size'] = 'Veľkosť';
$string['col_type'] = 'Typ';
$string['commandnotfound'] = 'Požadovaný systémový príkaz nebol nájdený: {$a}.';
$string['current_source_label'] = 'Aktuálna databáza:';
$string['database_desc'] = 'Exportuje štruktúru a údaje databázy v PHP, umožňuje vybrať výstupný formát a voliteľne oddeliť logy do samostatného súboru.';
$string['database_success'] = 'Export databázy bol úspešne vygenerovaný: {$a}';
$string['database_title'] = 'Export databázy';
$string['emptyfiles'] = 'Zatiaľ neboli vygenerované žiadne záložné súbory.';
$string['exportscope_full'] = 'Celá databáza';
$string['exportscope_logs'] = 'Iba logy';
$string['exportscope_main'] = 'Databáza bez logov';
$string['filenotfound'] = 'Záložný súbor sa nenašiel: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'Rozšírenie PHP zlib/gzip nie je na tomto serveri dostupné.';
$string['history_title'] = 'Vygenerované súbory';
$string['home_kpi_empty_subtitle'] = 'Nebola vygenerovaná žiadna záloha';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Najnovší súbor: {$a}';
$string['home_kpi_title'] = 'Najnovšia záloha';
$string['invalidaction'] = 'Požadovaná akcia je neplatná.';
$string['invalidfilename'] = 'Zadaný názov súboru je neplatný.';
$string['invalidoutputformat'] = 'Zadaný výstupný formát je neplatný: {$a}';
$string['menu_desc'] = 'Ručné generovanie zálohy moodledata a export databázy';
$string['menu_title'] = 'Zálohy';
$string['moodledata_desc'] = 'Vygeneruje kompletný balík priečinka moodledata, pričom vylúči iba priečinok, v ktorom sú uložené samotné zálohy.';
$string['moodledata_success'] = 'Záloha moodledata bola úspešne vygenerovaná: {$a}';
$string['moodledata_title'] = 'Záloha moodledata';
$string['notablesfound'] = 'Nenašli sa žiadne tabuľky na export.';
$string['outputformat_desc'] = 'Môžete exportovať do aktuálneho formátu databázy alebo konvertovať výstup medzi MySQL/MariaDB a PostgreSQL.';
$string['outputformat_label'] = 'Výstupný formát';
$string['page_title'] = 'Centrum zálohovania';
$string['pluginname'] = 'Zálohy';
$string['processfailed'] = 'Nepodarilo sa spustiť proces zálohovania: {$a}';
$string['processstartfailed'] = 'Nepodarilo sa spustiť proces zálohovania na serveri.';
$string['separatelogs_desc'] = 'Ak je povolené, systém vygeneruje ZIP balík s jedným súborom pre hlavnú databázu a ďalším súborom iba pre tabuľky logov.';
$string['separatelogs_label'] = 'Chcete exportovať logy samostatne?';
$string['storage_desc'] = 'Vygenerované súbory sa ukladajú do chránenej oblasti moodledata.';
$string['storage_title'] = 'Umiestnenie úložiska';
$string['type_database'] = 'Databáza';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Proces vrátil chybu, ale neposkytol podrobnosti.';
$string['unsupporteddbtype'] = 'Typ databázy nie je podporovaný týmto zálohovacím pluginom: {$a}';
$string['zipcreatefailed'] = 'Nepodarilo sa vytvoriť ZIP súbor.';
