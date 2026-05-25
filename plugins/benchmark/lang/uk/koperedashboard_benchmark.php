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

$string['back_to_benchmark'] = 'Назад до тесту продуктивності';
$string['cap_run'] = 'Запустити тест продуктивності';
$string['cap_run_desc'] = 'Може запускати синтетичні тести продуктивності в Kopere Dashboard.';
$string['cap_view'] = 'Перегляд тесту продуктивності';
$string['cap_view_desc'] = 'Може відкривати область тестування продуктивності та переглядати рекомендації щодо швидкодії.';
$string['check_backup_auto_active'] = 'Автоматичне резервне копіювання';
$string['check_cachejs'] = 'Кеш JavaScript';
$string['check_debug'] = 'Рівень налагодження';
$string['check_debugdisplay'] = 'Показувати повідомлення налагодження';
$string['check_themedesignermode'] = 'Режим дизайнера теми';
$string['debug_value'] = 'Увімкнено ({$a})';
$string['environment_db'] = 'База даних';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Деталі OPcache';
$string['environment_opcache_warning'] = 'Тримайте OPcache увімкненим у робочому середовищі. Він зберігає скомпільовані PHP-скрипти в памʼяті, зменшує використання CPU та покращує час відповіді.';
$string['environment_os'] = 'Операційна система';
$string['environment_os_windows_warning'] = 'Windows не рекомендується для робочих середовищ Moodle. Надавайте перевагу Linux для кращої сумісності, стабільності та продуктивності. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Документація Moodle: повний інсталяційний пакет для Windows не рекомендується для робочого середовища</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Середовище';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Тримайте X-Sendfile увімкненим у робочому середовищі. Це дає змогу вебсерверу віддавати файли напряму, зменшуючи використання памʼяті PHP та покращуючи завантаження великих файлів.';
$string['execute_title'] = 'Запустити тест продуктивності';
$string['help_recommendations'] = 'Ці рекомендації допомагають зрозуміти, чи налаштоване середовище для роботи в production. Вони не замінюють детальний аналіз бази даних, Redis, cron, дисків або зворотного кешу.';
$string['iterations'] = 'Ітерації';
$string['label_disabled'] = 'Вимкнено';
$string['label_enabled'] = 'Увімкнено';
$string['manage_intro'] = 'Запустіть короткий набір синтетичних тестів, щоб швидко оцінити загальну продуктивність сервера Moodle. Тести вимірюють прості читання з бази даних, цикл запису/читання диска, JSON, хешування та обробку рядків.';
$string['manage_warning'] = 'Результати є порівняльними. В ідеалі завжди запускайте їх на тому самому сервері та порівнюйте до/після змін у PHP, базі даних, диску, кеші, Redis, Nginx або плагінах.';
$string['menu_desc'] = 'Вимірює час роботи бази даних, диска та CPU з швидкими рекомендаціями для production.';
$string['menu_title'] = 'Тест продуктивності';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>памʼять: {$a->memory} MB<br>макс. файлів: {$a->maxfiles}<br>перевіряти timestamps: {$a->timestamps}<br>частота повторної перевірки: {$a->revalidate}';
$string['peakmemory'] = 'Пікова памʼять';
$string['pluginname'] = 'Тест продуктивності';
$string['recommend_backup_auto_active'] = 'Уникайте автоматичних резервних копій у години пікового навантаження. Обирайте періоди поза годинами активного використання.';
$string['recommend_cachejs'] = 'У production тримайте кеш JavaScript увімкненим, щоб зменшити обробку та передавання даних.';
$string['recommend_debug'] = 'Активне налагодження збільшує витрати обробки та кількість шуму. Тримайте його вимкненим у production.';
$string['recommend_debugdisplay'] = 'Показ повідомлень налагодження безпосередньо на екрані має бути вимкнений у production.';
$string['recommend_themedesignermode'] = 'Режим дизайнера теми має бути вимкнений у production, щоб уникнути перекомпіляції CSS і падіння продуктивності.';
$string['recommendation'] = 'Рекомендація';
$string['recommendations_title'] = 'Швидкі перевірки конфігурації';
$string['result_status'] = 'Стан';
$string['results_title'] = 'Результати тестів';
$string['run_benchmark'] = 'Запустити тест продуктивності';
$string['status_attention'] = 'Увага';
$string['status_fast'] = 'Швидко';
$string['status_slow'] = 'Повільно';
$string['summary_title'] = 'Підсумок';
$string['test_db_desc'] = 'Повторні читання невеликих записів конфігурації з бази даних.';
$string['test_db_name'] = 'База даних';
$string['test_files_desc'] = 'Запис, читання та видалення локального тимчасового файлу.';
$string['test_files_name'] = 'Файлова система';
$string['test_hash_desc'] = 'Повторні раунди SHA-256 для вимірювання чистої продуктивності CPU.';
$string['test_hash_name'] = 'Хеш / CPU';
$string['test_json_desc'] = 'Кодування та декодування JSON-структур середнього розміру.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Тест';
$string['test_string_desc'] = 'Просте очищення та аналіз вмісту, схожого на HTML.';
$string['test_string_name'] = 'Рядки / HTML';
$string['time_elapsed'] = 'Час';
$string['total_time'] = 'Загальний час';
$string['value'] = 'Значення';
$string['xsendfile_value'] = 'Увімкнено ({$a->header}<br>aliases: {$a->aliases})';
