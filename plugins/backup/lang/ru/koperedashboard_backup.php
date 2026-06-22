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

$string['action_download'] = 'Скачать';
$string['action_generate_database'] = 'Экспортировать базу данных';
$string['action_generate_moodledata'] = 'Создать резервную копию moodledata';
$string['cannotopenexportfile'] = 'Не удалось открыть файл экспорта: {$a}';
$string['cap_generate'] = 'Создавать резервные копии';
$string['cap_generate_desc'] = 'Позволяет пользователям создавать файлы резервных копий moodledata и резервные копии базы данных.';
$string['cap_view'] = 'Просматривать центр резервного копирования';
$string['cap_view_desc'] = 'Позволяет пользователям получать доступ к центру резервного копирования и скачивать созданные файлы.';
$string['col_actions'] = 'Действия';
$string['col_created'] = 'Создано';
$string['col_file'] = 'Файл';
$string['col_size'] = 'Размер';
$string['col_type'] = 'Тип';
$string['commandnotfound'] = 'Не найдена требуемая системная команда: {$a}.';
$string['current_source_label'] = 'Текущая база данных:';
$string['database_desc'] = 'Экспортирует структуру и данные базы данных в PHP, позволяя выбрать формат вывода и при необходимости отделить журналы в отдельный файл.';
$string['database_success'] = 'Экспорт базы данных успешно создан: {$a}';
$string['database_title'] = 'Экспорт базы данных';
$string['emptyfiles'] = 'Файлы резервных копий еще не созданы.';
$string['exportscope_full'] = 'Полная база данных';
$string['exportscope_logs'] = 'Только журналы';
$string['exportscope_main'] = 'База данных без журналов';
$string['filenotfound'] = 'Файл резервной копии не найден: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['friendly_installation_alternative_not_desc'] = 'Установите и настройте <a href="https://moodle.org/plugins/local_alternative_file_system" target="_blank">Alternative File System</a>, чтобы оптимизировать время миграции, хранить файлы Moodle в удалённом хранилище, снизить нагрузку на локальный диск, упростить кластерные среды, повысить отказоустойчивость и при необходимости использовать доставку через CDN. Без него этот экспорт добавит локальные файлы moodledata в созданный ZIP.';
$string['friendly_installation_alternative_not_title'] = 'Alternative File System не установлен или не настроен';
$string['friendly_installation_alternative_ok_desc'] = 'Проверьте в <a href="{$a}" target="_blank">альтернативной файловой системе</a>, что все файлы находятся в удалённом хранилище, перед выполнением восстановления.';
$string['friendly_installation_alternative_ok_title'] = 'Alternative File System установлен';
$string['friendly_installation_desc'] = 'Экспортирует базу данных в формате, совместимом с <a href="https://github.com/EduardoKrausME/moodle_friendly_installation" target="_blank">установщиком программного обеспечения Moodle™</a> и MoodleData.';
$string['friendly_installation_generate'] = 'Экспортировать';
$string['friendly_installation_success'] = 'Экспорт успешно создан!';
$string['friendly_installation_title'] = 'Экспорт для установщика программного обеспечения Moodle™';
$string['gzipnotavailable'] = 'Расширение PHP zlib/gzip недоступно на этом сервере.';
$string['history_title'] = 'Созданные файлы';
$string['home_kpi_empty_subtitle'] = 'Резервная копия не создана';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Самый новый файл: {$a}';
$string['home_kpi_title'] = 'Последняя резервная копия';
$string['invalidaction'] = 'Запрошенное действие недействительно.';
$string['invalidfilename'] = 'Указанное имя файла недействительно.';
$string['invalidoutputformat'] = 'Указанный формат вывода недействителен: {$a}';
$string['menu_desc'] = 'Ручное создание резервной копии moodledata и экспорт базы данных';
$string['menu_title'] = 'Резервные копии';
$string['moodledata_desc'] = 'Создает полный пакет папки moodledata, исключая только папку, в которой хранятся сами резервные копии.';
$string['moodledata_success'] = 'Резервная копия moodledata успешно создана: {$a}';
$string['moodledata_title'] = 'Резервная копия moodledata';
$string['notablesfound'] = 'Таблицы для экспорта не найдены.';
$string['outputformat_desc'] = 'Можно экспортировать в формат текущей базы данных или преобразовать вывод между MySQL/MariaDB и PostgreSQL.';
$string['outputformat_label'] = 'Формат вывода';
$string['page_title'] = 'Центр резервного копирования';
$string['pluginname'] = 'Резервные копии';
$string['processfailed'] = 'Не удалось выполнить процесс резервного копирования: {$a}';
$string['processstartfailed'] = 'Не удалось запустить процесс резервного копирования на сервере.';
$string['separatelogs_desc'] = 'Если включено, система создает ZIP-пакет с одним файлом для основной базы данных и другим файлом только для таблиц журналов.';
$string['separatelogs_label'] = 'Вы хотите экспортировать журналы отдельно?';
$string['type_database'] = 'База данных';
$string['type_friendly_installation'] = 'Установщик программного обеспечения Moodle™';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Процесс вернул ошибку, но не предоставил подробностей.';
$string['unsupporteddbtype'] = 'Тип базы данных не поддерживается этим плагином резервного копирования: {$a}';
$string['zipcreatefailed'] = 'Не удалось создать ZIP-файл.';
