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

$string['back_to_benchmark'] = 'العودة إلى اختبار الأداء';
$string['cap_run'] = 'تشغيل اختبار الأداء';
$string['cap_run_desc'] = 'يمكنه تشغيل اختبارات أداء اصطناعية في Kopere Dashboard.';
$string['cap_view'] = 'عرض اختبار الأداء';
$string['cap_view_desc'] = 'يمكنه الوصول إلى منطقة اختبار الأداء وعرض توصيات الأداء.';
$string['check_backup_auto_active'] = 'نسخ احتياطي تلقائي';
$string['check_cachejs'] = 'ذاكرة JavaScript المؤقتة';
$string['check_debug'] = 'مستوى التصحيح';
$string['check_debugdisplay'] = 'عرض رسائل التصحيح';
$string['check_themedesignermode'] = 'وضع مصمم القالب';
$string['debug_value'] = 'مفعل ({$a})';
$string['environment_db'] = 'قاعدة البيانات';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'تفاصيل OPcache';
$string['environment_opcache_warning'] = 'أبقِ OPcache مفعلاً في بيئة الإنتاج. فهو يخزن سكربتات PHP المترجمة في الذاكرة، ويقلل استخدام وحدة المعالجة المركزية ويحسن زمن الاستجابة.';
$string['environment_os'] = 'نظام التشغيل';
$string['environment_os_windows_warning'] = 'لا يُنصح باستخدام Windows لبيئات Moodle الإنتاجية. يُفضل Linux للحصول على توافق واستقرار وأداء أفضل. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">وثائق Moodle: حزمة التثبيت الكاملة لنظام Windows غير موصى بها للإنتاج</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'البيئة';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'أبقِ X-Sendfile مفعلاً في بيئة الإنتاج. فهو يتيح لخادم الويب تسليم الملفات مباشرة، مما يقلل استخدام ذاكرة PHP ويحسن تنزيل الملفات الكبيرة.';
$string['execute_title'] = 'تشغيل اختبار الأداء';
$string['help_recommendations'] = 'تساعد هذه التوصيات في تفسير ما إذا كانت البيئة مضبوطة للإنتاج. وهي لا تغني عن تحليل تفصيلي لقاعدة البيانات وRedis وcron والأقراص أو الذاكرة المؤقتة العكسية.';
$string['iterations'] = 'التكرارات';
$string['label_disabled'] = 'معطل';
$string['label_enabled'] = 'مفعل';
$string['manage_intro'] = 'شغّل مجموعة قصيرة من الاختبارات الاصطناعية للحصول على نظرة سريعة على الأداء العام لخادم Moodle. تقيس الاختبارات قراءات بسيطة من قاعدة البيانات، ودورة القرص، وJSON، والتجزئة، ومعالجة النصوص.';
$string['manage_warning'] = 'النتائج للمقارنة. من الأفضل تشغيلها دائماً على الخادم نفسه والمقارنة قبل/بعد التغييرات في PHP أو قاعدة البيانات أو القرص أو الذاكرة المؤقتة أو Redis أو Nginx أو الإضافات.';
$string['menu_desc'] = 'يقيس زمن قاعدة البيانات والقرص ووحدة المعالجة المركزية مع توصيات إنتاج سريعة.';
$string['menu_title'] = 'اختبار الأداء';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>الذاكرة: {$a->memory} MB<br>الحد الأقصى للملفات: {$a->maxfiles}<br>التحقق من الطوابع الزمنية: {$a->timestamps}<br>تكرار إعادة التحقق: {$a->revalidate}';
$string['peakmemory'] = 'ذروة الذاكرة';
$string['pluginname'] = 'اختبار الأداء';
$string['recommend_backup_auto_active'] = 'تجنب تشغيل النسخ الاحتياطية التلقائية خلال ساعات الذروة. يُفضل استخدام نوافذ خارج أوقات الاستخدام العالي.';
$string['recommend_cachejs'] = 'في الإنتاج، أبقِ ذاكرة JavaScript المؤقتة مفعلة لتقليل المعالجة والنقل.';
$string['recommend_debug'] = 'يزيد التصحيح النشط تكلفة المعالجة والضوضاء. أبقه معطلاً في الإنتاج.';
$string['recommend_debugdisplay'] = 'يجب تعطيل عرض رسائل التصحيح مباشرة على الشاشة في بيئة الإنتاج.';
$string['recommend_themedesignermode'] = 'يجب تعطيل وضع مصمم القالب في الإنتاج لتجنب إعادة تجميع CSS وانخفاض الأداء.';
$string['recommendation'] = 'توصية';
$string['recommendations_title'] = 'فحوصات تكوين سريعة';
$string['result_status'] = 'الحالة';
$string['results_title'] = 'نتائج الاختبار';
$string['run_benchmark'] = 'تشغيل اختبار الأداء';
$string['status_attention'] = 'انتباه';
$string['status_fast'] = 'سريع';
$string['status_slow'] = 'بطيء';
$string['summary_title'] = 'ملخص';
$string['test_db_desc'] = 'قراءات متكررة لسجلات إعدادات صغيرة من قاعدة البيانات.';
$string['test_db_name'] = 'قاعدة البيانات';
$string['test_files_desc'] = 'كتابة وقراءة وإزالة ملف مؤقت محلي.';
$string['test_files_name'] = 'نظام الملفات';
$string['test_hash_desc'] = 'جولات SHA-256 متكررة لقياس الأداء الخام لوحدة المعالجة المركزية.';
$string['test_hash_name'] = 'التجزئة / وحدة المعالجة المركزية';
$string['test_json_desc'] = 'ترميز وفك ترميز بنى JSON متوسطة الحجم.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'الاختبار';
$string['test_string_desc'] = 'تنظيف وتحليل بسيط لمحتوى شبيه بـ HTML.';
$string['test_string_name'] = 'النصوص / HTML';
$string['time_elapsed'] = 'الوقت';
$string['total_time'] = 'الوقت الإجمالي';
$string['value'] = 'القيمة';
$string['xsendfile_value'] = 'مفعل ({$a->header}<br>الأسماء المستعارة: {$a->aliases})';
