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
 * benchmark_service.php
 *
 * @package   koperedashboard_benchmark
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_benchmark\service;

/**
 * Class benchmark_service
 */
class benchmark_service {
    /** @var int */
    private const STATUS_OK = 1;

    /** @var int */
    private const STATUS_WARN = 2;

    /** @var int */
    private const STATUS_SLOW = 3;

    /**
     * Return environment information displayed in the benchmark pages.
     *
     * @return array
     */
    public static function get_environment(): array {
        global $CFG, $DB;

        $opcacheenabled = self::is_ini_flag_enabled("opcache.enable")
            || extension_loaded("Zend OPcache")
            || extension_loaded("opcache");

        $xsendfileenabled = trim((string) ($CFG->xsendfile ?? "")) !== "";
        $iswindows = strtoupper(PHP_OS_FAMILY) === "WINDOWS";

        return [
            self::build_environment_row(
                get_string("environment_moodle", "koperedashboard_benchmark"),
                s($CFG->release ?? (string) ($CFG->version ?? ""))
            ),
            self::build_environment_row(
                get_string("environment_php", "koperedashboard_benchmark"),
                s(PHP_VERSION)
            ),
            self::build_environment_row(
                get_string("environment_db", "koperedashboard_benchmark"),
                s($CFG->dbtype ?? get_class($DB))
            ),
            self::build_environment_row(
                get_string("environment_os", "koperedashboard_benchmark"),
                s(PHP_OS_FAMILY),
                $iswindows,
                $iswindows ? get_string("environment_os_windows_warning", "koperedashboard_benchmark") : ""
            ),
            self::build_environment_row(
                get_string("environment_memory", "koperedashboard_benchmark"),
                s((string) ini_get("memory_limit"))
            ),
            self::build_environment_row(
                get_string("environment_xsendfile", "koperedashboard_benchmark"),
                self::get_xsendfile_value(),
                !$xsendfileenabled,
                !$xsendfileenabled ? get_string("environment_xsendfile_warning", "koperedashboard_benchmark") : ""
            ),
            self::build_environment_row(
                get_string("environment_opcache", "koperedashboard_benchmark"),
                $opcacheenabled
                    ? get_string("label_enabled", "koperedashboard_benchmark")
                    : get_string("label_disabled", "koperedashboard_benchmark"),
                !$opcacheenabled,
                !$opcacheenabled ? get_string("environment_opcache_warning", "koperedashboard_benchmark") : ""
            ),
            self::build_environment_row(
                get_string("environment_opcache_details", "koperedashboard_benchmark"),
                self::get_opcache_details_value()
            ),
        ];
    }

    /**
     * Build one environment row for the template.
     *
     * @param string $label
     * @param string $value
     * @param bool $highlight
     * @param string $message
     * @return array
     */
    private static function build_environment_row(
        string $label,
        string $value,
        bool $highlight = false,
        string $message = ""
    ): array {
        return [
            "label" => $label,
            "value" => $value,
            "valueclass" => $highlight ? "text-danger fw-bold" : "",
            "hasmessage" => $message !== "",
            "message" => $message,
            "messageclass" => $highlight ? "text-danger small d-block mt-1" : "text-muted small d-block mt-1",
        ];
    }

    /**
     * Return production recommendations related to performance.
     *
     * @return array
     */
    public static function get_recommendations(): array {
        global $CFG;

        $backupautoactive = get_config("backup", "backup_auto_active");
        $debuglevel = (int) ($CFG->debug ?? 0);

        return [
            self::build_check(
                get_string("check_themedesignermode", "koperedashboard_benchmark"),
                !empty($CFG->themedesignermode),
                get_string("recommend_themedesignermode", "koperedashboard_benchmark"),
                true
            ),
            self::build_check(
                get_string("check_cachejs", "koperedashboard_benchmark"),
                empty($CFG->cachejs),
                get_string("recommend_cachejs", "koperedashboard_benchmark"),
                true
            ),
            self::build_check(
                get_string("check_debug", "koperedashboard_benchmark"),
                $debuglevel !== 0,
                get_string("recommend_debug", "koperedashboard_benchmark"),
                true,
                self::format_debug_label($debuglevel)
            ),
            self::build_check(
                get_string("check_debugdisplay", "koperedashboard_benchmark"),
                !empty($CFG->debugdisplay),
                get_string("recommend_debugdisplay", "koperedashboard_benchmark"),
                true
            ),
            self::build_check(
                get_string("check_backup_auto_active", "koperedashboard_benchmark"),
                !empty($backupautoactive),
                get_string("recommend_backup_auto_active", "koperedashboard_benchmark"),
                true
            ),
        ];
    }

