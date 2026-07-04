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
 * backup_manager.php
 *
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_backup;

use FilesystemIterator;
use moodle_exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;
use ZipArchive;

/**
 * Class backup_manager
 */
class backup_manager {
    /** @var string */
    private const BACKUPDIRNAME = "kopere_dashboard-backups";

    /** @var string */
    private const TYPE_DATABASE = "database";

    /** @var string */
    private const TYPE_MOODLEDATA = "moodledata";

    /** @var int */
    private const INSERT_BATCH_SIZE = 100;

    /** @var string */
    private const FRIENDLY_INSTALLATION_BASE64_PREFIX = "__BASE64__:";

    /**
     * Function create_moodledata_backup
     *
     * @return string
     * @throws \moodle_exception
     */
    public static function create_moodledata_backup(): string {
        global $CFG;

        raise_memory_limit(MEMORY_EXTRA);
        @set_time_limit(0);

        $backupdir = self::get_backup_dir();
        $filename = self::TYPE_MOODLEDATA . "-" . date("Ymd-His") . ".tar.gz";
        $filepath = "{$backupdir}/{$filename}";

        if (self::command_exists("tar")) {
            $command = "tar --exclude=" . escapeshellarg(self::BACKUPDIRNAME) .
                " -czf " . escapeshellarg($filepath) .
                " -C " . escapeshellarg($CFG->dataroot) . " .";

            self::run_shell_command($command);
            return $filepath;
        }

        $filename = self::TYPE_MOODLEDATA . "-" . date("Ymd-His") . ".zip";
        $filepath = "{$backupdir}/{$filename}";
        self::create_moodledata_zip($CFG->dataroot, $filepath);

        return $filepath;
    }

    /**
     * Function create_database_backup
     *
     * @param string $outputformat
     * @param bool $separatelogs
     * @return string
     * @throws \moodle_exception
     * @throws \Throwable
     */
    public static function create_database_backup(string $outputformat, bool $separatelogs = false): string {
        raise_memory_limit(MEMORY_EXTRA);
        @set_time_limit(0);

        $outputformat = self::normalize_output_format($outputformat);
        $sourcetype = self::get_source_database_family();
        $tables = self::list_database_tables();

        if (empty($tables)) {
            throw new moodle_exception("notablesfound", "koperedashboard_backup");
        }

        $timestamp = date("Ymd-His");
        $backupdir = self::get_backup_dir();
        $basename = self::TYPE_DATABASE . "-{$sourcetype}-to-{$outputformat}-{$timestamp}";

        if (!$separatelogs) {
            $filepath = "{$backupdir}/{$basename}.sql.gz";
            $exportscopefull = get_string("exportscope_full", "koperedashboard_backup");
            self::export_tables_to_gzip($filepath, $tables, $outputformat, $exportscopefull);
            return $filepath;
        }

        $maintables = [];
        $logtables = [];
        foreach ($tables as $table) {
            if (self::is_log_table($table)) {
                $logtables[] = $table;
            } else {
                $maintables[] = $table;
            }
        }

        $tempdir = "{$backupdir}/{$basename}-tmp-" . uniqid("", true);
        mkdir($tempdir, 0700, true);

        try {
            $mainfile = "{$tempdir}/database-main.sql.gz";
            self::export_tables_to_gzip(
                $mainfile, $maintables, $outputformat, get_string("exportscope_main", "koperedashboard_backup")
            );

            $files = [$mainfile];
            if (!empty($logtables)) {
                $logfile = "{$tempdir}/database-logs.sql.gz";
                self::export_tables_to_gzip(
                    $logfile, $logtables, $outputformat, get_string("exportscope_logs", "koperedashboard_backup")
                );
                $files[] = $logfile;
            }

            $manifest = "{$tempdir}/manifest.txt";
            self::write_manifest($manifest, $sourcetype, $outputformat, $maintables, $logtables);
            $files[] = $manifest;

            $zipfile = "{$backupdir}/{$basename}.zip";
            self::create_zip_bundle($zipfile, $files);
        } catch (Throwable $e) {
            self::delete_path($tempdir);
            throw $e;
        }

        self::delete_path($tempdir);
        return $zipfile;
    }

    /**
     * Function create_friendly_installation_backup
     *
     * @return string
     * @throws \dml_exception
     * @throws \moodle_exception
     * @throws \Throwable
     */
    public static function create_friendly_installation_backup(): string {
        global $CFG;

        raise_memory_limit(MEMORY_EXTRA);
        @set_time_limit(0);

        $tables = self::list_database_tables();
        if (empty($tables)) {
            throw new moodle_exception("notablesfound", "koperedashboard_backup");
        }

        $exportdir = self::get_friendly_installation_dir();
        $schemadir = "{$exportdir}/schema";
        $datadir = "{$exportdir}/data";

        self::delete_path($schemadir);
        self::delete_path($datadir);

        mkdir($schemadir, $CFG->directorypermissions, true);
        mkdir($datadir, $CFG->directorypermissions, true);

        foreach ($tables as $table) {
            $columns = self::get_table_columns($table);
            $indexes = self::get_table_indexes($table);
            $exportname = self::get_friendly_installation_table_name($table);

            self::write_friendly_installation_schema("{$schemadir}/{$exportname}.json", $exportname, $columns, $indexes);
            self::write_friendly_installation_data("{$datadir}/{$exportname}.csv", $table, $columns);
        }

        $manifest = "{$exportdir}/manifest.json";
        self::write_friendly_installation_manifest($manifest, $tables);

        $zipfile = self::get_backup_dir() . "/friendly_installation-" . date("Ymd-His") . ".zip";
        $extradirs = [];
        if (!self::is_alternative_file_system_ready()) {
            $moodledatafilesdir = self::get_moodledata_files_dir();
            if ($moodledatafilesdir !== null) {
                $extradirs["backup/moodledata/files"] = $moodledatafilesdir;
            }
        }

        self::create_directory_zip($zipfile, $exportdir, "backup", $extradirs);

        return $zipfile;
    }

    /**
     * Function get_database_export_formats
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_database_export_formats(): array {
        return [
            [
                "value" => "mysql",
                "label" => get_string("format_mysql", "koperedashboard_backup"),
            ],
            [
                "value" => "pgsql",
                "label" => get_string("format_pgsql", "koperedashboard_backup"),
            ],
        ];
    }

    /**
     * Function get_default_database_export_format
     *
     * @return string
     * @throws \moodle_exception
     */
    public static function get_default_database_export_format(): string {
        return self::get_source_database_family();
    }

