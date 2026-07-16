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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actions_title'] = '操作';
$string['already_has_valid'] = '有効なものが既に存在します';
$string['attest:manage'] = '証明書を管理する';
$string['attest:view'] = '証明書を表示する';
$string['audit_issue_create'] = '証明書を生成しました。';
$string['audit_tpl_create'] = 'テンプレートを作成しました。';
$string['audit_tpl_delete'] = 'テンプレートを削除しました。';
$string['audit_tpl_update'] = 'テンプレートを更新しました。';
$string['available_desc'] = '登録済みコースの証明書のみ。';
$string['available_title'] = '利用可能な証明書';
$string['cap_manage'] = '証明書管理者';
$string['cap_manage_desc'] = '証明書テンプレートを作成および管理できます。';
$string['cap_view'] = '証明書へのアクセス';
$string['cap_view_desc'] = '自分の証明書を表示および生成できます。';
$string['choose_course'] = 'コースを選択';
$string['choose_course_help'] = '利用可能な証明書を表示するには、まずコースを選択してください。';
$string['choose_course_placeholder'] = 'コースを選択';
$string['close_modal'] = '閉じる';
$string['course_removed'] = 'コースが削除されました';
$string['delete_template'] = '削除';
$string['edit_template'] = 'テンプレートを編集';
$string['empty_available'] = 'あなたの登録に利用できる証明書はありません。';
$string['empty_available_for_course'] = '選択したコースに利用できる証明書はありません。';
$string['empty_available_select_course'] = '利用可能な証明書を一覧表示するにはコースを選択してください。';
$string['empty_courses'] = '現在、どのコースにも登録されていません。';
$string['empty_issued'] = 'まだ証明書を生成していません。';
$string['field_active'] = '有効';
$string['field_allcourses'] = 'すべてのコースで有効';
$string['field_courses'] = 'コース';
$string['field_courses_help'] = 'このテンプレートを使用できるコースを選択してください。「すべてのコースで有効」がチェックされている場合、この設定は無視されます。';
$string['field_html'] = 'HTMLテンプレート';
$string['field_html_help'] = '証明書PDFの生成に使用するHTMLを入力してください。利用可能なプレースホルダーを使用して、学生、コース、日付、有効期限の動的データを挿入できます。';
$string['field_name'] = '名称';
$string['field_validmonths'] = '有効期間（月）';
$string['footer_created'] = '作成日';
$string['footer_desc'] = 'QRコードまたは以下のリンクで検証できる電子文書です。';
$string['footer_hash'] = '署名';
$string['footer_title'] = '電子署名';
$string['footer_validuntil'] = '有効期限';
$string['generate'] = 'PDFを生成';
$string['generate_new'] = '証明書を生成';
$string['generate_new_button'] = '新しい証明書を生成';
$string['home_kpi_subtitle'] = '発行済みで有効な文書';
$string['home_kpi_title'] = '有効な証明書';
$string['issued_desc'] = '既に生成された証明書と、それらがまだ有効かどうかを確認します。';
$string['issued_title'] = '生成済み証明書';
$string['manage_title'] = '証明書テンプレート';
$string['menu_desc'] = 'HTMLテンプレートに基づいてPDF証明書を生成します。';
$string['menu_title'] = '証明書';
$string['new_template'] = '新しいテンプレート';
$string['open_attestation'] = '証明書を開く';
$string['open_valid'] = '有効な証明書を開く';
$string['placeholders_title'] = '利用可能なプレースホルダー';
$string['pluginname'] = '証明書';
$string['recreate_valid'] = '有効な証明書を再生成';
$string['status_expired'] = '期限切れ';
$string['status_notgenerated'] = 'まだ生成されていません';
$string['status_title'] = 'ステータス';
$string['status_valid'] = '有効';
$string['status_valid_expiring'] = '有効ですが期限切れが近いです';
$string['student_title'] = '自分の証明書';
$string['studentcard'] = '学生証';
$string['studentcard_back_placeholder_description'] = '検証面に表示される翻訳済みの説明文です。';
$string['studentcard_back_placeholder_fullname'] = '学生の氏名です。';
$string['studentcard_back_placeholder_hashlabel'] = '検証コードの前に表示される翻訳済みラベルです。';
$string['studentcard_back_placeholder_qrcode'] = 'Base64 PNG データ URI として埋め込まれた検証用 QR コードです。';
$string['studentcard_back_placeholder_sitefullname'] = '書式設定された Moodle サイトの正式名称です。';
$string['studentcard_back_placeholder_title'] = '検証面の翻訳済みタイトルです。';
$string['studentcard_back_placeholder_userid'] = 'Moodle の数値ユーザー ID です。';
$string['studentcard_back_placeholder_validationcode'] = '学生証用に生成された公開検証コードです。';
$string['studentcard_back_placeholder_verifyurl'] = '学生証の検証に使用する公開 URL です。';
$string['studentcard_back_placeholder_wwwroot'] = 'Moodle サイトのベース URL です。';
$string['studentcard_desc'] = '';
$string['studentcard_front_placeholder_coursefullname'] = '学生が登録している最初の表示可能なコースの書式設定済み正式名称です。';
$string['studentcard_front_placeholder_courselabel'] = '翻訳済みのコースラベルです。';
$string['studentcard_front_placeholder_cpf'] = 'カスタムプロファイルフィールドから取得した CPF です。取得できない場合は識別番号を使用します。';
$string['studentcard_front_placeholder_cpflabel'] = 'CPF フィールドのラベルです。';
$string['studentcard_front_placeholder_email'] = '学生のメールアドレスです。';
$string['studentcard_front_placeholder_fullname'] = '学生の氏名です。';
$string['studentcard_front_placeholder_photo'] = 'Base64 画像データ URI として埋め込まれたプロフィール写真です。';
$string['studentcard_front_placeholder_title'] = '学生証の翻訳済みタイトルです。';
$string['studentcard_front_placeholder_userid'] = 'Moodle の数値ユーザー ID です。';
$string['studentcard_settings_back'] = '裏面テンプレート';
$string['studentcard_settings_back_template'] = '裏面の Mustache';
$string['studentcard_settings_back_template_help'] = '学生証の 2 ページ目で TCPDF によりレンダリングされる Mustache と HTML です。文書を引き続き検証できるように、検証 URL または QR コードをレイアウトに残してください。';
$string['studentcard_settings_back_variables'] = '裏面で使用可能な変数';
$string['studentcard_settings_back_variables_desc'] = 'これらの Mustache 変数は、PDF の検証面を生成するときに置き換えられます。';
$string['studentcard_settings_description'] = '学生証 PDF の生成に使用する 2 つの Mustache/HTML テンプレートを編集します。表面には学生の身元情報とコース、裏面にはコード、公開リンク、検証用 QR コードが含まれます。';
$string['studentcard_settings_front'] = '表面テンプレート';
$string['studentcard_settings_front_template'] = '表面の Mustache';
$string['studentcard_settings_front_template_help'] = '学生証の 1 ページ目で TCPDF によりレンダリングされる Mustache と HTML です。画像は、埋め込みデータ URI として提供される写真変数を使用して保持する必要があります。';
$string['studentcard_settings_front_variables'] = '表面で使用可能な変数';
$string['studentcard_settings_front_variables_desc'] = 'これらの Mustache 変数は、PDF の識別面を生成するときに置き換えられます。';
$string['studentcard_settings_menu'] = '学生証テンプレート';
$string['studentcard_settings_title'] = '学生証テンプレート';
$string['studentcardgenerate'] = 'PDFを生成';
$string['studentcardnophoto'] = '<h5>プロフィール写真がありません。</h5>学生証を生成するには、プロフィールを編集して写真を追加してください。';
$string['studentcardnovisiblecourses'] = '学生証は、表示されているコースに登録しているユーザーのみ利用できます。';
$string['studentcardsignaturedesc'] = 'このページには、学生証PDFの検証コードと公開検証リンクが含まれています。';
$string['studentcardsignaturetitle'] = '電子署名と検証ツール';
$string['studentcardvalidationinvalid'] = '検証コードが無効です。';
$string['studentcardvalidationlabel'] = '検証ツール';
$string['studentcardvalidationtitle'] = '学生証の検証';
$string['studentcardvalidationvalid'] = '有効な学生証です。';
$string['template_removed'] = 'テンプレートを削除しました';
$string['title_view'] = '証明書';
$string['verify_title'] = '証明書の検証';
