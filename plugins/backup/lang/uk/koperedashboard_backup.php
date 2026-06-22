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

$string['action_download'] = 'Завантажити';
$string['action_generate_database'] = 'Експортувати базу даних';
$string['action_generate_moodledata'] = 'Створити резервну копію moodledata';
$string['cannotopenexportfile'] = 'Не вдалося відкрити файл експорту: {$a}';
$string['cap_generate'] = 'Створювати резервні копії';
$string['cap_generate_desc'] = 'Дозволяє користувачам створювати файли резервних копій moodledata та резервні копії бази даних.';
$string['cap_view'] = 'Перегляд центру резервного копіювання';
$string['cap_view_desc'] = 'Дозволяє користувачам отримувати доступ до центру резервного копіювання та завантажувати створені файли.';
$string['col_actions'] = 'Дії';
$string['col_created'] = 'Створено';
$string['col_file'] = 'Файл';
$string['col_size'] = 'Розмір';
$string['col_type'] = 'Тип';
$string['commandnotfound'] = 'Потрібну системну команду не знайдено: {$a}.';
$string['current_source_label'] = 'Поточна база даних:';
$string['database_desc'] = 'Експортує структуру та дані бази даних у PHP, даючи змогу вибрати формат виводу та за потреби відокремити журнали в окремий файл.';
$string['database_success'] = 'Експорт бази даних успішно створено: {$a}';
$string['database_title'] = 'Експорт бази даних';
$string['emptyfiles'] = 'Файли резервних копій ще не створено.';
$string['exportscope_full'] = 'Повна база даних';
$string['exportscope_logs'] = 'Лише журнали';
$string['exportscope_main'] = 'База даних без журналів';
$string['filenotfound'] = 'Файл резервної копії не знайдено: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['friendly_installation_alternative_not_desc'] = 'Встановіть і налаштуйте <a href="https://moodle.org/plugins/local_alternative_file_system" target="_blank">Alternative File System</a>, щоб оптимізувати час міграції, зберігати файли Moodle у віддаленому сховищі, зменшити навантаження на локальний диск, спростити кластерні середовища, підвищити стійкість і за потреби використовувати доставку через CDN. Без цього ця експортна копія включить локальні файли moodledata до створеного ZIP.';
$string['friendly_installation_alternative_not_title'] = 'Alternative File System не встановлено або не налаштовано';
$string['friendly_installation_alternative_ok_desc'] = 'Перевірте в <a href="{$a}" target="_blank">альтернативній файловій системі</a>, що всі файли знаходяться у віддаленому сховищі, перед виконанням відновлення.';
$string['friendly_installation_alternative_ok_title'] = 'Alternative File System встановлено';
$string['friendly_installation_desc'] = 'Експортує базу даних у форматі, сумісному з <a href="https://github.com/EduardoKrausME/moodle_friendly_installation" target="_blank">інсталятором програмного забезпечення Moodle™</a> і MoodleData.';
$string['friendly_installation_generate'] = 'Експортувати';
$string['friendly_installation_success'] = 'Експорт успішно створено!';
$string['friendly_installation_title'] = 'Експорт для інсталятора програмного забезпечення Moodle™';
$string['gzipnotavailable'] = 'Розширення PHP zlib/gzip недоступне на цьому сервері.';
$string['history_title'] = 'Створені файли';
$string['home_kpi_empty_subtitle'] = 'Резервну копію не створено';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Найновіший файл: {$a}';
$string['home_kpi_title'] = 'Остання резервна копія';
$string['invalidaction'] = 'Запитана дія недійсна.';
$string['invalidfilename'] = 'Указане ім’я файлу недійсне.';
$string['invalidoutputformat'] = 'Указаний формат виводу недійсний: {$a}';
$string['menu_desc'] = 'Ручне створення резервної копії moodledata та експорт бази даних';
$string['menu_title'] = 'Резервні копії';
$string['moodledata_desc'] = 'Створює повний пакет папки moodledata, виключаючи лише папку, де зберігаються самі резервні копії.';
$string['moodledata_success'] = 'Резервну копію moodledata успішно створено: {$a}';
$string['moodledata_title'] = 'Резервна копія moodledata';
$string['notablesfound'] = 'Не знайдено таблиць для експорту.';
$string['outputformat_desc'] = 'Ви можете експортувати у формат поточної бази даних або конвертувати вивід між MySQL/MariaDB і PostgreSQL.';
$string['outputformat_label'] = 'Формат виводу';
$string['page_title'] = 'Центр резервного копіювання';
$string['pluginname'] = 'Резервні копії';
$string['processfailed'] = 'Не вдалося виконати процес резервного копіювання: {$a}';
$string['processstartfailed'] = 'Не вдалося запустити процес резервного копіювання на сервері.';
$string['separatelogs_desc'] = 'Якщо увімкнено, система створює ZIP-пакет з одним файлом для основної бази даних і ще одним файлом лише для таблиць журналів.';
$string['separatelogs_label'] = 'Бажаєте експортувати журнали окремо?';
$string['type_database'] = 'База даних';
$string['type_friendly_installation'] = 'Інсталятор програмного забезпечення Moodle™';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Процес повернув помилку, але не надав подробиць.';
$string['unsupporteddbtype'] = 'Тип бази даних не підтримується цим plugin резервного копіювання: {$a}';
$string['zipcreatefailed'] = 'Не вдалося створити ZIP-файл.';
