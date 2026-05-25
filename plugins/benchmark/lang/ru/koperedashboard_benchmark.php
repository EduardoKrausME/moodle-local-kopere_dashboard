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

$string['back_to_benchmark'] = 'Назад к тесту производительности';
$string['cap_run'] = 'Запустить тест производительности';
$string['cap_run_desc'] = 'Может запускать синтетические тесты производительности в Kopere Dashboard.';
$string['cap_view'] = 'Просмотр теста производительности';
$string['cap_view_desc'] = 'Может открывать раздел тестирования производительности и просматривать рекомендации.';
$string['check_backup_auto_active'] = 'Автоматическое резервное копирование';
$string['check_cachejs'] = 'Кэш JavaScript';
$string['check_debug'] = 'Уровень отладки';
$string['check_debugdisplay'] = 'Показывать сообщения отладки';
$string['check_themedesignermode'] = 'Режим дизайнера темы';
$string['debug_value'] = 'Включено ({$a})';
$string['environment_db'] = 'База данных';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Сведения об OPcache';
$string['environment_opcache_warning'] = 'Держите OPcache включенным в рабочей среде. Он хранит скомпилированные PHP-скрипты в памяти, снижает нагрузку на CPU и улучшает время ответа.';
$string['environment_os'] = 'Операционная система';
$string['environment_os_windows_warning'] = 'Windows не рекомендуется для рабочих сред Moodle. Предпочитайте Linux для лучшей совместимости, стабильности и производительности. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Документация Moodle: полный установочный пакет для Windows не рекомендуется для production</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Среда';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Держите X-Sendfile включенным в рабочей среде. Он позволяет веб-серверу отдавать файлы напрямую, уменьшая использование памяти PHP и улучшая загрузку больших файлов.';
$string['execute_title'] = 'Запустить тест производительности';
$string['help_recommendations'] = 'Эти рекомендации помогают понять, настроена ли среда для production. Они не заменяют подробный анализ базы данных, Redis, cron, дисков или обратного кэша.';
$string['iterations'] = 'Итерации';
$string['label_disabled'] = 'Отключено';
$string['label_enabled'] = 'Включено';
$string['manage_intro'] = 'Запустите короткий набор синтетических тестов, чтобы быстро оценить общую производительность сервера Moodle. Тесты измеряют простые чтения из базы данных, дисковый цикл, JSON, хэширование и обработку строк.';
$string['manage_warning'] = 'Результаты являются сравнительными. В идеале запускайте их всегда на одном и том же сервере и сравнивайте до/после изменений PHP, базы данных, диска, кэша, Redis, Nginx или плагинов.';
$string['menu_desc'] = 'Измеряет время базы данных, диска и CPU с быстрыми рекомендациями для production.';
$string['menu_title'] = 'Тест производительности';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>память: {$a->memory} MB<br>макс. файлов: {$a->maxfiles}<br>проверять timestamps: {$a->timestamps}<br>частота повторной проверки: {$a->revalidate}';
$string['peakmemory'] = 'Пиковая память';
$string['pluginname'] = 'Тест производительности';
$string['recommend_backup_auto_active'] = 'Избегайте автоматических резервных копий в часы пик. Предпочитайте интервалы вне периодов высокой нагрузки.';
$string['recommend_cachejs'] = 'В production держите кэш JavaScript включенным, чтобы снизить обработку и передачу данных.';
$string['recommend_debug'] = 'Активная отладка увеличивает затраты на обработку и шум. Держите ее отключенной в production.';
$string['recommend_debugdisplay'] = 'Показ сообщений отладки прямо на экране должен быть отключен в production.';
$string['recommend_themedesignermode'] = 'Режим дизайнера темы должен быть отключен в production, чтобы избежать перекомпиляции CSS и падения производительности.';
$string['recommendation'] = 'Рекомендация';
$string['recommendations_title'] = 'Быстрые проверки конфигурации';
$string['result_status'] = 'Статус';
$string['results_title'] = 'Результаты тестов';
$string['run_benchmark'] = 'Запустить тест производительности';
$string['status_attention'] = 'Внимание';
$string['status_fast'] = 'Быстро';
$string['status_slow'] = 'Медленно';
$string['summary_title'] = 'Сводка';
$string['test_db_desc'] = 'Повторные чтения небольших записей конфигурации из базы данных.';
$string['test_db_name'] = 'База данных';
$string['test_files_desc'] = 'Запись, чтение и удаление локального временного файла.';
$string['test_files_name'] = 'Файловая система';
$string['test_hash_desc'] = 'Повторные раунды SHA-256 для измерения чистой производительности CPU.';
$string['test_hash_name'] = 'Хэш / CPU';
$string['test_json_desc'] = 'Кодирование и декодирование JSON-структур среднего размера.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Тест';
$string['test_string_desc'] = 'Простая очистка и анализ содержимого, похожего на HTML.';
$string['test_string_name'] = 'Строки / HTML';
$string['time_elapsed'] = 'Время';
$string['total_time'] = 'Общее время';
$string['value'] = 'Значение';
$string['xsendfile_value'] = 'Включено ({$a->header}<br>псевдонимы: {$a->aliases})';
