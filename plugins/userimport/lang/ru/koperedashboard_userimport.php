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

$string['back_mapping'] = 'Вернуться к сопоставлению';
$string['cannotreadcsv'] = 'Не удалось прочитать CSV-файл.';
$string['cap_manage'] = 'Управлять импортом пользователей';
$string['cap_manage_desc'] = 'Загружать CSV-файлы, просматривать строки и выполнять импорт пользователей в Kopere Dashboard.';
$string['confirm_import'] = 'Импортировать пользователей сейчас';
$string['course_not_found'] = 'Курс не найден.';
$string['customfields'] = 'Настраиваемые поля профиля';
$string['filename'] = 'Файл';
$string['group_not_found'] = 'Группа не найдена в выбранном курсе.';
$string['idnumbercourse'] = 'Идентификационный номер курса';
$string['invalidcsv'] = 'Файл не похож на корректный CSV с заголовками.';
$string['invalidemail'] = 'Недействительный адрес электронной почты.';
$string['invalidfiletype'] = 'Принимаются только файлы .csv или .txt.';
$string['invalidtoken'] = 'Токен импорта недействителен или истек.';
$string['invalidusername'] = 'Недопустимое значение имени пользователя.';
$string['manage_intro'] = 'Загрузите CSV-файл, чтобы создать пользователей, использовать существующие учетные записи, заполнить настраиваемые поля профиля и при необходимости записать их на курсы и в группы.';
$string['manualinstance_missing'] = 'В курсе {$a} нет включенного экземпляра ручной записи.';
$string['manualplugin_missing'] = 'Плагин ручной записи недоступен на этом сайте Moodle.';
$string['mappingerror_email'] = 'Сопоставьте столбец электронной почты.';
$string['mappingerror_firstname'] = 'Сопоставьте столбец имени (или столбец полного имени).';
$string['menu_desc'] = 'Импортируйте пользователей через CSV, просматривайте результат, создавайте учетные записи и при необходимости записывайте их на курсы/в группы.';
$string['menu_title'] = 'Импорт пользователей';
$string['missingemail'] = 'Отсутствует электронная почта.';
$string['missingfirstname'] = 'Отсутствует имя.';
$string['missingtempfile'] = 'Временный CSV-файл больше не существует.';
$string['missingusername'] = 'Отсутствует имя пользователя.';
$string['pluginname'] = 'Импорт пользователей';
$string['preview_intro'] = 'Проверьте первые строки, сопоставьте каждый столбец CSV и выполните предварительный просмотр перед импортом.';
$string['preview_title'] = 'Предпросмотр и сопоставление';
$string['result_alreadyenrolled'] = 'Уже записан';
$string['result_courseenrolled'] = 'Записан на {$a}';
$string['result_groupadded'] = 'Добавлен в группу {$a}';
$string['result_usercreated'] = 'Пользователь создан';
$string['result_userexists'] = 'Пользователь уже существовал';
$string['run_preview'] = 'Запустить предпросмотр';
$string['saveuploaderror'] = 'Не удалось сохранить загруженный файл во временное хранилище Moodle.';
$string['select_column'] = 'Выберите столбец';
$string['separator'] = 'Обнаруженный разделитель';
$string['separator_comma'] = 'Запятая (,)';
$string['separator_semicolon'] = 'Точка с запятой (;)';
$string['separator_tab'] = 'Табуляция';
$string['shortnamecourse'] = 'Краткое название/полное название курса';
$string['start_over'] = 'Начать новый импорт';
$string['status_created'] = 'Создан';
$string['status_error'] = 'Ошибка';
$string['status_existing'] = 'Существующий пользователь';
$string['status_ok'] = 'Готово';
$string['status_willcreate'] = 'Будет создан';
$string['summary_create'] = 'Будет создан';
$string['summary_created'] = 'Созданные пользователи';
$string['summary_enrolled'] = 'Новые записи';
$string['summary_errors'] = 'Ошибки';
$string['summary_existing'] = 'Существующие';
$string['summary_total'] = 'Строки';
$string['summary_withcourse'] = 'Строки с курсом';
$string['table_course'] = 'Курс';
$string['table_email'] = 'Электронная почта';
$string['table_firstname'] = 'Имя';
$string['table_group'] = 'Группа';
$string['table_lastname'] = 'Фамилия';
$string['table_line'] = 'Строка';
$string['table_message'] = 'Сообщение';
$string['table_status'] = 'Статус';
$string['table_username'] = 'Имя пользователя';
$string['tip_detectseparator'] = 'Плагин автоматически определяет файлы, разделенные точкой с запятой, запятой или табуляцией.';
$string['tip_existing'] = 'Существующие пользователи не дублируются. Они по-прежнему могут получать данные настраиваемых полей и записи на курсы.';
$string['tip_headers'] = 'Первая строка CSV считается строкой заголовков.';
$string['tip_password'] = 'Если у нового пользователя нет пароля или он очень короткий, генерируется случайный пароль и включается принудительная смена пароля.';
$string['upload_csv'] = 'Загрузить CSV-файл';
$string['upload_submit'] = 'Продолжить';
$string['uploaderror'] = 'Отправьте корректный CSV-файл.';
