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

$string['action_download'] = 'Herunterladen';
$string['action_generate_database'] = 'Datenbank exportieren';
$string['action_generate_moodledata'] = 'Moodledata-Sicherung erstellen';
$string['cannotopenexportfile'] = 'Die Exportdatei konnte nicht geöffnet werden: {$a}';
$string['cap_generate'] = 'Sicherungen erstellen';
$string['cap_generate_desc'] = 'Erlaubt Nutzern, moodledata-Sicherungsdateien und Datenbanksicherungen zu erstellen.';
$string['cap_view'] = 'Sicherungszentrale anzeigen';
$string['cap_view_desc'] = 'Erlaubt Nutzern, auf die Sicherungszentrale zuzugreifen und erstellte Dateien herunterzuladen.';
$string['col_actions'] = 'Aktionen';
$string['col_created'] = 'Erstellt am';
$string['col_file'] = 'Datei';
$string['col_size'] = 'Größe';
$string['col_type'] = 'Typ';
$string['commandnotfound'] = 'Der erforderliche Systembefehl wurde nicht gefunden: {$a}.';
$string['current_source_label'] = 'Aktuelle Datenbank:';
$string['database_desc'] = 'Exportiert die Datenbankstruktur und -daten in PHP, sodass das Ausgabeformat gewählt und Protokolle optional in eine eigene Datei getrennt werden können.';
$string['database_success'] = 'Datenbankexport erfolgreich erstellt: {$a}';
$string['database_title'] = 'Datenbankexport';
$string['emptyfiles'] = 'Es wurden noch keine Sicherungsdateien erstellt.';
$string['exportscope_full'] = 'Vollständige Datenbank';
$string['exportscope_logs'] = 'Nur Protokolle';
$string['exportscope_main'] = 'Datenbank ohne Protokolle';
$string['filenotfound'] = 'Sicherungsdatei nicht gefunden: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'Die PHP-Erweiterung zlib/gzip ist auf diesem Server nicht verfügbar.';
$string['history_title'] = 'Erstellte Dateien';
$string['home_kpi_empty_subtitle'] = 'Keine Sicherung erstellt';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Neueste Datei: {$a}';
$string['home_kpi_title'] = 'Letzte Sicherung';
$string['invalidaction'] = 'Die angeforderte Aktion ist ungültig.';
$string['invalidfilename'] = 'Der angegebene Dateiname ist ungültig.';
$string['invalidoutputformat'] = 'Das angegebene Ausgabeformat ist ungültig: {$a}';
$string['menu_desc'] = 'Manuelle Erstellung von moodledata-Sicherungen und Datenbankexport';
$string['menu_title'] = 'Sicherungen';
$string['moodledata_desc'] = 'Erstellt ein vollständiges Paket des moodledata-Ordners, wobei nur der Ordner ausgeschlossen wird, in dem die Sicherungen selbst gespeichert sind.';
$string['moodledata_success'] = 'Moodledata-Sicherung erfolgreich erstellt: {$a}';
$string['moodledata_title'] = 'Moodledata-Sicherung';
$string['notablesfound'] = 'Es wurden keine Tabellen für den Export gefunden.';
$string['outputformat_desc'] = 'Sie können in das aktuelle Datenbankformat exportieren oder die Ausgabe zwischen MySQL/MariaDB und PostgreSQL konvertieren.';
$string['outputformat_label'] = 'Ausgabeformat';
$string['page_title'] = 'Sicherungszentrale';
$string['pluginname'] = 'Sicherungen';
$string['processfailed'] = 'Der Sicherungsprozess konnte nicht ausgeführt werden: {$a}';
$string['processstartfailed'] = 'Der Sicherungsprozess konnte auf dem Server nicht gestartet werden.';
$string['separatelogs_desc'] = 'Wenn aktiviert, erstellt das System ein ZIP-Paket mit einer Datei für die Hauptdatenbank und einer weiteren Datei nur für Protokolltabellen.';
$string['separatelogs_label'] = 'Möchten Sie die Protokolle separat exportieren?';
$string['storage_desc'] = 'Die erstellten Dateien werden im geschützten moodledata-Bereich gespeichert.';
$string['storage_title'] = 'Speicherort';
$string['type_database'] = 'Datenbank';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Der Prozess hat einen Fehler zurückgegeben, aber keine Details bereitgestellt.';
$string['unsupporteddbtype'] = 'Datenbanktyp wird von diesem Sicherungs-Plugin nicht unterstützt: {$a}';
$string['zipcreatefailed'] = 'Die ZIP-Datei konnte nicht erstellt werden.';
