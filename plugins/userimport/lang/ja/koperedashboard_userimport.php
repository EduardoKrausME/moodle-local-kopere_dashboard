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

$string['back_mapping'] = 'マッピングに戻る';
$string['cannotreadcsv'] = 'CSVファイルを読み取れませんでした。';
$string['cap_manage'] = 'ユーザーインポートを管理する';
$string['cap_manage_desc'] = 'Kopere Dashboard 内でCSVファイルをアップロードし、行をプレビューしてユーザーインポートを実行できます。';
$string['confirm_import'] = '今すぐユーザーをインポート';
$string['course_not_found'] = 'コースが見つかりません。';
$string['customfields'] = 'カスタムプロファイルフィールド';
$string['filename'] = 'ファイル';
$string['group_not_found'] = '選択されたコース内にグループが見つかりません。';
$string['idnumbercourse'] = 'コースID番号';
$string['invalidcsv'] = 'このファイルはヘッダー付きの有効なCSVではないようです。';
$string['invalidemail'] = 'メールアドレスが無効です。';
$string['invalidfiletype'] = '受け付けるファイルは .csv または .txt のみです。';
$string['invalidtoken'] = 'インポートトークンが無効または期限切れです。';
$string['invalidusername'] = 'ユーザー名の値が無効です。';
$string['manage_intro'] = 'CSVファイルをアップロードして、ユーザーの作成、既存アカウントの再利用、カスタムプロファイルフィールドの入力、必要に応じたコースやグループへの登録を行います。';
$string['manualinstance_missing'] = 'コース {$a} には有効な手動登録インスタンスがありません。';
$string['manualplugin_missing'] = 'この Moodle サイトでは手動登録プラグインを利用できません。';
$string['mappingerror_email'] = 'メール列をマッピングしてください。';
$string['mappingerror_firstname'] = '名の列（またはフルネーム列）をマッピングしてください。';
$string['menu_desc'] = 'CSVでユーザーをインポートし、結果をプレビューしてアカウントを作成し、必要に応じてコース/グループに登録します。';
$string['menu_title'] = 'ユーザーインポート';
$string['missingemail'] = 'メールがありません。';
$string['missingfirstname'] = '名がありません。';
$string['missingtempfile'] = '一時CSVファイルはもう存在しません。';
$string['missingusername'] = 'ユーザー名がありません。';
$string['pluginname'] = 'ユーザーインポート';
$string['preview_intro'] = '最初の行を確認し、各CSV列をマッピングして、インポート前にドライプレビューを実行してください。';
$string['preview_title'] = 'プレビューとマッピング';
$string['result_alreadyenrolled'] = '登録済み';
$string['result_courseenrolled'] = '{$a} に登録済み';
$string['result_groupadded'] = 'グループ {$a} に追加済み';
$string['result_usercreated'] = 'ユーザーを作成しました';
$string['result_userexists'] = 'ユーザーはすでに存在していました';
$string['run_preview'] = 'プレビューを実行';
$string['saveuploaderror'] = 'アップロードされたファイルを Moodle の一時ストレージに保存できませんでした。';
$string['select_column'] = '列を選択';
$string['separator'] = '検出された区切り文字';
$string['separator_comma'] = 'カンマ (,)';
$string['separator_semicolon'] = 'セミコロン (;)';
$string['separator_tab'] = 'タブ';
$string['shortnamecourse'] = 'コース省略名/フルネーム';
$string['start_over'] = '新しいインポートを開始';
$string['status_created'] = '作成済み';
$string['status_error'] = 'エラー';
$string['status_existing'] = '既存ユーザー';
$string['status_ok'] = '準備完了';
$string['status_willcreate'] = '作成予定';
$string['summary_create'] = '作成予定';
$string['summary_created'] = '作成済みユーザー';
$string['summary_enrolled'] = '新規登録';
$string['summary_errors'] = 'エラー';
$string['summary_existing'] = '既存';
$string['summary_total'] = '行';
$string['summary_withcourse'] = 'コースありの行';
$string['table_course'] = 'コース';
$string['table_email'] = 'メール';
$string['table_firstname'] = '名';
$string['table_group'] = 'グループ';
$string['table_lastname'] = '姓';
$string['table_line'] = '行';
$string['table_message'] = 'メッセージ';
$string['table_status'] = 'ステータス';
$string['table_username'] = 'ユーザー名';
$string['tip_detectseparator'] = 'プラグインは、セミコロン、カンマ、タブ区切りのファイルを自動検出します。';
$string['tip_existing'] = '既存ユーザーは重複作成されません。カスタムフィールドデータやコース登録を引き続き受け取ることができます。';
$string['tip_headers'] = 'CSVの最初の行はヘッダー行として扱われます。';
$string['tip_password'] = '新規ユーザーにパスワードがない、または非常に短い場合は、ランダムなパスワードが生成され、パスワード変更の強制が有効になります。';
$string['upload_csv'] = 'CSVファイルをアップロード';
$string['upload_submit'] = '続行';
$string['uploaderror'] = '有効なCSVファイルを送信してください。';
