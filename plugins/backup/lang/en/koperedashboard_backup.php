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
 * lang
 *
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['action_download'] = 'Download';
$string['action_generate_database'] = 'Export database';
$string['action_generate_moodledata'] = 'Generate moodledata backup';
$string['cannotopenexportfile'] = 'Could not open the export file: {$a}';
$string['cap_generate'] = 'Generate backups';
$string['cap_generate_desc'] = 'Allows users to generate moodledata backup files and database backups.';
$string['cap_view'] = 'View backup center';
$string['cap_view_desc'] = 'Allows users to access the backup center and download generated files.';
$string['col_actions'] = 'Actions';
$string['col_created'] = 'Created at';
$string['col_file'] = 'File';
$string['col_size'] = 'Size';
$string['col_type'] = 'Type';
$string['commandnotfound'] = 'The required system command was not found: {$a}.';
$string['current_source_label'] = 'Current database:';
$string['database_desc'] = 'Exports the database structure and data in PHP, allowing you to choose the output format and optionally separate logs into their own file.';
$string['database_success'] = 'Database export generated successfully: {$a}';
$string['database_title'] = 'Database export';
$string['emptyfiles'] = 'No backup files have been generated yet.';
$string['exportscope_full'] = 'Full database';
$string['exportscope_logs'] = 'Logs only';
$string['exportscope_main'] = 'Database without logs';
$string['filenotfound'] = 'Backup file not found: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'The PHP zlib/gzip extension is not available on this server.';
$string['history_title'] = 'Generated files';
$string['home_kpi_empty_subtitle'] = 'No backup generated';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Most recent file: {$a}';
$string['home_kpi_title'] = 'Latest backup';
$string['invalidaction'] = 'The requested action is invalid.';
$string['invalidfilename'] = 'The specified file name is invalid.';
$string['invalidoutputformat'] = 'The specified output format is invalid: {$a}';
$string['menu_desc'] = 'Manual moodledata backup generation and database export';
$string['menu_title'] = 'Backups';
$string['moodledata_desc'] = 'Generates a full package of the moodledata folder, excluding only the folder where the backups themselves are stored.';
$string['moodledata_success'] = 'Moodledata backup generated successfully: {$a}';
$string['moodledata_title'] = 'Moodledata backup';
$string['notablesfound'] = 'No tables were found for export.';
$string['outputformat_desc'] = 'You can export to the current database format or convert the output between MySQL/MariaDB and PostgreSQL.';
$string['outputformat_label'] = 'Output format';
$string['page_title'] = 'Backup center';
$string['pluginname'] = 'Backups';
$string['processfailed'] = 'Failed to run the backup process: {$a}';
$string['processstartfailed'] = 'Could not start the backup process on the server.';
$string['separatelogs_desc'] = 'When enabled, the system generates a ZIP package with one file for the main database and another file only for log tables.';
$string['separatelogs_label'] = 'Do you want to export the logs separately?';
$string['storage_desc'] = 'Generated files are saved inside the protected moodledata area.';
$string['storage_title'] = 'Storage location';
$string['type_database'] = 'Database';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'The process returned an error but did not provide details.';
$string['unsupporteddbtype'] = 'Database type not supported by this backup plugin: {$a}';
$string['zipcreatefailed'] = 'Could not create the ZIP file.';
