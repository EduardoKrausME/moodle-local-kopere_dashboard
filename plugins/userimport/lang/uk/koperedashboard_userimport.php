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

$string['back_mapping'] = 'Повернутися до зіставлення';
$string['cannotreadcsv'] = 'Не вдалося прочитати CSV-файл.';
$string['cap_manage'] = 'Керувати імпортом користувачів';
$string['cap_manage_desc'] = 'Завантажувати CSV-файли, переглядати рядки та виконувати імпорт користувачів у Kopere Dashboard.';
$string['confirm_import'] = 'Імпортувати користувачів зараз';
$string['course_not_found'] = 'Курс не знайдено.';
$string['customfields'] = 'Користувацькі поля профілю';
$string['filename'] = 'Файл';
$string['group_not_found'] = 'Групу не знайдено у вибраному курсі.';
$string['idnumbercourse'] = 'Ідентифікаційний номер курсу';
$string['invalidcsv'] = 'Файл не схожий на дійсний CSV із заголовками.';
$string['invalidemail'] = 'Недійсна адреса електронної пошти.';
$string['invalidfiletype'] = 'Приймаються лише файли .csv або .txt.';
$string['invalidtoken'] = 'Токен імпорту недійсний або прострочений.';
$string['invalidusername'] = 'Недійсне значення імені користувача.';
$string['manage_intro'] = 'Завантажте CSV-файл, щоб створити користувачів, повторно використати наявні облікові записи, заповнити користувацькі поля профілю та за потреби зарахувати їх на курси й до груп.';
$string['manualinstance_missing'] = 'Курс {$a} не має увімкненого екземпляра ручного зарахування.';
$string['manualplugin_missing'] = 'Плагін ручного зарахування недоступний на цьому сайті Moodle.';
$string['mappingerror_email'] = 'Зіставте стовпець електронної пошти.';
$string['mappingerror_firstname'] = 'Зіставте стовпець імені (або стовпець повного імені).';
$string['menu_desc'] = 'Імпортуйте користувачів через CSV, переглядайте результат, створюйте облікові записи та за потреби зараховуйте їх на курси/до груп.';
$string['menu_title'] = 'Імпорт користувачів';
$string['missingemail'] = 'Відсутня електронна пошта.';
$string['missingfirstname'] = 'Відсутнє ім’я.';
$string['missingtempfile'] = 'Тимчасовий CSV-файл більше не існує.';
$string['missingusername'] = 'Відсутнє ім’я користувача.';
$string['pluginname'] = 'Імпорт користувачів';
$string['preview_intro'] = 'Перегляньте перші рядки, зіставте кожен стовпець CSV і виконайте попередній перегляд перед імпортом.';
$string['preview_title'] = 'Попередній перегляд і зіставлення';
$string['result_alreadyenrolled'] = 'Уже зараховано';
$string['result_courseenrolled'] = 'Зараховано на {$a}';
$string['result_groupadded'] = 'Додано до групи {$a}';
$string['result_usercreated'] = 'Користувача створено';
$string['result_userexists'] = 'Користувач уже існував';
$string['run_preview'] = 'Запустити попередній перегляд';
$string['saveuploaderror'] = 'Не вдалося зберегти завантажений файл у тимчасовому сховищі Moodle.';
$string['select_column'] = 'Виберіть стовпець';
$string['separator'] = 'Виявлений роздільник';
$string['separator_comma'] = 'Кома (,)';
$string['separator_semicolon'] = 'Крапка з комою (;)';
$string['separator_tab'] = 'Табуляція';
$string['shortnamecourse'] = 'Коротка назва/повна назва курсу';
$string['start_over'] = 'Почати новий імпорт';
$string['status_created'] = 'Створено';
$string['status_error'] = 'Помилка';
$string['status_existing'] = 'Наявний користувач';
$string['status_ok'] = 'Готово';
$string['status_willcreate'] = 'Буде створено';
$string['summary_create'] = 'Буде створено';
$string['summary_created'] = 'Створені користувачі';
$string['summary_enrolled'] = 'Нові зарахування';
$string['summary_errors'] = 'Помилки';
$string['summary_existing'] = 'Наявні';
$string['summary_total'] = 'Рядки';
$string['summary_withcourse'] = 'Рядки з курсом';
$string['table_course'] = 'Курс';
$string['table_email'] = 'Електронна пошта';
$string['table_firstname'] = 'Ім’я';
$string['table_group'] = 'Група';
$string['table_lastname'] = 'Прізвище';
$string['table_line'] = 'Рядок';
$string['table_message'] = 'Повідомлення';
$string['table_status'] = 'Стан';
$string['table_username'] = 'Ім’я користувача';
$string['tip_detectseparator'] = 'Плагін автоматично визначає файли, розділені крапкою з комою, комою або табуляцією.';
$string['tip_existing'] = 'Наявні користувачі не дублюються. Вони все одно можуть отримувати дані користувацьких полів і зарахування на курси.';
$string['tip_headers'] = 'Перший рядок CSV вважається рядком заголовків.';
$string['tip_password'] = 'Якщо новий користувач не має пароля або має дуже короткий пароль, генерується випадковий пароль і вмикається примусова зміна пароля.';
$string['upload_csv'] = 'Завантажити CSV-файл';
$string['upload_submit'] = 'Продовжити';
$string['uploaderror'] = 'Надішліть дійсний CSV-файл.';
