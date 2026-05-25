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
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_mapping'] = 'Zurück zur Zuordnung';
$string['cannotreadcsv'] = 'Die CSV-Datei konnte nicht gelesen werden.';
$string['cap_manage'] = 'Benutzerimporte verwalten';
$string['cap_manage_desc'] = 'CSV-Dateien hochladen, Zeilen in der Vorschau anzeigen und Benutzerimporte im Kopere Dashboard ausführen.';
$string['confirm_import'] = 'Benutzer jetzt importieren';
$string['course_not_found'] = 'Kurs nicht gefunden.';
$string['customfields'] = 'Benutzerdefinierte Profilfelder';
$string['filename'] = 'Datei';
$string['group_not_found'] = 'Gruppe im ausgewählten Kurs nicht gefunden.';
$string['idnumbercourse'] = 'Kurs-ID-Nummer';
$string['invalidcsv'] = 'Die Datei scheint keine gültige CSV-Datei mit Kopfzeilen zu sein.';
$string['invalidemail'] = 'Ungültige E-Mail-Adresse.';
$string['invalidfiletype'] = 'Es werden nur .csv- oder .txt-Dateien akzeptiert.';
$string['invalidtoken'] = 'Das Import-Token ist ungültig oder abgelaufen.';
$string['invalidusername'] = 'Ungültiger Wert für den Benutzernamen.';
$string['manage_intro'] = 'Laden Sie eine CSV-Datei hoch, um Benutzer zu erstellen, vorhandene Konten wiederzuverwenden, benutzerdefinierte Profilfelder zu füllen und sie optional in Kurse und Gruppen einzuschreiben.';
$string['manualinstance_missing'] = 'Der Kurs {$a} hat keine aktivierte manuelle Einschreibemethode.';
$string['manualplugin_missing'] = 'Das Plugin für manuelle Einschreibung ist auf dieser Moodle-Website nicht verfügbar.';
$string['mappingerror_email'] = 'Ordnen Sie die E-Mail-Spalte zu.';
$string['mappingerror_firstname'] = 'Ordnen Sie die Vornamensspalte zu (oder die Spalte mit dem vollständigen Namen).';
$string['menu_desc'] = 'Importieren Sie Benutzer per CSV, sehen Sie das Ergebnis in der Vorschau, erstellen Sie Konten und schreiben Sie sie optional in Kurse/Gruppen ein.';
$string['menu_title'] = 'Benutzerimport';
$string['missingemail'] = 'E-Mail fehlt.';
$string['missingfirstname'] = 'Vorname fehlt.';
$string['missingtempfile'] = 'Die temporäre CSV-Datei existiert nicht mehr.';
$string['missingusername'] = 'Benutzername fehlt.';
$string['pluginname'] = 'Benutzerimport';
$string['preview_intro'] = 'Prüfen Sie die ersten Zeilen, ordnen Sie jede CSV-Spalte zu und führen Sie vor dem Import eine Vorschau aus.';
$string['preview_title'] = 'Vorschau und Zuordnung';
$string['result_alreadyenrolled'] = 'Bereits eingeschrieben';
$string['result_courseenrolled'] = 'In {$a} eingeschrieben';
$string['result_groupadded'] = 'Zur Gruppe {$a} hinzugefügt';
$string['result_usercreated'] = 'Benutzer erstellt';
$string['result_userexists'] = 'Benutzer war bereits vorhanden';
$string['run_preview'] = 'Vorschau ausführen';
$string['saveuploaderror'] = 'Die hochgeladene Datei konnte nicht im temporären Moodle-Speicher gespeichert werden.';
$string['select_column'] = 'Spalte auswählen';
$string['separator'] = 'Erkanntes Trennzeichen';
$string['separator_comma'] = 'Komma (,)';
$string['separator_semicolon'] = 'Semikolon (;)';
$string['separator_tab'] = 'Tabulator';
$string['shortnamecourse'] = 'Kurzname/vollständiger Name des Kurses';
$string['start_over'] = 'Neuen Import starten';
$string['status_created'] = 'Erstellt';
$string['status_error'] = 'Fehler';
$string['status_existing'] = 'Vorhandener Benutzer';
$string['status_ok'] = 'Bereit';
$string['status_willcreate'] = 'Wird erstellt';
$string['summary_create'] = 'Wird erstellt';
$string['summary_created'] = 'Erstellte Benutzer';
$string['summary_enrolled'] = 'Neue Einschreibungen';
$string['summary_errors'] = 'Fehler';
$string['summary_existing'] = 'Vorhandene';
$string['summary_total'] = 'Zeilen';
$string['summary_withcourse'] = 'Zeilen mit Kurs';
$string['table_course'] = 'Kurs';
$string['table_email'] = 'E-Mail';
$string['table_firstname'] = 'Vorname';
$string['table_group'] = 'Gruppe';
$string['table_lastname'] = 'Nachname';
$string['table_line'] = 'Zeile';
$string['table_message'] = 'Nachricht';
$string['table_status'] = 'Status';
$string['table_username'] = 'Benutzername';
$string['tip_detectseparator'] = 'Das Plugin erkennt automatisch Dateien, die durch Semikolon, Komma oder Tabulator getrennt sind.';
$string['tip_existing'] = 'Vorhandene Benutzer werden nicht dupliziert. Sie können weiterhin Daten für benutzerdefinierte Felder und Kurseinschreibungen erhalten.';
$string['tip_headers'] = 'Die erste CSV-Zeile wird als Kopfzeile behandelt.';
$string['tip_password'] = 'Wenn ein neuer Benutzer kein Passwort oder ein sehr kurzes Passwort hat, wird ein zufälliges Passwort erzeugt und die erzwungene Passwortänderung aktiviert.';
$string['upload_csv'] = 'CSV-Datei hochladen';
$string['upload_submit'] = 'Fortfahren';
$string['uploaderror'] = 'Senden Sie eine gültige CSV-Datei.';