    /**
     * Function get_source_database_label
     *
     * @return string
     * @throws \moodle_exception
     */
    public static function get_source_database_label(): string {
        return get_string("format_" . self::get_source_database_family(), "koperedashboard_backup");
    }

    /**
     * Function is_alternative_file_system_ready
     *
     * @return bool
     * @throws \dml_exception
     */
    public static function is_alternative_file_system_ready(): bool {
        global $CFG;

        if (!isset($CFG->alternative_file_system_class[50])) {
            return false;
        }
        else if ($CFG->alternative_file_system_class != '\\local_alternative_file_system\\external_file_system') {
            return false;
        }

        $settingslocal = get_config("local_alternative_file_system", "storage_destination");
        return isset($settingslocal[1]);
    }

    /**
     * Function list_backups
     *
     * @return array
     */
    public static function list_backups(): array {
        $backupdir = self::get_backup_dir();
        $files = glob("{$backupdir}/*");
        if (empty($files)) {
            return [];
        }

        rsort($files);
        $items = [];

        foreach ($files as $filepath) {
            if (!is_file($filepath)) {
                continue;
            }

            $filename = basename($filepath);
            $items[] = [
                "filename" => $filename,
                "filesize" => filesize($filepath) ?: 0,
                "timemodified" => filemtime($filepath) ?: 0,
                "type" => self::detect_type($filename),
            ];
        }

        return $items;
    }

    /**
     * Function get_backup_file_path
     *
     * @param string $filename
     * @return string
     * @throws \moodle_exception
     */
    public static function get_backup_file_path(string $filename): string {
        if ($filename === "" || $filename !== basename($filename)) {
            throw new moodle_exception("invalidfilename", "koperedashboard_backup");
        }

        $filepath = self::get_backup_dir() . "/{$filename}";
        if (!is_file($filepath)) {
            throw new moodle_exception("filenotfound", "koperedashboard_backup", "", $filename);
        }

        return $filepath;
    }

    /**
     * Delete a generated backup file.
     *
     * @param string $filename
     * @return void
     * @throws \moodle_exception
     */
    public static function delete_backup_file(string $filename): void {
        $filepath = self::get_backup_file_path($filename);

        if (!unlink($filepath)) {
            throw new moodle_exception("deletefailed", "koperedashboard_backup", "", $filename);
        }
    }

    /**
     * Function get_backup_dir
     *
     * @return string
     */
    private static function get_backup_dir(): string {
        global $CFG;

        $backupdir = $CFG->dataroot . "/" . self::BACKUPDIRNAME;
        if (!is_dir($backupdir)) {
            mkdir($backupdir, $CFG->directorypermissions, true);
        }

        return $backupdir;
    }

    /**
     * Function get_friendly_installation_dir
     *
     * @return string
     */
    private static function get_friendly_installation_dir(): string {
        global $CFG;

        $backupdir = "{$CFG->dataroot}/schema";
        if (!is_dir($backupdir)) {
            mkdir($backupdir, $CFG->directorypermissions, true);
        }

        return $backupdir;
    }

    /**
     * Function create_moodledata_zip
     *
     * @param string $source
     * @param string $destination
     * @return void
     * @throws \moodle_exception
     */
    private static function create_moodledata_zip(string $source, string $destination): void {
        $zip = new ZipArchive();
        if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new moodle_exception("zipcreatefailed", "koperedashboard_backup");
        }

        $source = rtrim($source, "/");
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $pathname = $item->getPathname();
            $relativepath = ltrim(substr($pathname, strlen($source)), "/");
            if ($relativepath === "" || strpos($relativepath, self::BACKUPDIRNAME . "/") === 0 ||
                $relativepath === self::BACKUPDIRNAME) {
                continue;
            }

            if ($item->isDir()) {
                $zip->addEmptyDir($relativepath);
                continue;
            }

