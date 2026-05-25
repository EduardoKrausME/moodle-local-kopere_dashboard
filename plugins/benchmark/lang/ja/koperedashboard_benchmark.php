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

$string['back_to_benchmark'] = 'ベンチマークに戻る';
$string['cap_run'] = 'ベンチマークを実行する';
$string['cap_run_desc'] = 'Kopere Dashboard で合成ベンチマークテストを実行できます。';
$string['cap_view'] = 'ベンチマークを表示する';
$string['cap_view_desc'] = 'ベンチマーク領域にアクセスし、パフォーマンス推奨事項を表示できます。';
$string['check_backup_auto_active'] = '自動バックアップ';
$string['check_cachejs'] = 'JavaScript キャッシュ';
$string['check_debug'] = 'デバッグレベル';
$string['check_debugdisplay'] = 'デバッグメッセージを表示する';
$string['check_themedesignermode'] = 'テーマデザイナーモード';
$string['debug_value'] = '有効 ({$a})';
$string['environment_db'] = 'データベース';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'OPcache の詳細';
$string['environment_opcache_warning'] = '本番環境では OPcache を有効にしてください。コンパイル済みの PHP スクリプトをメモリに保存し、CPU 使用率を下げ、応答時間を改善します。';
$string['environment_os'] = 'オペレーティングシステム';
$string['environment_os_windows_warning'] = 'Windows は Moodle の本番環境には推奨されません。互換性、安定性、パフォーマンスを高めるために Linux を推奨します。 <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Moodle ドキュメント: Windows 用完全インストールパッケージは本番環境には推奨されません</a>。';
$string['environment_php'] = 'PHP';
$string['environment_title'] = '環境';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = '本番環境では X-Sendfile を有効にしてください。Web サーバーがファイルを直接配信できるようになり、PHP のメモリ使用量を削減し、大きなファイルのダウンロードを改善します。';
$string['execute_title'] = 'ベンチマークを実行する';
$string['help_recommendations'] = 'これらの推奨事項は、環境が本番向けに設定されているかを判断するためのものです。データベース、Redis、cron、ディスク、またはリバースキャッシュの詳細な分析に代わるものではありません。';
$string['iterations'] = '反復回数';
$string['label_disabled'] = '無効';
$string['label_enabled'] = '有効';
$string['manage_intro'] = '短い合成テストセットを実行して、Moodle サーバー全体のパフォーマンス概要をすばやく確認します。テストでは、単純なデータベース読み取り、ディスク往復、JSON、ハッシュ、文字列処理を測定します。';
$string['manage_warning'] = '結果は比較用です。理想的には常に同じサーバーで実行し、PHP、データベース、ディスク、キャッシュ、Redis、Nginx、プラグインの変更前後を比較してください。';
$string['menu_desc'] = 'データベース、ディスク、CPU の時間を測定し、本番向けの簡易推奨事項を表示します。';
$string['menu_title'] = 'ベンチマーク';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>メモリ: {$a->memory} MB<br>最大ファイル数: {$a->maxfiles}<br>タイムスタンプ検証: {$a->timestamps}<br>再検証頻度: {$a->revalidate}';
$string['peakmemory'] = 'ピークメモリ';
$string['pluginname'] = 'ベンチマーク';
$string['recommend_backup_auto_active'] = 'ピーク時間帯に自動バックアップを実行しないでください。利用の少ない時間帯を推奨します。';
$string['recommend_cachejs'] = '本番環境では、処理と転送を減らすために JavaScript キャッシュを有効にしてください。';
$string['recommend_debug'] = 'デバッグを有効にすると処理コストとノイズが増えます。本番環境では無効にしてください。';
$string['recommend_debugdisplay'] = 'デバッグメッセージを画面に直接表示する設定は、本番環境では無効にしてください。';
$string['recommend_themedesignermode'] = 'CSS の再コンパイルやパフォーマンス低下を避けるため、本番環境ではテーマデザイナーモードを無効にしてください。';
$string['recommendation'] = '推奨事項';
$string['recommendations_title'] = 'クイック構成チェック';
$string['result_status'] = 'ステータス';
$string['results_title'] = 'テスト結果';
$string['run_benchmark'] = 'ベンチマークを実行する';
$string['status_attention'] = '注意';
$string['status_fast'] = '高速';
$string['status_slow'] = '低速';
$string['summary_title'] = '概要';
$string['test_db_desc'] = 'データベースから小さな設定レコードを繰り返し読み取ります。';
$string['test_db_name'] = 'データベース';
$string['test_files_desc'] = 'ローカル一時ファイルの書き込み、読み取り、削除を行います。';
$string['test_files_name'] = 'ファイルシステム';
$string['test_hash_desc'] = 'SHA-256 を繰り返し実行して、CPU の生の性能を測定します。';
$string['test_hash_name'] = 'ハッシュ / CPU';
$string['test_json_desc'] = '中程度のサイズの JSON 構造をエンコードおよびデコードします。';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'テスト';
$string['test_string_desc'] = 'HTML に似たコンテンツの単純なクリーンアップと分析を行います。';
$string['test_string_name'] = '文字列 / HTML';
$string['time_elapsed'] = '時間';
$string['total_time'] = '合計時間';
$string['value'] = '値';
$string['xsendfile_value'] = '有効 ({$a->header}<br>エイリアス: {$a->aliases})';
