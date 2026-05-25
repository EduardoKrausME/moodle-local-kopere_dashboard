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

$string['back_mapping'] = 'العودة إلى المطابقة';
$string['cannotreadcsv'] = 'تعذرت قراءة ملف CSV.';
$string['cap_manage'] = 'إدارة عمليات استيراد المستخدمين';
$string['cap_manage_desc'] = 'رفع ملفات CSV، ومعاينة الصفوف، وتنفيذ عمليات استيراد المستخدمين داخل Kopere Dashboard.';
$string['confirm_import'] = 'استيراد المستخدمين الآن';
$string['course_not_found'] = 'لم يتم العثور على المقرر.';
$string['customfields'] = 'حقول الملف الشخصي المخصصة';
$string['filename'] = 'الملف';
$string['group_not_found'] = 'لم يتم العثور على المجموعة في المقرر المحدد.';
$string['idnumbercourse'] = 'رقم تعريف المقرر';
$string['invalidcsv'] = 'لا يبدو أن الملف ملف CSV صالح يحتوي على رؤوس.';
$string['invalidemail'] = 'عنوان البريد الإلكتروني غير صالح.';
$string['invalidfiletype'] = 'يتم قبول ملفات .csv أو .txt فقط.';
$string['invalidtoken'] = 'رمز الاستيراد غير صالح أو منتهي الصلاحية.';
$string['invalidusername'] = 'قيمة اسم المستخدم غير صالحة.';
$string['manage_intro'] = 'ارفع ملف CSV لإنشاء مستخدمين، وإعادة استخدام الحسابات الموجودة، وملء حقول الملف الشخصي المخصصة، واختيارياً تسجيلهم في المقررات والمجموعات.';
$string['manualinstance_missing'] = 'لا يحتوي المقرر {$a} على نسخة تسجيل يدوي مفعلة.';
$string['manualplugin_missing'] = 'إضافة التسجيل اليدوي غير متاحة في موقع Moodle هذا.';
$string['mappingerror_email'] = 'طابق عمود البريد الإلكتروني.';
$string['mappingerror_firstname'] = 'طابق عمود الاسم الأول (أو عمود الاسم الكامل).';
$string['menu_desc'] = 'استورد المستخدمين عبر CSV، وعاين النتيجة، وأنشئ الحسابات، واختيارياً سجلهم في المقررات/المجموعات.';
$string['menu_title'] = 'استيراد المستخدمين';
$string['missingemail'] = 'البريد الإلكتروني مفقود.';
$string['missingfirstname'] = 'الاسم الأول مفقود.';
$string['missingtempfile'] = 'لم يعد ملف CSV المؤقت موجوداً.';
$string['missingusername'] = 'اسم المستخدم مفقود.';
$string['pluginname'] = 'استيراد المستخدمين';
$string['preview_intro'] = 'راجع الصفوف الأولى، وطابق كل عمود في CSV، وشغل معاينة تجريبية قبل الاستيراد.';
$string['preview_title'] = 'المعاينة والمطابقة';
$string['result_alreadyenrolled'] = 'مسجل بالفعل';
$string['result_courseenrolled'] = 'تم التسجيل في {$a}';
$string['result_groupadded'] = 'تمت الإضافة إلى المجموعة {$a}';
$string['result_usercreated'] = 'تم إنشاء المستخدم';
$string['result_userexists'] = 'كان المستخدم موجوداً بالفعل';
$string['run_preview'] = 'تشغيل المعاينة';
$string['saveuploaderror'] = 'تعذر حفظ الملف المرفوع في التخزين المؤقت لـ Moodle.';
$string['select_column'] = 'حدد عموداً';
$string['separator'] = 'الفاصل المكتشف';
$string['separator_comma'] = 'فاصلة (,)';
$string['separator_semicolon'] = 'فاصلة منقوطة (;)';
$string['separator_tab'] = 'تبويب';
$string['shortnamecourse'] = 'الاسم المختصر/الاسم الكامل للمقرر';
$string['start_over'] = 'بدء استيراد جديد';
$string['status_created'] = 'تم الإنشاء';
$string['status_error'] = 'خطأ';
$string['status_existing'] = 'مستخدم موجود';
$string['status_ok'] = 'جاهز';
$string['status_willcreate'] = 'سيتم إنشاؤه';
$string['summary_create'] = 'سيتم إنشاؤه';
$string['summary_created'] = 'المستخدمون الذين تم إنشاؤهم';
$string['summary_enrolled'] = 'تسجيلات جديدة';
$string['summary_errors'] = 'أخطاء';
$string['summary_existing'] = 'موجودون';
$string['summary_total'] = 'الصفوف';
$string['summary_withcourse'] = 'صفوف تحتوي على مقرر';
$string['table_course'] = 'المقرر';
$string['table_email'] = 'البريد الإلكتروني';
$string['table_firstname'] = 'الاسم الأول';
$string['table_group'] = 'المجموعة';
$string['table_lastname'] = 'اسم العائلة';
$string['table_line'] = 'السطر';
$string['table_message'] = 'الرسالة';
$string['table_status'] = 'الحالة';
$string['table_username'] = 'اسم المستخدم';
$string['tip_detectseparator'] = 'تكتشف الإضافة تلقائياً الملفات المفصولة بفاصلة منقوطة أو فاصلة أو تبويب.';
$string['tip_existing'] = 'لا يتم تكرار المستخدمين الموجودين. ما زال بإمكانهم تلقي بيانات الحقول المخصصة والتسجيلات في المقررات.';
$string['tip_headers'] = 'يتم التعامل مع أول صف في CSV كصف رؤوس.';
$string['tip_password'] = 'إذا لم يكن لدى مستخدم جديد كلمة مرور أو كانت قصيرة جداً، يتم إنشاء كلمة مرور عشوائية وتفعيل فرض تغيير كلمة المرور.';
$string['upload_csv'] = 'رفع ملف CSV';
$string['upload_submit'] = 'متابعة';
$string['uploaderror'] = 'أرسل ملف CSV صالحاً.';
