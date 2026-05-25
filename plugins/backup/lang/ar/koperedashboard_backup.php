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

$string['action_download'] = 'تنزيل';
$string['action_generate_database'] = 'تصدير قاعدة البيانات';
$string['action_generate_moodledata'] = 'إنشاء نسخة احتياطية من moodledata';
$string['cannotopenexportfile'] = 'تعذر فتح ملف التصدير: {$a}';
$string['cap_generate'] = 'إنشاء نسخ احتياطية';
$string['cap_generate_desc'] = 'يسمح للمستخدمين بإنشاء ملفات نسخ احتياطي لـ moodledata ونسخ احتياطية لقاعدة البيانات.';
$string['cap_view'] = 'عرض مركز النسخ الاحتياطي';
$string['cap_view_desc'] = 'يسمح للمستخدمين بالوصول إلى مركز النسخ الاحتياطي وتنزيل الملفات التي تم إنشاؤها.';
$string['col_actions'] = 'الإجراءات';
$string['col_created'] = 'تم الإنشاء في';
$string['col_file'] = 'ملف';
$string['col_size'] = 'الحجم';
$string['col_type'] = 'النوع';
$string['commandnotfound'] = 'لم يتم العثور على أمر النظام المطلوب: {$a}.';
$string['current_source_label'] = 'قاعدة البيانات الحالية:';
$string['database_desc'] = 'يصدر بنية قاعدة البيانات وبياناتها بصيغة PHP، مما يتيح لك اختيار تنسيق الإخراج وفصل السجلات اختياريًا في ملف مستقل.';
$string['database_success'] = 'تم إنشاء تصدير قاعدة البيانات بنجاح: {$a}';
$string['database_title'] = 'تصدير قاعدة البيانات';
$string['emptyfiles'] = 'لم يتم إنشاء أي ملفات نسخ احتياطي بعد.';
$string['exportscope_full'] = 'قاعدة البيانات كاملة';
$string['exportscope_logs'] = 'السجلات فقط';
$string['exportscope_main'] = 'قاعدة البيانات بدون سجلات';
$string['filenotfound'] = 'لم يتم العثور على ملف النسخة الاحتياطية: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'إضافة PHP zlib/gzip غير متوفرة على هذا الخادم.';
$string['history_title'] = 'الملفات التي تم إنشاؤها';
$string['home_kpi_empty_subtitle'] = 'لم يتم إنشاء أي نسخة احتياطية';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'أحدث ملف: {$a}';
$string['home_kpi_title'] = 'آخر نسخة احتياطية';
$string['invalidaction'] = 'الإجراء المطلوب غير صالح.';
$string['invalidfilename'] = 'اسم الملف المحدد غير صالح.';
$string['invalidoutputformat'] = 'تنسيق الإخراج المحدد غير صالح: {$a}';
$string['menu_desc'] = 'إنشاء يدوي لنسخة احتياطية من moodledata وتصدير قاعدة البيانات';
$string['menu_title'] = 'النسخ الاحتياطية';
$string['moodledata_desc'] = 'ينشئ حزمة كاملة لمجلد moodledata، مع استثناء المجلد الذي يتم فيه تخزين النسخ الاحتياطية نفسها فقط.';
$string['moodledata_success'] = 'تم إنشاء النسخة الاحتياطية من moodledata بنجاح: {$a}';
$string['moodledata_title'] = 'نسخة احتياطية من moodledata';
$string['notablesfound'] = 'لم يتم العثور على جداول للتصدير.';
$string['outputformat_desc'] = 'يمكنك التصدير إلى تنسيق قاعدة البيانات الحالي أو تحويل الإخراج بين MySQL/MariaDB وPostgreSQL.';
$string['outputformat_label'] = 'تنسيق الإخراج';
$string['page_title'] = 'مركز النسخ الاحتياطي';
$string['pluginname'] = 'النسخ الاحتياطية';
$string['processfailed'] = 'فشل تشغيل عملية النسخ الاحتياطي: {$a}';
$string['processstartfailed'] = 'تعذر بدء عملية النسخ الاحتياطي على الخادم.';
$string['separatelogs_desc'] = 'عند التفعيل، ينشئ النظام حزمة ZIP تحتوي على ملف واحد لقاعدة البيانات الرئيسية وملف آخر لجداول السجلات فقط.';
$string['separatelogs_label'] = 'هل تريد تصدير السجلات بشكل منفصل؟';
$string['storage_desc'] = 'يتم حفظ الملفات التي تم إنشاؤها داخل منطقة moodledata المحمية.';
$string['storage_title'] = 'موقع التخزين';
$string['type_database'] = 'قاعدة البيانات';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'أعادت العملية خطأً ولكنها لم تقدم تفاصيل.';
$string['unsupporteddbtype'] = 'نوع قاعدة البيانات غير مدعوم بواسطة إضافة النسخ الاحتياطي هذه: {$a}';
$string['zipcreatefailed'] = 'تعذر إنشاء ملف ZIP.';
