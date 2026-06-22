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

$string['action_download'] = 'Unduh';
$string['action_generate_database'] = 'Ekspor basis data';
$string['action_generate_moodledata'] = 'Buat cadangan moodledata';
$string['cannotopenexportfile'] = 'Tidak dapat membuka file ekspor: {$a}';
$string['cap_generate'] = 'Buat cadangan';
$string['cap_generate_desc'] = 'Mengizinkan pengguna membuat file cadangan moodledata dan cadangan basis data.';
$string['cap_view'] = 'Lihat pusat cadangan';
$string['cap_view_desc'] = 'Mengizinkan pengguna mengakses pusat cadangan dan mengunduh file yang dihasilkan.';
$string['col_actions'] = 'Tindakan';
$string['col_created'] = 'Dibuat pada';
$string['col_file'] = 'File';
$string['col_size'] = 'Ukuran';
$string['col_type'] = 'Jenis';
$string['commandnotfound'] = 'Perintah sistem yang diperlukan tidak ditemukan: {$a}.';
$string['current_source_label'] = 'Basis data saat ini:';
$string['database_desc'] = 'Mengekspor struktur dan data basis data dalam PHP, memungkinkan Anda memilih format keluaran dan secara opsional memisahkan log ke file tersendiri.';
$string['database_success'] = 'Ekspor basis data berhasil dibuat: {$a}';
$string['database_title'] = 'Ekspor basis data';
$string['emptyfiles'] = 'Belum ada file cadangan yang dibuat.';
$string['exportscope_full'] = 'Basis data lengkap';
$string['exportscope_logs'] = 'Log saja';
$string['exportscope_main'] = 'Basis data tanpa log';
$string['filenotfound'] = 'File cadangan tidak ditemukan: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['friendly_installation_alternative_not_desc'] = 'Instal dan konfigurasi <a href="https://moodle.org/plugins/local_alternative_file_system" target="_blank">Alternative File System</a> untuk mengoptimalkan waktu migrasi, menyimpan file Moodle di penyimpanan jarak jauh, mengurangi beban disk lokal, memudahkan lingkungan cluster, meningkatkan ketahanan, dan dapat memakai pengiriman CDN bila sesuai. Tanpanya, ekspor ini akan menyertakan file lokal moodledata ke dalam ZIP yang dibuat.';
$string['friendly_installation_alternative_not_title'] = 'Alternative File System belum diinstal atau belum dikonfigurasi';
$string['friendly_installation_alternative_ok_desc'] = 'Periksa di <a href="{$a}" target="_blank">sistem file alternatif</a> apakah semua file sudah berada di penyimpanan remote sebelum menjalankan pemulihan.';
$string['friendly_installation_alternative_ok_title'] = 'Alternative File System telah diinstal';
$string['friendly_installation_desc'] = 'Mengekspor database dalam format yang kompatibel dengan <a href="https://github.com/EduardoKrausME/moodle_friendly_installation" target="_blank">Penginstal perangkat lunak Moodle™</a> dan MoodleData.';
$string['friendly_installation_generate'] = 'Ekspor';
$string['friendly_installation_success'] = 'Ekspor berhasil dibuat!';
$string['friendly_installation_title'] = 'Ekspor ke Penginstal perangkat lunak Moodle™';
$string['gzipnotavailable'] = 'Ekstensi PHP zlib/gzip tidak tersedia di server ini.';
$string['history_title'] = 'File yang dihasilkan';
$string['home_kpi_empty_subtitle'] = 'Tidak ada cadangan yang dibuat';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'File terbaru: {$a}';
$string['home_kpi_title'] = 'Cadangan terbaru';
$string['invalidaction'] = 'Tindakan yang diminta tidak valid.';
$string['invalidfilename'] = 'Nama file yang ditentukan tidak valid.';
$string['invalidoutputformat'] = 'Format keluaran yang ditentukan tidak valid: {$a}';
$string['menu_desc'] = 'Pembuatan cadangan moodledata secara manual dan ekspor basis data';
$string['menu_title'] = 'Cadangan';
$string['moodledata_desc'] = 'Membuat paket lengkap folder moodledata, hanya mengecualikan folder tempat cadangan itu sendiri disimpan.';
$string['moodledata_success'] = 'Cadangan moodledata berhasil dibuat: {$a}';
$string['moodledata_title'] = 'Cadangan moodledata';
$string['notablesfound'] = 'Tidak ada tabel yang ditemukan untuk diekspor.';
$string['outputformat_desc'] = 'Anda dapat mengekspor ke format basis data saat ini atau mengonversi keluaran antara MySQL/MariaDB dan PostgreSQL.';
$string['outputformat_label'] = 'Format keluaran';
$string['page_title'] = 'Pusat cadangan';
$string['pluginname'] = 'Cadangan';
$string['processfailed'] = 'Gagal menjalankan proses cadangan: {$a}';
$string['processstartfailed'] = 'Tidak dapat memulai proses cadangan di server.';
$string['separatelogs_desc'] = 'Jika diaktifkan, sistem menghasilkan paket ZIP dengan satu file untuk basis data utama dan satu file lain hanya untuk tabel log.';
$string['separatelogs_label'] = 'Apakah Anda ingin mengekspor log secara terpisah?';
$string['type_database'] = 'Basis data';
$string['type_friendly_installation'] = 'Penginstal perangkat lunak Moodle™';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Proses mengembalikan kesalahan tetapi tidak memberikan detail.';
$string['unsupporteddbtype'] = 'Jenis basis data tidak didukung oleh plugin cadangan ini: {$a}';
$string['zipcreatefailed'] = 'Tidak dapat membuat file ZIP.';