            $zip->addFile($pathname, $relativepath);
        }

        $zip->close();
    }

    /**
     * Function get_moodledata_files_dir
     *
     * @return string|null
     */
    private static function get_moodledata_files_dir(): ?string {
        global $CFG;

        $filesdir = "{$CFG->dataroot}/files";
        if (is_dir($filesdir)) {
            return $filesdir;
        }

        $filedir = "{$CFG->dataroot}/filedir";
        if (is_dir($filedir)) {
            return $filedir;
        }

        return null;
    }

    /**
     * Function write_friendly_installation_schema
     *
     * @param string $filepath
     * @param string $tablename
     * @param array $columns
     * @param array $indexes
     * @return void
     * @throws \moodle_exception
     */
    private static function write_friendly_installation_schema(string $filepath, string $tablename, array $columns, array $indexes
    ): void {
        $primarycolumns = self::get_primary_columns_from_indexes($indexes);
        $fields = [];

        foreach ($columns as $column) {
            $field = [
                "name" => $column["name"],
                "type" => self::get_friendly_installation_field_type($column),
            ];

            $length = self::get_friendly_installation_field_length($column);
            if ($length !== null) {
                $field["length"] = $length;
            }

            $decimals = self::get_friendly_installation_field_decimals($column);
            if ($decimals !== null) {
                $field["decimals"] = $decimals;
            }

            $field["nullable"] = (bool) $column["nullable"];
            $field["notnull"] = empty($column["nullable"]);

            if (!empty($column["unsigned"])) {
                $field["unsigned"] = true;
            }

            $default = self::get_friendly_installation_default_value($column);
            if ($default !== null) {
                $field["default"] = $default;
            }

            $field["auto_increment"] = !empty($column["auto_increment"]);
            $field["sequence"] = !empty($column["auto_increment"]);

            if (in_array($column["name"], $primarycolumns, true)) {
                $field["primary"] = true;
            }

            $fields[] = $field;
        }

        $schema = [
            "table" => $tablename,
            "fields" => $fields,
        ];

        $schemakeys = [];
        foreach ($indexes as $index) {
            if (empty($index["isprimary"]) || empty($index["columns"])) {
                continue;
            }

            $schemakeys[] = [
                "name" => "primary",
                "type" => "primary",
                "fields" => array_values($index["columns"]),
            ];
        }

        if (!empty($schemakeys)) {
            $schema["keys"] = $schemakeys;
        }

        $schemaindexes = [];
        foreach ($indexes as $index) {
            if (!empty($index["isprimary"]) || empty($index["columns"])) {
                continue;
            }

            $schemaindexes[] = [
                "name" => $index["name"],
                "unique" => !empty($index["isunique"]),
                "fields" => array_values($index["columns"]),
            ];
        }

        if (!empty($schemaindexes)) {
            $schema["indexes"] = $schemaindexes;
        }

        $json = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents($filepath, "{$json}\n") === false) {
            throw new moodle_exception("cannotopenexportfile", "koperedashboard_backup", "", $filepath);
        }
    }

    /**
     * Function write_friendly_installation_data
     *
     * @param string $filepath
     * @param string $table
     * @param array $columns
     * @return void
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function write_friendly_installation_data(string $filepath, string $table, array $columns): void {
        global $DB;

        $handle = fopen($filepath, "wb");
        if ($handle === false) {
            throw new moodle_exception("cannotopenexportfile", "koperedashboard_backup", "", $filepath);
        }

        if (empty($columns)) {
            fclose($handle);
            return;
        }

        $columnnames = array_column($columns, "name");
        $columnsql = implode(", ", array_map(static function(string $columnname): string {
            return self::quote_identifier($columnname, self::get_source_database_family());
        }, $columnnames));

        $prefixless = self::remove_table_prefix($table);
        $recordset = $DB->get_recordset_sql("SELECT {$columnsql} FROM {{$prefixless}}");

        try {
            foreach ($recordset as $record) {
                $values = [];
                foreach ($columns as $column) {
                    $name = $column["name"];
                    $value = self::format_value_for_restore_moodle($record->{$name} ?? null, $column);
                    $values[] = self::encode_value_for_friendly_installation_csv($value, $column);
                }

                if (fputcsv($handle, $values, ";", '"', "\\") === false) {
                    throw new moodle_exception("cannotopenexportfile", "koperedashboard_backup", "", $filepath);
                }
            }
        } catch (Throwable $e) {
            $recordset->close();
            fclose($handle);
            throw $e;
        }

        $recordset->close();
        fclose($handle);
    }

    /**
     * Function write_friendly_installation_manifest
     *
     * @param string $filepath
     * @param array $tables
     * @return void
     * @throws \moodle_exception
     */
    private static function write_friendly_installation_manifest(string $filepath, array $tables): void {
        global $CFG;

        $manifest = [
            "type" => "restore_moodle",
            "generated_at" => date("c"),
            "source_database" => self::get_source_database_family(),
            "source_moodle" => [
                "version" => $CFG->version ?? "",
                "release" => $CFG->release ?? "",
                "branch" => $CFG->branch ?? "",
            ],
            "table_count" => count($tables),
            "tables" => array_map(static function(string $table): string {
                return self::get_friendly_installation_table_name($table);
            }, $tables),
        ];

        $json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents($filepath, "{$json}\n") === false) {
            throw new moodle_exception("cannotopenexportfile", "koperedashboard_backup", "", $filepath);
        }
    }

    /**
     * Function get_primary_columns_from_indexes
     *
     * @param array $indexes
     * @return array
     */
    private static function get_primary_columns_from_indexes(array $indexes): array {
        foreach ($indexes as $index) {
            if (!empty($index["isprimary"])) {
                return $index["columns"];
            }
        }

        return [];
    }

    /**
     * Function get_friendly_installation_table_name
     *
     * @param string $table
     * @return string
     */
    private static function get_friendly_installation_table_name(string $table): string {
        $tablename = self::remove_table_prefix($table);
        $tablename = preg_replace('/[^a-zA-Z0-9_\-]/', "_", $tablename);

        return trim($tablename, "_") ?: "table";
    }

    /**
     * Function get_friendly_installation_field_type
     *
     * @param array $column
     * @return string
     */
    private static function get_friendly_installation_field_type(array $column): string {
        if (self::is_datetime_like_column($column)) {
            return "datetime";
        }

        switch ($column["abstracttype"]) {
            case "boolean":
            case "smallint":
            case "integer":
            case "bigint":
                return "int";
            case "decimal":
                return "number";
            case "float":
                return "float";
            case "char":
            case "varchar":
                return "char";
            case "date":
            case "time":
            case "timestamp":
                return "datetime";
            case "binary":
                return "binary";
            default:
                return "text";
        }
    }

    /**
     * Function get_friendly_installation_field_length
     *
     * @param array $column
     * @return int|string|null
     */
    private static function get_friendly_installation_field_length(array $column) {
        switch ($column["abstracttype"]) {
            case "boolean":
                return 1;
            case "smallint":
            case "integer":
            case "bigint":
                return self::get_integer_display_length($column);
            case "decimal":
            case "float":
                return $column["precision"] !== null ? (int) $column["precision"] : null;
            case "char":
            case "varchar":
                return $column["length"] !== null ? (int) $column["length"] : 255;
            case "text":
            case "binary":
                return self::get_large_field_length($column);
            default:
                return null;
        }
    }

    /**
     * Function get_friendly_installation_field_decimals
     *
     * @param array $column
     * @return int|null
     */
    private static function get_friendly_installation_field_decimals(array $column): ?int {
        if (!in_array($column["abstracttype"], ["decimal", "float"], true)) {
            return null;
        }

        return $column["scale"] !== null ? (int) $column["scale"] : null;
    }

    /**
     * Function get_friendly_installation_default_value
     *
     * @param array $column
     * @return string|null
     */
    private static function get_friendly_installation_default_value(array $column): ?string {
        if (!array_key_exists("default", $column) || $column["default"] === null || !empty($column["auto_increment"])) {
            return null;
        }

        $default = self::normalize_default_expression((string) $column["default"]);
        if (strtolower($default) === "null") {
            return null;
        }

        if ($column["abstracttype"] === "boolean") {
            $normalized = strtolower($default);
            if (in_array($normalized, ["1", "true", "t", "yes"], true)) {
                return "1";
            }

            if (in_array($normalized, ["0", "false", "f", "no"], true)) {
                return "0";
            }
        }

        return $default;
    }

    /**
     * Function get_integer_display_length
     *
     * @param array $column
     * @return int
     */
    private static function get_integer_display_length(array $column): int {
        if (preg_match('/\((\d+)\)/', (string) $column["columntype"], $matches)) {
            return (int) $matches[1];
        }

        if ($column["abstracttype"] === "smallint") {
            return 4;
        }

        return 10;
    }

    /**
     * Function get_large_field_length
     *
     * @param array $column
     * @return string|null
     */
    private static function get_large_field_length(array $column): ?string {
        $columntype = (string) $column["columntype"];
        if (strpos($columntype, "tiny") !== false) {
            return "small";
        }

        if (strpos($columntype, "medium") !== false) {
            return "medium";
        }

        if (strpos($columntype, "long") !== false) {
            return "big";
        }

        return null;
    }

    /**
     * Function format_value_for_restore_moodle
     *
     * @param mixed $value
     * @param array $column
     * @return string
     */
    private static function format_value_for_restore_moodle($value, array $column): string {
        if ($value === null) {
            return "";
        }

        if (self::is_datetime_like_column($column)) {
            return self::format_datetime_for_restore_moodle($value);
        }

        switch ($column["abstracttype"]) {
            case "boolean":
                return (string) ((int) (bool) $value);
            case "smallint":
            case "integer":
            case "bigint":
                return (string) (int) $value;
            case "decimal":
            case "float":
                return is_numeric($value) ? $value : "0";
            case "binary":
                return (string) $value;
            default:
                return $value;
        }
    }

    /**
     * Function encode_value_for_friendly_installation_csv
     *
     * @param string $value
     * @param array $column
     * @return string
     */
    private static function encode_value_for_friendly_installation_csv(string $value, array $column): string {
        if ($value === "") {
            return "";
        }

        if ($column["abstracttype"] === "binary") {
            return self::FRIENDLY_INSTALLATION_BASE64_PREFIX . base64_encode($value);
        }

        if (self::is_text_like_column($column) && preg_match('/[^A-Za-z0-9]/', $value)) {
            return self::FRIENDLY_INSTALLATION_BASE64_PREFIX . base64_encode($value);
        }

        return $value;
    }

    /**
     * Function is_text_like_column
     *
     * @param array $column
     * @return bool
     */
    private static function is_text_like_column(array $column): bool {
        return in_array($column["abstracttype"], ["char", "varchar", "text", "binary"], true);
    }

    /**
     * Function format_datetime_for_restore_moodle
     *
     * @param mixed $value
     * @return string
     */
    private static function format_datetime_for_restore_moodle($value): string {
        if ($value === "" || $value === false) {
            return "";
        }

        if (is_numeric($value)) {
            return gmdate("Y-m-d\\TH:i:s", (int) $value);
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        }

        return gmdate("Y-m-d\\TH:i:s", $timestamp);
    }

    /**
     * Function is_datetime_like_column
     *
     * @param array $column
     * @return bool
     */
    private static function is_datetime_like_column(array $column): bool {
        return in_array($column["abstracttype"], ["date", "time", "timestamp"], true);
    }

    /**
     * Function export_tables_to_gzip
     *
     * @param string $filepath
     * @param array $tables
     * @param string $outputformat
     * @param string $scope
     * @return void
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function export_tables_to_gzip(string $filepath, array $tables, string $outputformat, string $scope): void {
        if (!function_exists("gzopen")) {
            throw new moodle_exception("gzipnotavailable", "koperedashboard_backup");
        }

        $handle = gzopen($filepath, "wb9");
        if ($handle === false) {
            throw new moodle_exception("cannotopenexportfile", "koperedashboard_backup", "", $filepath);
        }

        self::write_export_header($handle, $outputformat, $scope, $tables);

        foreach ($tables as $table) {
            self::export_single_table($handle, $table, $outputformat);
        }

        gzclose($handle);
    }

    /**
     * Function write_export_header
     *
     * @param resource $handle
     * @param string $outputformat
     * @param string $scope
     * @param array $tables
     * @return void
     * @throws \moodle_exception
     */
    private static function write_export_header($handle, string $outputformat, string $scope, array $tables): void {
        $header = [];
        $header[] = "-- Kopere Dashboard database export";
        $header[] = "-- Generated at: " . date("c");
        $header[] = "-- Source database: " . self::get_source_database_family();
        $header[] = "-- Output format: {$outputformat}";
        $header[] = "-- Scope: {$scope}";
        $header[] = "-- Total tables: " . count($tables);
        $header[] = "";

        if ($outputformat === "pgsql") {
            $header[] = "SET client_encoding = 'UTF8';";
            $header[] = "SET standard_conforming_strings = on;";
            $header[] = "";
        }

        gzwrite($handle, implode("\n", $header) . "\n");
    }

    /**
     * Function export_single_table
     *
     * @param resource $handle
     * @param string $table
     * @param string $outputformat
     * @return void
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function export_single_table($handle, string $table, string $outputformat): void {
        $columns = self::get_table_columns($table);
        $indexes = self::get_table_indexes($table);

        gzwrite($handle, "\n-- Table: {$table}\n");
        gzwrite($handle, self::build_drop_table_sql($table, $outputformat));
        gzwrite($handle, self::build_create_table_sql($table, $columns, $outputformat));

        foreach ($indexes as $index) {
            gzwrite($handle, self::build_index_sql($table, $index, $outputformat));
        }

        self::write_table_data($handle, $table, $columns, $outputformat);

        if ($outputformat === "pgsql") {
            foreach ($columns as $column) {
                if (!empty($column["auto_increment"])) {
                    gzwrite($handle, self::build_pgsql_sequence_reset_sql($table, $column["name"]));
                }
            }
        }
    }

    /**
     * Function write_table_data
     *
     * @param resource $handle
     * @param string $table
     * @param array $columns
     * @param string $outputformat
     * @return void
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function write_table_data($handle, string $table, array $columns, string $outputformat): void {
        global $DB;

        if (empty($columns)) {
            return;
        }

        $columnnames = array_column($columns, "name");
        $columnsql = implode(", ", array_map(static function(string $columnname): string {
            return self::quote_identifier($columnname, self::get_source_database_family());
        }, $columnnames));

        $prefixless = self::remove_table_prefix($table);
        $recordset = $DB->get_recordset_sql("SELECT {$columnsql} FROM {{$prefixless}}");

        $insertprefix = "INSERT INTO " . self::quote_identifier($table, $outputformat) .
            " (" . implode(", ", array_map(static function(string $columnname) use ($outputformat): string {
                return self::quote_identifier($columnname, $outputformat);
            }, $columnnames)) . ") VALUES\n";

        $batch = [];
        foreach ($recordset as $record) {
            $values = [];
            foreach ($columns as $column) {
                $name = $column["name"];
                $values[] = self::format_value_for_sql($record->{$name} ?? null, $column, $outputformat);
            }

            $batch[] = "(" . implode(", ", $values) . ")";
            if (count($batch) >= self::INSERT_BATCH_SIZE) {
                gzwrite($handle, $insertprefix . implode(",\n", $batch) . ";\n");
                $batch = [];
            }
        }
        $recordset->close();

        if (!empty($batch)) {
            gzwrite($handle, $insertprefix . implode(",\n", $batch) . ";\n");
        }
    }

    /**
     * Function build_drop_table_sql
     *
     * @param string $table
     * @param string $outputformat
     * @return string
     */
    private static function build_drop_table_sql(string $table, string $outputformat): string {
        return "DROP TABLE IF EXISTS " . self::quote_identifier($table, $outputformat) . ";\n";
    }

    /**
     * Function build_create_table_sql
     *
     * @param string $table
     * @param array $columns
     * @param string $outputformat
     * @return string
     */
    private static function build_create_table_sql(string $table, array $columns, string $outputformat): string {
        $lines = [];
        foreach ($columns as $column) {
            $line = "    " . self::quote_identifier($column["name"], $outputformat) . " " .
                self::build_column_definition($column, $outputformat);
            $lines[] = $line;
        }

        return "CREATE TABLE " . self::quote_identifier($table, $outputformat) . " (\n" .
            implode(",\n", $lines) . "\n);\n";
    }

    /**
     * Function build_index_sql
     *
     * @param string $table
     * @param array $index
     * @param string $outputformat
     * @return string
     */
    private static function build_index_sql(string $table, array $index, string $outputformat): string {
        $columns = implode(", ", array_map(static function(string $column) use ($outputformat): string {
            return self::quote_identifier($column, $outputformat);
        }, $index["columns"]));

        if ($index["isprimary"]) {
            return "ALTER TABLE " . self::quote_identifier($table, $outputformat) .
                " ADD PRIMARY KEY ({$columns});\n";
        }

        $name = self::quote_identifier($index["name"], $outputformat);
        $keyword = $index["isunique"] ? "CREATE UNIQUE INDEX" : "CREATE INDEX";

        return "{$keyword} {$name} ON " . self::quote_identifier($table, $outputformat) . " ({$columns});\n";
    }

    /**
     * Function build_pgsql_sequence_reset_sql
     *
     * @param string $table
     * @param string $column
     * @return string
     */
    private static function build_pgsql_sequence_reset_sql(string $table, string $column): string {
        $tableliteral = str_replace("'", "''", $table);
        $columnliteral = str_replace("'", "''", $column);
        $quotedtable = self::quote_identifier($table, "pgsql");
        $quotedcolumn = self::quote_identifier($column, "pgsql");

        return "SELECT pg_catalog.setval(pg_get_serial_sequence('{$tableliteral}', '{$columnliteral}'), " .
            "COALESCE((SELECT MAX({$quotedcolumn}) FROM {$quotedtable}), 1), " .
            "(SELECT MAX({$quotedcolumn}) IS NOT NULL FROM {$quotedtable}));\n";
    }

    /**
     * Function build_column_definition
     *
     * @param array $column
     * @param string $outputformat
     * @return string
     */
    private static function build_column_definition(array $column, string $outputformat): string {
        $sqltype = self::map_column_type($column, $outputformat);
        $parts = [$sqltype];

        if (empty($column["nullable"])) {
            $parts[] = "NOT NULL";
        }

        $defaultsql = self::build_default_clause($column, $outputformat);
        if ($defaultsql !== "") {
            $parts[] = $defaultsql;
        }

        return implode(" ", $parts);
    }

    /**
     * Function map_column_type
     *
     * @param array $column
     * @param string $outputformat
     * @return string
     */
    private static function map_column_type(array $column, string $outputformat): string {
        $type = $column["abstracttype"];
        $length = $column["length"];
        $precision = $column["precision"];
        $scale = $column["scale"];
        $autoincrement = !empty($column["auto_increment"]);

        if ($outputformat === "mysql") {
            switch ($type) {
                case "boolean":
                    return "TINYINT(1)";
                case "smallint":
                    return $autoincrement ? "SMALLINT AUTO_INCREMENT" : "SMALLINT";
                case "integer":
                    return $autoincrement ? "INT AUTO_INCREMENT" : "INT";
                case "bigint":
                    return $autoincrement ? "BIGINT AUTO_INCREMENT" : "BIGINT";
                case "decimal":
                    return "DECIMAL(" . ($precision ?: 10) . "," . ($scale ?: 0) . ")";
                case "float":
                    return "DOUBLE";
                case "char":
                    return "CHAR(" . ($length ?: 1) . ")";
                case "varchar":
                    return "VARCHAR(" . min($length ?: 255, 16383) . ")";
                case "text":
                    return "LONGTEXT";
                case "date":
                    return "DATE";
                case "time":
                    return "TIME";
                case "timestamp":
                    return "DATETIME";
                case "binary":
                    return "LONGBLOB";
                default:
                    return "LONGTEXT";
            }
        }

        switch ($type) {
            case "boolean":
                return "BOOLEAN";
            case "smallint":
                return $autoincrement ? "SMALLSERIAL" : "SMALLINT";
            case "integer":
                return $autoincrement ? "SERIAL" : "INTEGER";
            case "bigint":
                return $autoincrement ? "BIGSERIAL" : "BIGINT";
            case "decimal":
                return "NUMERIC(" . ($precision ?: 10) . "," . ($scale ?: 0) . ")";
            case "float":
                return "DOUBLE PRECISION";
            case "char":
                return "CHAR(" . ($length ?: 1) . ")";
            case "varchar":
                return "VARCHAR(" . min($length ?: 255, 10485760) . ")";
            case "text":
                return "TEXT";
            case "date":
                return "DATE";
            case "time":
                return "TIME";
            case "timestamp":
                return "TIMESTAMP WITHOUT TIME ZONE";
            case "binary":
                return "BYTEA";
            default:
                return "TEXT";
        }
    }

    /**
     * Function build_default_clause
     *
     * @param array $column
     * @param string $outputformat
     * @return string
     */
    private static function build_default_clause(array $column, string $outputformat): string {
        if ($column["default"] === null || !empty($column["auto_increment"])) {
            return "";
        }

        $default = self::normalize_default_expression((string) $column["default"]);
        if ($default === "") {
            return "DEFAULT ''";
        }

        $normalized = strtolower($default);
        if (in_array($normalized, ["current_timestamp", "current_timestamp()", "now()"], true)) {
            return $outputformat == "mysql" ? "DEFAULT CURRENT_TIMESTAMP" : "DEFAULT CURRENT_TIMESTAMP";
        }

        if ($normalized === "null") {
            return "DEFAULT NULL";
        }

        if ($column["abstracttype"] === "boolean") {
            if (in_array($normalized, ["1", "true", "t", "yes"], true)) {
                return $outputformat === "pgsql" ? "DEFAULT TRUE" : "DEFAULT 1";
            }

            if (in_array($normalized, ["0", "false", "f", "no"], true)) {
                return $outputformat === "pgsql" ? "DEFAULT FALSE" : "DEFAULT 0";
            }
        }

        if (in_array($column["abstracttype"], ["smallint", "integer", "bigint", "decimal", "float"], true) &&
            preg_match('/^-?[0-9]+(?:\.[0-9]+)?$/', $default)) {
            return "DEFAULT {$default}";
        }

        return "DEFAULT " . self::format_string_literal($default, $outputformat);
    }

    /**
     * Function normalize_default_expression
     *
     * @param string $default
     * @return string
     */
    private static function normalize_default_expression(string $default): string {
        $default = trim($default);
        if ($default === "") {
            return "";
        }

        while (preg_match('/^\((.*)\)$/s', $default, $matches)) {
            $default = trim($matches[1]);
        }

        if (preg_match("/^'(.*)'::[a-zA-Z0-9_ ]+$/s", $default, $matches)) {
            return str_replace("''", "'", $matches[1]);
        }

        if (preg_match('/^(-?[0-9]+(?:\.[0-9]+)?)::[a-zA-Z0-9_ ]+$/', $default, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^(true|false)::[a-zA-Z0-9_ ]+$/i', $default, $matches)) {
            return strtolower($matches[1]);
        }

        return $default;
    }

    /**
     * Function format_value_for_sql
     *
     * @param mixed $value
     * @param array $column
     * @param string $outputformat
     * @return string
     */
    private static function format_value_for_sql($value, array $column, string $outputformat): string {
        if ($value === null) {
            return "NULL";
        }

        switch ($column["abstracttype"]) {
            case "boolean":
                $bool = (bool) $value;
                if ($outputformat === "pgsql") {
                    return $bool ? "TRUE" : "FALSE";
                }

                return $bool ? "1" : "0";

            case "smallint":
            case "integer":
            case "bigint":
                return (string) (int) $value;

            case "decimal":
            case "float":
                return is_numeric($value) ? $value : "0";

            case "binary":
                return self::format_binary_literal($value, $outputformat);

            default:
                return self::format_string_literal($value, $outputformat);
        }
    }

    /**
     * Function format_binary_literal
     *
     * @param string $value
     * @param string $outputformat
     * @return string
     */
    private static function format_binary_literal(string $value, string $outputformat): string {
        $hex = bin2hex($value);
        if ($outputformat === "pgsql") {
            return "decode('{$hex}', 'hex')";
        }

        return "X'{$hex}'";
    }

    /**
     * Function format_string_literal
     *
     * @param string $value
     * @param string $outputformat
     * @return string
     */
    private static function format_string_literal(string $value, string $outputformat): string {
        if ($outputformat === "mysql") {
            $escaped = strtr($value, [
                "\\" => "\\\\",
                "\0" => "\\0",
                "\n" => "\\n",
                "\r" => "\\r",
                "\x1a" => "\\Z",
                "'" => "\\'",
            ]);

            return "'{$escaped}'";
        }

        return "'" . str_replace("'", "''", $value) . "'";
    }

    /**
     * Function list_database_tables
     *
     * @return array
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function list_database_tables(): array {
        global $CFG, $DB;

        $tables = [];
        if (self::get_source_database_family() === "mysql") {
            $records = $DB->get_records_sql(
                "SELECT TABLE_NAME AS tablename
                   FROM information_schema.TABLES
                  WHERE TABLE_SCHEMA = ?
                    AND TABLE_TYPE = 'BASE TABLE'
               ORDER BY TABLE_NAME",
                [$CFG->dbname]
            );
        } else {
            $records = $DB->get_records_sql(
                "SELECT tablename
                   FROM pg_catalog.pg_tables
                  WHERE schemaname = 'public'
               ORDER BY tablename"
            );
        }

        foreach ($records as $record) {
            $tables[] = $record->tablename;
        }

        return $tables;
    }

    /**
     * Function get_table_columns
     *
     * @param string $table
     * @return array
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function get_table_columns(string $table): array {
        global $CFG, $DB;

        $columns = [];
        if (self::get_source_database_family() === "mysql") {
            $records = $DB->get_records_sql(
                "SELECT COLUMN_NAME AS columnname,
                        DATA_TYPE AS datatype,
                        COLUMN_TYPE AS columntype,
                        IS_NULLABLE AS isnullable,
                        COLUMN_DEFAULT AS defaultvalue,
                        EXTRA AS extra,
                        CHARACTER_MAXIMUM_LENGTH AS charmaxlength,
                        NUMERIC_PRECISION AS numericprecision,
                        NUMERIC_SCALE AS numericscale
                   FROM information_schema.COLUMNS
                  WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
               ORDER BY ORDINAL_POSITION",
                [$CFG->dbname, $table]
            );
        } else {
            $records = $DB->get_records_sql(
                "SELECT column_name AS columnname,
                        data_type AS datatype,
                        udt_name AS columntype,
                        is_nullable AS isnullable,
                        column_default AS defaultvalue,
                        is_identity AS isidentity,
                        character_maximum_length AS charmaxlength,
                        numeric_precision AS numericprecision,
                        numeric_scale AS numericscale
                   FROM information_schema.columns
                  WHERE table_schema = 'public'
                    AND table_name = ?
               ORDER BY ordinal_position",
                [$table]
            );
        }

        foreach ($records as $record) {
            if (self::get_source_database_family() === "mysql") {
                $auto = strpos((string) $record->extra, "auto_increment") !== false;
            } else {
                $defaultvalue = $record->defaultvalue ?? "";
                $auto = $record->isidentity ?? "" == "YES" || strpos($defaultvalue, "nextval(") !== false;
            }

            $datatype = strtolower((string) $record->datatype);
            $columntype = strtolower((string) $record->columntype);

            $columns[] = [
                "name" => $record->columnname,
                "datatype" => $datatype,
                "columntype" => $columntype,
                "nullable" => ((string) $record->isnullable) === "YES",
                "default" => $record->defaultvalue,
                "auto_increment" => $auto,
                "unsigned" => strpos($columntype, "unsigned") !== false,
                "length" => $record->charmaxlength !== null ? (int) $record->charmaxlength : null,
                "precision" => $record->numericprecision !== null ? (int) $record->numericprecision : null,
                "scale" => $record->numericscale !== null ? (int) $record->numericscale : null,
                "abstracttype" => self::normalize_abstract_type($datatype, $columntype),
            ];
        }

        return $columns;
    }

    /**
     * Function get_table_indexes
     *
     * @param string $table
     * @return array
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private static function get_table_indexes(string $table): array {
        global $CFG, $DB;

        $indexes = [];
        if (self::get_source_database_family() === "mysql") {
            $recordset = $DB->get_recordset_sql(
                "SELECT INDEX_NAME AS indexname,
                        NON_UNIQUE AS nonunique,
                        SEQ_IN_INDEX AS seqinindex,
                        COLUMN_NAME AS columnname
                   FROM information_schema.STATISTICS
                  WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
               ORDER BY INDEX_NAME, SEQ_IN_INDEX",
                [$CFG->dbname, $table]
            );

            foreach ($recordset as $record) {
                $name = $record->indexname;
                if (!isset($indexes[$name])) {
                    $indexes[$name] = [
                        "name" => $name,
                        "isprimary" => $name === "PRIMARY",
                        "isunique" => ((int) $record->nonunique) === 0,
                        "columns" => [],
                    ];
                }

                $indexes[$name]["columns"][] = $record->columnname;
            }
        } else {
            $recordset = $DB->get_recordset_sql(
                "SELECT i.relname AS indexname,
                        ix.indisunique AS isunique,
                        ix.indisprimary AS isprimary,
                        a.attname AS columnname,
                        arr.idxpos AS idxpos
                   FROM pg_catalog.pg_class t
                   JOIN pg_catalog.pg_namespace n ON n.oid = t.relnamespace
                   JOIN pg_catalog.pg_index ix ON t.oid = ix.indrelid
                   JOIN pg_catalog.pg_class i ON i.oid = ix.indexrelid
                   JOIN LATERAL unnest(ix.indkey) WITH ORDINALITY AS arr(attnum, idxpos) ON true
                   JOIN pg_catalog.pg_attribute a ON a.attrelid = t.oid AND a.attnum = arr.attnum
                  WHERE n.nspname = 'public'
                    AND t.relname = ?
               ORDER BY i.relname, arr.idxpos",
                [$table]
            );

            foreach ($recordset as $record) {
                $name = $record->indexname;
                if (!isset($indexes[$name])) {
                    $indexes[$name] = [
                        "name" => $name,
                        "isprimary" => self::database_bool_to_php($record->isprimary),
                        "isunique" => self::database_bool_to_php($record->isunique),
                        "columns" => [],
                    ];
                }

                $indexes[$name]["columns"][] = $record->columnname;
            }
        }
        $recordset->close();

        return array_values($indexes);
    }

    /**
     * Function database_bool_to_php
     *
     * @param mixed $value
     * @return bool
     */
    private static function database_bool_to_php($value): bool {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower($value);
        return in_array($normalized, ["1", "t", "true", "y", "yes"], true);
    }

    /**
     * Function normalize_abstract_type
     *
     * @param string $datatype
     * @param string $columntype
     * @return string
     */
    private static function normalize_abstract_type(string $datatype, string $columntype): string {
        if ($datatype === "boolean" || $datatype === "bool" || $columntype === "bool") {
            return "boolean";
        }

        if ($datatype === "tinyint" && preg_match('/tinyint\(1\)/', $columntype)) {
            return "boolean";
        }

        if (in_array($datatype, ["smallint", "int2"], true)) {
            return "smallint";
        }

        if (in_array($datatype, ["integer", "int", "int4", "mediumint"], true)) {
            return "integer";
        }

        if (in_array($datatype, ["bigint", "int8"], true)) {
            return "bigint";
        }

        if (in_array($datatype, ["decimal", "numeric"], true)) {
            return "decimal";
        }

        if (in_array($datatype, ["float", "double", "double precision", "real"], true)) {
            return "float";
        }

        if (in_array($datatype, ["character", "char", "bpchar"], true)) {
            return "char";
        }

        if (in_array($datatype, ["varchar", "character varying"], true)) {
            return "varchar";
        }

        if (in_array($datatype, ["text", "tinytext", "mediumtext", "longtext", "json", "jsonb", "enum", "set"], true)) {
            return "text";
        }

        if ($datatype === "date") {
            return "date";
        }

        if (strpos($datatype, "timestamp") !== false || $datatype === "datetime") {
            return "timestamp";
        }

        if (strpos($datatype, "time") !== false) {
            return "time";
        }

        if (in_array($datatype, ["blob", "tinyblob", "mediumblob", "longblob", "bytea", "binary", "varbinary"], true)) {
            return "binary";
        }

        return "text";
    }

    /**
     * Function normalize_output_format
     *
     * @param string $outputformat
     * @return string
     * @throws \moodle_exception
     */
    private static function normalize_output_format(string $outputformat): string {
        $outputformat = strtolower(trim($outputformat));
        if (!in_array($outputformat, ["mysql", "pgsql"], true)) {
            throw new moodle_exception("invalidoutputformat", "koperedashboard_backup", "", $outputformat);
        }

        return $outputformat;
    }

    /**
     * Function get_source_database_family
     *
     * @return string
     * @throws \moodle_exception
     */
    private static function get_source_database_family(): string {
        global $CFG;

        if ($CFG->dbtype === "mysqli" || $CFG->dbtype === "mariadb") {
            return "mysql";
        }

        if ($CFG->dbtype === "pgsql") {
            return "pgsql";
        }

        throw new moodle_exception("unsupporteddbtype", "koperedashboard_backup", "", $CFG->dbtype);
    }

    /**
     * Function is_log_table
     *
     * @param string $table
     * @return bool
     */
    private static function is_log_table(string $table): bool {
        global $CFG;

        $prefix = preg_quote($CFG->prefix, "/");

        $test1 = preg_match(
            "/^(?:{$prefix})?(?:log|log_display|task_log)$/i",
            $table
        );
        $test2 = preg_match(
            "/(?:_log|_logs|logstore_)/i",
            $table
        );
        return $test1 || $test2;
    }

    /**
     * Function quote_identifier
     *
     * @param string $identifier
     * @param string $format
     * @return string
     */
    private static function quote_identifier(string $identifier, string $format): string {
        if ($format === "pgsql") {
            return '"' . str_replace('"', '""', $identifier) . '"';
        }

        return "`" . str_replace("`", "``", $identifier) . "`"; // phpcs:disable
    }

    /**
     * Function remove_table_prefix
     *
     * @param string $table
     * @return string
     */
    private static function remove_table_prefix(string $table): string {
        global $CFG;

        if (strpos($table, $CFG->prefix) === 0) {
            return substr($table, strlen($CFG->prefix));
        }

        return $table;
    }

    /**
     * Function write_manifest
     *
     * @param string $filepath
     * @param string $sourcetype
     * @param string $outputformat
     * @param array $maintables
     * @param array $logtables
     * @return void
     * @throws \moodle_exception
     */
    private static function write_manifest(
        string $filepath,
        string $sourcetype,
        string $outputformat,
        array $maintables,
        array $logtables
    ): void {
        $content = [];
        $content[] = "Kopere Dashboard database export bundle";
        $content[] = "Generated at: " . date("c");
        $content[] = "Source database: {$sourcetype}";
        $content[] = "Output format: {$outputformat}";
        $content[] = "Main tables: " . count($maintables);
        $content[] = "Log tables: " . count($logtables);
        $content[] = "";
        $content[] = "Files:";
        $content[] = "- database-main.sql.gz";
        if (!empty($logtables)) {
            $content[] = "- database-logs.sql.gz";
        }
        $content[] = "";
        $content[] = "Log tables:";
        foreach ($logtables as $table) {
            $content[] = "- {$table}";
        }

        if (file_put_contents($filepath, implode("\n", $content) . "\n") === false) {
            throw new moodle_exception("cannotopenexportfile", "koperedashboard_backup", "", $filepath);
        }
    }

    /**
     * Function create_directory_zip
     *
     * @param string $zipfile
     * @param string $source
     * @param string $rootname
     * @param array $extradirs
     * @return void
     * @throws \moodle_exception
     */
    private static function create_directory_zip(
        string $zipfile,
        string $source,
        string $rootname,
        array $extradirs = []
    ): void {
        $zip = new ZipArchive();
        if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new moodle_exception("zipcreatefailed", "koperedashboard_backup");
        }

        self::add_directory_to_zip($zip, $source, $rootname);
        foreach ($extradirs as $zippath => $dirpath) {
            if (is_dir($dirpath)) {
                self::add_directory_to_zip($zip, $dirpath, $zippath);
            }
        }

        $zip->close();
    }

    /**
     * Function add_directory_to_zip
     *
     * @param ZipArchive $zip
     * @param string $source
     * @param string $rootname
     * @return void
     */
    private static function add_directory_to_zip(ZipArchive $zip, string $source, string $rootname): void {
        $source = rtrim($source, "/");
        $rootname = trim($rootname, "/");

        if ($rootname !== "") {
            $zip->addEmptyDir($rootname);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $pathname = $item->getPathname();
            $relativepath = ltrim(substr($pathname, strlen($source)), "/");
            $relativepath = str_replace(DIRECTORY_SEPARATOR, "/", $relativepath);
            $zippath = $rootname === "" ? $relativepath : "{$rootname}/{$relativepath}";

            if ($item->isDir()) {
                $zip->addEmptyDir($zippath);
                continue;
            }

            $zip->addFile($pathname, $zippath);
        }
    }

    /**
     * Function create_zip_bundle
     *
     * @param string $zipfile
     * @param array $files
     * @return void
     * @throws \moodle_exception
     */
    private static function create_zip_bundle(string $zipfile, array $files): void {
        $zip = new ZipArchive();
        if ($zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new moodle_exception("zipcreatefailed", "koperedashboard_backup");
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        $zip->close();
    }

    /**
     * Function delete_path
     *
     * @param string $path
     * @return void
     */
    private static function delete_path(string $path): void {
        if (!file_exists($path)) {
            return;
        }

        if (is_file($path)) {
            @unlink($path);
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }

        @rmdir($path);
    }

    /**
     * Function run_shell_command
     *
     * @param string $command
     * @param array $environment
     * @return void
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    private static function run_shell_command(string $command, array $environment = []): void {
        $process = proc_open(
            ["/bin/sh", "-c", $command],
            [
                1 => ["pipe", "w"],
                2 => ["pipe", "w"],
            ],
            $pipes,
            null,
            $environment
        );

        if (!is_resource($process)) {
            throw new moodle_exception("processstartfailed", "koperedashboard_backup");
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitcode = proc_close($process);
        if ($exitcode !== 0) {
            $details = trim($stderr ?: $stdout);
            if ($details === "") {
                $details = get_string("unknownprocesserror", "koperedashboard_backup");
            }

            throw new moodle_exception("processfailed", "koperedashboard_backup", "", $details);
        }
    }

    /**
     * Function command_exists
     *
     * @param string $command
     * @return bool
     */
    private static function command_exists(string $command): bool {
        $result = shell_exec("command -v " . escapeshellarg($command) . " 2>/dev/null");
        return trim($result) !== "";
    }

    /**
     * Function detect_type
     *
     * @param string $filename
     * @return string
     */
    private static function detect_type(string $filename): string {
        if (strpos($filename, self::TYPE_MOODLEDATA . "-") === 0) {
            return self::TYPE_MOODLEDATA;
        }

        if (strpos($filename, "friendly_installation-") === 0) {
            return "friendly_installation";
        }

        return self::TYPE_DATABASE;
    }
}
