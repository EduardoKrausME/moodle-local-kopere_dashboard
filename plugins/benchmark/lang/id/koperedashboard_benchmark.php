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

$string['back_to_benchmark'] = 'Kembali ke benchmark';
$string['cap_run'] = 'Jalankan benchmark';
$string['cap_run_desc'] = 'Dapat menjalankan pengujian benchmark sintetis di Kopere Dashboard.';
$string['cap_view'] = 'Lihat benchmark';
$string['cap_view_desc'] = 'Dapat mengakses area benchmark dan melihat rekomendasi performa.';
$string['check_backup_auto_active'] = 'Backup otomatis';
$string['check_cachejs'] = 'Cache JavaScript';
$string['check_debug'] = 'Level debug';
$string['check_debugdisplay'] = 'Tampilkan pesan debug';
$string['check_themedesignermode'] = 'Mode desainer tema';
$string['debug_value'] = 'Aktif ({$a})';
$string['environment_db'] = 'Database';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Detail OPcache';
$string['environment_opcache_warning'] = 'Biarkan OPcache aktif di produksi. OPcache menyimpan skrip PHP terkompilasi di memori, mengurangi penggunaan CPU, dan meningkatkan waktu respons.';
$string['environment_os'] = 'Sistem operasi';
$string['environment_os_windows_warning'] = 'Windows tidak direkomendasikan untuk lingkungan produksi Moodle. Pilih Linux untuk kompatibilitas, stabilitas, dan performa yang lebih baik. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Dokumentasi Moodle: paket instalasi lengkap untuk Windows tidak direkomendasikan untuk produksi</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Lingkungan';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Biarkan X-Sendfile aktif di produksi. Ini memungkinkan server web mengirim file secara langsung, mengurangi penggunaan memori PHP, dan meningkatkan unduhan file besar.';
$string['execute_title'] = 'Jalankan benchmark';
$string['help_recommendations'] = 'Rekomendasi ini membantu menafsirkan apakah lingkungan sudah dikonfigurasi untuk produksi. Rekomendasi ini tidak menggantikan analisis mendetail terhadap database, Redis, cron, disk, atau cache balik.';
$string['iterations'] = 'Iterasi';
$string['label_disabled'] = 'Nonaktif';
$string['label_enabled'] = 'Aktif';
$string['manage_intro'] = 'Jalankan serangkaian pengujian sintetis singkat untuk mendapatkan gambaran cepat tentang performa umum server Moodle. Pengujian mengukur pembacaan database sederhana, putaran baca/tulis disk, JSON, hash, dan pemrosesan string.';
$string['manage_warning'] = 'Hasil bersifat komparatif. Idealnya, selalu jalankan pada server yang sama dan bandingkan sebelum/sesudah perubahan pada PHP, database, disk, cache, Redis, Nginx, atau plugin.';
$string['menu_desc'] = 'Mengukur waktu database, disk, dan CPU dengan rekomendasi produksi cepat.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>memori: {$a->memory} MB<br>maks. file: {$a->maxfiles}<br>validasi timestamp: {$a->timestamps}<br>freq. validasi ulang: {$a->revalidate}';
$string['peakmemory'] = 'Memori puncak';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Hindari backup otomatis berjalan pada jam sibuk. Pilih jendela waktu di luar jam sibuk.';
$string['recommend_cachejs'] = 'Di produksi, biarkan cache JavaScript aktif untuk mengurangi pemrosesan dan transfer.';
$string['recommend_debug'] = 'Debug aktif meningkatkan biaya pemrosesan dan noise. Biarkan nonaktif di produksi.';
$string['recommend_debugdisplay'] = 'Menampilkan pesan debug langsung di layar harus dinonaktifkan di produksi.';
$string['recommend_themedesignermode'] = 'Mode desainer tema harus dinonaktifkan di produksi untuk menghindari kompilasi ulang CSS dan penurunan performa.';
$string['recommendation'] = 'Rekomendasi';
$string['recommendations_title'] = 'Pemeriksaan konfigurasi cepat';
$string['result_status'] = 'Status';
$string['results_title'] = 'Hasil pengujian';
$string['run_benchmark'] = 'Jalankan benchmark';
$string['status_attention'] = 'Perhatian';
$string['status_fast'] = 'Cepat';
$string['status_slow'] = 'Lambat';
$string['summary_title'] = 'Ringkasan';
$string['test_db_desc'] = 'Pembacaan berulang dari catatan konfigurasi kecil di database.';
$string['test_db_name'] = 'Database';
$string['test_files_desc'] = 'Menulis, membaca, dan menghapus file sementara lokal.';
$string['test_files_name'] = 'Sistem file';
$string['test_hash_desc'] = 'Putaran SHA-256 berulang untuk mengukur performa mentah CPU.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Encoding dan decoding struktur JSON berukuran sedang.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Pengujian';
$string['test_string_desc'] = 'Pembersihan dan analisis sederhana terhadap konten mirip HTML.';
$string['test_string_name'] = 'String / HTML';
$string['time_elapsed'] = 'Waktu';
$string['total_time'] = 'Total waktu';
$string['value'] = 'Nilai';
$string['xsendfile_value'] = 'Aktif ({$a->header}<br>alias: {$a->aliases})';
