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

$string['back_to_benchmark'] = 'Back to benchmark';
$string['cap_run'] = 'Run benchmark';
$string['cap_run_desc'] = 'Can run synthetic benchmark tests in Kopere Dashboard.';
$string['cap_view'] = 'View benchmark';
$string['cap_view_desc'] = 'Can access the benchmark area and view performance recommendations.';
$string['check_backup_auto_active'] = 'Automatic backup';
$string['check_cachejs'] = 'JavaScript cache';
$string['check_debug'] = 'Debug level';
$string['check_debugdisplay'] = 'Display debug messages';
$string['check_themedesignermode'] = 'Theme designer mode';
$string['debug_value'] = 'Enabled ({$a})';
$string['environment_db'] = 'Database';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'OPcache details';
$string['environment_opcache_warning'] = 'Keep OPcache enabled in production. It stores compiled PHP scripts in memory, reduces CPU usage and improves response time.';
$string['environment_os'] = 'Operating system';
$string['environment_os_windows_warning'] = 'Windows is not recommended for Moodle production environments. Prefer Linux for better compatibility, stability and performance. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Moodle docs: Windows complete install package is not recommended for production</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Environment';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Keep X-Sendfile enabled in production. It lets the web server deliver files directly, reducing PHP memory usage and improving large file downloads.';
$string['execute_title'] = 'Run benchmark';
$string['help_recommendations'] = 'These recommendations help interpret whether the environment is configured for production. They do not replace a detailed analysis of the database, Redis, cron, disks or reverse cache.';
$string['iterations'] = 'Iterations';
$string['label_disabled'] = 'Disabled';
$string['label_enabled'] = 'Enabled';
$string['manage_intro'] = 'Run a short set of synthetic tests to get a quick overview of the Moodle server’s general performance. The tests measure simple database reads, disk roundtrip, JSON, hash and string processing.';
$string['manage_warning'] = 'The results are comparative. Ideally, always run them on the same server and compare before/after changes to PHP, database, disk, cache, Redis, Nginx or plugins.';
$string['menu_desc'] = 'Measures database, disk and CPU time with quick production recommendations.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>memory: {$a->memory} MB<br>max files: {$a->maxfiles}<br>validate timestamps: {$a->timestamps}<br>revalidate freq: {$a->revalidate}';
$string['peakmemory'] = 'Peak memory';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Avoid automatic backups running during peak hours. Prefer off-hours windows.';
$string['recommend_cachejs'] = 'In production, keep JavaScript cache enabled to reduce processing and transfer.';
$string['recommend_debug'] = 'Active debugging increases processing cost and noise. Keep it disabled in production.';
$string['recommend_debugdisplay'] = 'Displaying debug messages directly on screen should be disabled in production.';
$string['recommend_themedesignermode'] = 'Theme designer mode should be disabled in production to avoid CSS recompilation and performance drops.';
$string['recommendation'] = 'Recommendation';
$string['recommendations_title'] = 'Quick configuration checks';
$string['result_status'] = 'Status';
$string['results_title'] = 'Test results';
$string['run_benchmark'] = 'Run benchmark';
$string['status_attention'] = 'Attention';
$string['status_fast'] = 'Fast';
$string['status_slow'] = 'Slow';
$string['summary_title'] = 'Summary';
$string['test_db_desc'] = 'Repeated reads of small configuration records from the database.';
$string['test_db_name'] = 'Database';
$string['test_files_desc'] = 'Writing, reading and removing a local temporary file.';
$string['test_files_name'] = 'File system';
$string['test_hash_desc'] = 'Repeated SHA-256 rounds to measure raw CPU performance.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Encoding and decoding medium-sized JSON structures.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Test';
$string['test_string_desc'] = 'Simple cleanup and analysis of HTML-like content.';
$string['test_string_name'] = 'Strings / HTML';
$string['time_elapsed'] = 'Time';
$string['total_time'] = 'Total time';
$string['value'] = 'Value';
$string['xsendfile_value'] = 'Enabled ({$a->header}<br>aliases: {$a->aliases})';