    /**
     * Execute all benchmark tests.
     *
     * @return array
     */
    public static function run_all(): array {
        $tests = self::get_test_definitions();
        $rows = [];
        $totalseconds = 0.0;

        foreach ($tests as $test) {
            $started = microtime(true);
            call_user_func($test["callback"]);
            $seconds = microtime(true) - $started;
            $totalseconds += $seconds;

            $status = self::score($seconds, $test["ok"], $test["warn"]);
            $rows[] = [
                "name" => $test["name"],
                "description" => $test["description"],
                "iterations" => $test["iterations"],
                "seconds" => self::format_seconds($seconds),
                "statuslabel" => self::status_label($status),
                "statusclass" => self::status_class($status),
            ];
        }

        $totalstatus = self::score($totalseconds, 1.50, 3.00);

        return [
            "summary" => [
                "totalseconds" => self::format_seconds($totalseconds),
                "statuslabel" => self::status_label($totalstatus),
                "statusclass" => self::status_class($totalstatus),
                "peakmemory" => format_float(memory_get_peak_usage(true) / 1048576, 2),
            ],
            "results" => $rows,
            "environment" => self::get_environment(),
            "checks" => self::get_recommendations(),
        ];
    }

    /**
     * Return the available benchmark test definitions.
     *
     * @return array
     */
    private static function get_test_definitions(): array {
        return [
            [
                "name" => get_string("test_db_name", "koperedashboard_benchmark"),
                "description" => get_string("test_db_desc", "koperedashboard_benchmark"),
                "iterations" => 120,
                "ok" => 0.20,
                "warn" => 0.60,
                "callback" => [self::class, "run_db_reads"],
            ],
            [
                "name" => get_string("test_files_name", "koperedashboard_benchmark"),
                "description" => get_string("test_files_desc", "koperedashboard_benchmark"),
                "iterations" => 30,
                "ok" => 0.08,
                "warn" => 0.25,
                "callback" => [self::class, "run_filesystem_roundtrip"],
            ],
            [
                "name" => get_string("test_json_name", "koperedashboard_benchmark"),
                "description" => get_string("test_json_desc", "koperedashboard_benchmark"),
                "iterations" => 800,
                "ok" => 0.10,
                "warn" => 0.30,
                "callback" => [self::class, "run_json_roundtrip"],
            ],
            [
                "name" => get_string("test_hash_name", "koperedashboard_benchmark"),
                "description" => get_string("test_hash_desc", "koperedashboard_benchmark"),
                "iterations" => 5000,
                "ok" => 0.15,
                "warn" => 0.40,
                "callback" => [self::class, "run_hash_rounds"],
            ],
            [
                "name" => get_string("test_string_name", "koperedashboard_benchmark"),
                "description" => get_string("test_string_desc", "koperedashboard_benchmark"),
                "iterations" => 400,
                "ok" => 0.08,
                "warn" => 0.25,
                "callback" => [self::class, "run_string_processing"],
            ],
        ];
    }

    /**
     * Benchmark repeated small database reads.
     *
     * @return void
     */
    private static function run_db_reads(): void {
        global $DB;

        for ($i = 0; $i < 120; $i++) {
            $DB->get_records_select("config", "name <> :name", ["name" => ""], "name ASC", "id,name,value", 0, 5);
        }
    }

