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

$string['action_download'] = 'ダウンロード';
$string['action_generate_database'] = 'データベースをエクスポートする';
$string['action_generate_moodledata'] = 'moodledataバックアップを生成する';
$string['cannotopenexportfile'] = 'エクスポートファイルを開けませんでした: {$a}';
$string['cap_generate'] = 'バックアップを生成する';
$string['cap_generate_desc'] = 'ユーザーがmoodledataバックアップファイルおよびデータベースバックアップを生成できるようにします。';
$string['cap_view'] = 'バックアップセンターを表示する';
$string['cap_view_desc'] = 'ユーザーがバックアップセンターにアクセスし、生成されたファイルをダウンロードできるようにします。';
$string['col_actions'] = '操作';
$string['col_created'] = '作成日時';
$string['col_file'] = 'ファイル';
$string['col_size'] = 'サイズ';
$string['col_type'] = '種類';
$string['commandnotfound'] = '必要なシステムコマンドが見つかりませんでした: {$a}。';
$string['current_source_label'] = '現在のデータベース:';
$string['database_desc'] = 'データベースの構造とデータをPHPでエクスポートし、出力形式を選択したり、必要に応じてログを別ファイルに分離したりできます。';
$string['database_success'] = 'データベースのエクスポートが正常に生成されました: {$a}';
$string['database_title'] = 'データベースエクスポート';
$string['emptyfiles'] = 'バックアップファイルはまだ生成されていません。';
$string['exportscope_full'] = 'データベース全体';
$string['exportscope_logs'] = 'ログのみ';
$string['exportscope_main'] = 'ログを除くデータベース';
$string['filenotfound'] = 'バックアップファイルが見つかりません: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'このサーバーではPHP zlib/gzip拡張機能を利用できません。';
$string['history_title'] = '生成されたファイル';
$string['home_kpi_empty_subtitle'] = 'バックアップは生成されていません';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = '最新のファイル: {$a}';
$string['home_kpi_title'] = '最新のバックアップ';
$string['invalidaction'] = '要求された操作は無効です。';
$string['invalidfilename'] = '指定されたファイル名は無効です。';
$string['invalidoutputformat'] = '指定された出力形式は無効です: {$a}';
$string['menu_desc'] = 'moodledataバックアップの手動生成およびデータベースエクスポート';
$string['menu_title'] = 'バックアップ';
$string['moodledata_desc'] = 'moodledataフォルダーの完全なパッケージを生成します。ただし、バックアップ自体が保存されるフォルダーのみ除外されます。';
$string['moodledata_success'] = 'moodledataバックアップが正常に生成されました: {$a}';
$string['moodledata_title'] = 'moodledataバックアップ';
$string['notablesfound'] = 'エクスポート対象のテーブルが見つかりませんでした。';
$string['outputformat_desc'] = '現在のデータベース形式にエクスポートするか、MySQL/MariaDBとPostgreSQLの間で出力を変換できます。';
$string['outputformat_label'] = '出力形式';
$string['page_title'] = 'バックアップセンター';
$string['pluginname'] = 'バックアップ';
$string['processfailed'] = 'バックアッププロセスの実行に失敗しました: {$a}';
$string['processstartfailed'] = 'サーバー上でバックアッププロセスを開始できませんでした。';
$string['separatelogs_desc'] = '有効にすると、メインデータベース用のファイル1つとログテーブル専用の別ファイル1つを含むZIPパッケージが生成されます。';
$string['separatelogs_label'] = 'ログを別々にエクスポートしますか?';
$string['storage_desc'] = '生成されたファイルは、保護されたmoodledata領域内に保存されます。';
$string['storage_title'] = '保存場所';
$string['type_database'] = 'データベース';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'プロセスはエラーを返しましたが、詳細は提供されませんでした。';
$string['unsupporteddbtype'] = 'このバックアッププラグインではサポートされていないデータベースタイプです: {$a}';
$string['zipcreatefailed'] = 'ZIPファイルを作成できませんでした。';
