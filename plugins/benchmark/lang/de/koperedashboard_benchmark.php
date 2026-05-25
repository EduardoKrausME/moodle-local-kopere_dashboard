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

$string['back_to_benchmark'] = 'Zurück zum Benchmark';
$string['cap_run'] = 'Benchmark ausführen';
$string['cap_run_desc'] = 'Kann synthetische Benchmark-Tests im Kopere Dashboard ausführen.';
$string['cap_view'] = 'Benchmark anzeigen';
$string['cap_view_desc'] = 'Kann den Benchmark-Bereich aufrufen und Leistungsempfehlungen anzeigen.';
$string['check_backup_auto_active'] = 'Automatische Sicherung';
$string['check_cachejs'] = 'JavaScript-Cache';
$string['check_debug'] = 'Debug-Stufe';
$string['check_debugdisplay'] = 'Debug-Meldungen anzeigen';
$string['check_themedesignermode'] = 'Theme-Designer-Modus';
$string['debug_value'] = 'Aktiviert ({$a})';
$string['environment_db'] = 'Datenbank';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'OPcache-Details';
$string['environment_opcache_warning'] = 'Lassen Sie OPcache in der Produktion aktiviert. Es speichert kompilierte PHP-Skripte im Arbeitsspeicher, reduziert die CPU-Nutzung und verbessert die Antwortzeit.';
$string['environment_os'] = 'Betriebssystem';
$string['environment_os_windows_warning'] = 'Windows wird für Moodle-Produktionsumgebungen nicht empfohlen. Bevorzugen Sie Linux für bessere Kompatibilität, Stabilität und Leistung. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Moodle-Dokumentation: Das vollständige Windows-Installationspaket wird nicht für die Produktion empfohlen</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Umgebung';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Lassen Sie X-Sendfile in der Produktion aktiviert. Dadurch kann der Webserver Dateien direkt ausliefern, was die PHP-Speichernutzung reduziert und Downloads großer Dateien verbessert.';
$string['execute_title'] = 'Benchmark ausführen';
$string['help_recommendations'] = 'Diese Empfehlungen helfen bei der Einschätzung, ob die Umgebung für die Produktion konfiguriert ist. Sie ersetzen keine detaillierte Analyse von Datenbank, Redis, Cron, Datenträgern oder Reverse Cache.';
$string['iterations'] = 'Iterationen';
$string['label_disabled'] = 'Deaktiviert';
$string['label_enabled'] = 'Aktiviert';
$string['manage_intro'] = 'Führen Sie eine kurze Reihe synthetischer Tests aus, um einen schnellen Überblick über die allgemeine Leistung des Moodle-Servers zu erhalten. Die Tests messen einfache Datenbanklesevorgänge, Datenträger-Roundtrip, JSON, Hash- und Zeichenkettenverarbeitung.';
$string['manage_warning'] = 'Die Ergebnisse sind vergleichend. Idealerweise führen Sie sie immer auf demselben Server aus und vergleichen vor/nach Änderungen an PHP, Datenbank, Datenträger, Cache, Redis, Nginx oder Plugins.';
$string['menu_desc'] = 'Misst Datenbank-, Datenträger- und CPU-Zeit mit schnellen Produktionsempfehlungen.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>Speicher: {$a->memory} MB<br>max. Dateien: {$a->maxfiles}<br>Zeitstempel validieren: {$a->timestamps}<br>Revalidierungsfrequenz: {$a->revalidate}';
$string['peakmemory'] = 'Spitzen-Arbeitsspeicher';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Vermeiden Sie automatische Sicherungen während Spitzenzeiten. Bevorzugen Sie Zeitfenster außerhalb der Hauptnutzungszeiten.';
$string['recommend_cachejs'] = 'Lassen Sie den JavaScript-Cache in der Produktion aktiviert, um Verarbeitung und Übertragung zu reduzieren.';
$string['recommend_debug'] = 'Aktives Debugging erhöht Verarbeitungsaufwand und Störmeldungen. Lassen Sie es in der Produktion deaktiviert.';
$string['recommend_debugdisplay'] = 'Die direkte Anzeige von Debug-Meldungen am Bildschirm sollte in der Produktion deaktiviert sein.';
$string['recommend_themedesignermode'] = 'Der Theme-Designer-Modus sollte in der Produktion deaktiviert sein, um CSS-Neukompilierung und Leistungseinbrüche zu vermeiden.';
$string['recommendation'] = 'Empfehlung';
$string['recommendations_title'] = 'Schnelle Konfigurationsprüfungen';
$string['result_status'] = 'Status';
$string['results_title'] = 'Testergebnisse';
$string['run_benchmark'] = 'Benchmark ausführen';
$string['status_attention'] = 'Achtung';
$string['status_fast'] = 'Schnell';
$string['status_slow'] = 'Langsam';
$string['summary_title'] = 'Zusammenfassung';
$string['test_db_desc'] = 'Wiederholtes Lesen kleiner Konfigurationsdatensätze aus der Datenbank.';
$string['test_db_name'] = 'Datenbank';
$string['test_files_desc'] = 'Schreiben, Lesen und Entfernen einer lokalen temporären Datei.';
$string['test_files_name'] = 'Dateisystem';
$string['test_hash_desc'] = 'Wiederholte SHA-256-Runden zur Messung der reinen CPU-Leistung.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Kodieren und Dekodieren mittelgroßer JSON-Strukturen.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Test';
$string['test_string_desc'] = 'Einfache Bereinigung und Analyse von HTML-ähnlichen Inhalten.';
$string['test_string_name'] = 'Zeichenketten / HTML';
$string['time_elapsed'] = 'Zeit';
$string['total_time'] = 'Gesamtzeit';
$string['value'] = 'Wert';
$string['xsendfile_value'] = 'Aktiviert ({$a->header}<br>Aliase: {$a->aliases})';