    /**
     * Benchmark write/read/delete roundtrips on a temporary file.
     *
     * @return void
     */
    private static function run_filesystem_roundtrip(): void {
        $payload = str_repeat("Kopere Dashboard benchmark payload.", 2048);
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "koperedashboard_benchmark_" . uniqid("", true) . ".txt";

        for ($i = 0; $i < 30; $i++) {
            file_put_contents($path, $payload);
            file_get_contents($path);
        }

        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Benchmark JSON encoding and decoding.
     *
     * @return void
     */
    private static function run_json_roundtrip(): void {
        $payload = [
            "course" => "Kopere Dashboard benchmark",
            "users" => range(1, 30),
            "filters" => [
                "active" => true,
                "category" => "tools",
                "tags" => ["performance", "benchmark", "moodle"],
            ],
            "rows" => array_fill(0, 8, [
                "fullname" => "Student name",
                "progress" => 73,
                "lastaccess" => time(),
            ]),
        ];

        for ($i = 0; $i < 800; $i++) {
            $json = json_encode($payload);
            json_decode($json, true);
        }
    }

    /**
     * Benchmark repeated SHA-256 hashing.
     *
     * @return void
     */
    private static function run_hash_rounds(): void {
        $payload = str_repeat("Kopere DashboardHashSeed", 128);

        for ($i = 0; $i < 5000; $i++) {
            hash("sha256", $payload . $i);
        }
    }

    /**
     * Benchmark common string and HTML-like processing.
     *
     * @return void
     */
    private static function run_string_processing(): void {
        $html = str_repeat("<p><strong>Kopere Dashboard</strong> benchmark <a href=\"#\">row</a></p>", 60);

        for ($i = 0; $i < 400; $i++) {
            $text = strip_tags($html);
            $text = preg_replace("/\s+/", " ", $text ?? "");
            substr_count($text, "benchmark");
        }
    }

    /**
     * Return the xsendfile label.
     *
     * @return string
     */
    private static function get_xsendfile_value(): string {
        global $CFG;

        $header = trim((string) ($CFG->xsendfile ?? ""));
        if ($header === "") {
            return get_string("label_disabled", "koperedashboard_benchmark");
        }

        $aliases = $CFG->xsendfilealiases ?? [];
        $aliascount = is_array($aliases) ? count($aliases) : 0;

        return get_string(
            "xsendfile_value",
            "koperedashboard_benchmark",
            (object) [
                "header" => s($header),
                "aliases" => $aliascount,
            ]
        );
    }

    /**
     * Return OPcache details formatted for display.
     *
     * @return string
     */
    private static function get_opcache_details_value(): string {
        return get_string(
            "opcache_details_value",
            "koperedashboard_benchmark",
            (object) [
                "enablecli" => self::format_ini_flag_label("opcache.enable_cli"),
                "memory" => s(self::get_ini_value("opcache.memory_consumption", "-")),
                "maxfiles" => s(self::get_ini_value("opcache.max_accelerated_files", "-")),
                "timestamps" => self::format_ini_flag_label("opcache.validate_timestamps"),
                "revalidate" => s(self::get_ini_value("opcache.revalidate_freq", "-")),
            ]
        );
    }

    /**
     * Return one ini value as string.
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    private static function get_ini_value(string $name, string $default = ""): string {
        $value = ini_get($name);
        if ($value === false || $value === "") {
            return $default;
        }

        return (string) $value;
    }

    /**
     * Return whether one ini flag is enabled.
     *
     * @param string $name
     * @return bool
     */
    private static function is_ini_flag_enabled(string $name): bool {
        $value = ini_get($name);
        if ($value === false || $value === "") {
            return false;
        }

        $normalized = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($normalized !== null) {
            return $normalized;
        }

        return (int) $value === 1;
    }

    /**
     * Return enabled or disabled label for one ini flag.
     *
     * @param string $name
     * @return string
     */
    private static function format_ini_flag_label(string $name): string {
        return self::is_ini_flag_enabled($name)
            ? get_string("label_enabled", "koperedashboard_benchmark")
            : get_string("label_disabled", "koperedashboard_benchmark");
    }

    /**
     * Return score for a given elapsed time.
     *
     * @param float $seconds
     * @param float $oklimit
     * @param float $warnlimit
     * @return int
     */
    private static function score(float $seconds, float $oklimit, float $warnlimit): int {
        if ($seconds <= $oklimit) {
            return self::STATUS_OK;
        }

        if ($seconds <= $warnlimit) {
            return self::STATUS_WARN;
        }

        return self::STATUS_SLOW;
    }

    /**
     * Build recommendation row.
     *
     * @param string $name
     * @param bool $problem
     * @param string $recommendation
     * @param bool $invertvalue
     * @param string|null $customvalue
     * @return array
     */
    private static function build_check(
        string $name,
        bool $problem,
        string $recommendation,
        bool $invertvalue = false,
        ?string $customvalue = null
    ): array {
        $status = $problem ? self::STATUS_WARN : self::STATUS_OK;
        $value = $customvalue;

        if ($value === null) {
            $effective = $invertvalue ? !$problem : $problem;
            $value = $effective
                ? get_string("label_enabled", "koperedashboard_benchmark")
                : get_string("label_disabled", "koperedashboard_benchmark");
        }

        return [
            "name" => $name,
            "value" => $value,
            "recommendation" => $recommendation,
            "statuslabel" => self::status_label($status),
            "statusclass" => self::status_class($status),
        ];
    }

    /**
     * Format elapsed seconds.
     *
     * @param float $seconds
     * @return string
     */
    private static function format_seconds(float $seconds): string {
        return format_float($seconds, 4) . "s";
    }

    /**
     * Return status label.
     *
     * @param int $status
     * @return string
     */
    private static function status_label(int $status): string {
        if ($status === self::STATUS_OK) {
            return get_string("status_fast", "koperedashboard_benchmark");
        }

        if ($status === self::STATUS_WARN) {
            return get_string("status_attention", "koperedashboard_benchmark");
        }

        return get_string("status_slow", "koperedashboard_benchmark");
    }

    /**
     * Return CSS class suffix for badges.
     *
     * @param int $status
     * @return string
     */
    private static function status_class(int $status): string {
        if ($status === self::STATUS_OK) {
            return "success";
        }

        if ($status === self::STATUS_WARN) {
            return "warning";
        }

        return "danger";
    }

    /**
     * Return human label for the current debug level.
     *
     * @param int $debuglevel
     * @return string
     */
    private static function format_debug_label(int $debuglevel): string {
        if ($debuglevel === 0) {
            return get_string("label_disabled", "koperedashboard_benchmark");
        }

        return get_string("debug_value", "koperedashboard_benchmark", $debuglevel);
    }
}
