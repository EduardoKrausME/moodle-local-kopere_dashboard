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

$string['actions_title'] = 'Действия';
$string['already_has_valid'] = 'Действительная справка уже существует';
$string['attest:manage'] = 'Управлять справками';
$string['attest:view'] = 'Просматривать справки';
$string['audit_issue_create'] = 'Справка создана.';
$string['audit_tpl_create'] = 'Шаблон создан.';
$string['audit_tpl_delete'] = 'Шаблон удалён.';
$string['audit_tpl_update'] = 'Шаблон обновлён.';
$string['available_desc'] = 'Только справки из курсов с зачислением.';
$string['available_title'] = 'Доступные справки';
$string['cap_manage'] = 'Менеджер справок';
$string['cap_manage_desc'] = 'Может создавать шаблоны справок и управлять ими.';
$string['cap_view'] = 'Доступ к справкам';
$string['cap_view_desc'] = 'Может просматривать и создавать собственные справки.';
$string['choose_course'] = 'Выберите курс';
$string['choose_course_help'] = 'Сначала выберите курс, чтобы увидеть доступные справки.';
$string['choose_course_placeholder'] = 'Выберите курс';
$string['close_modal'] = 'Закрыть';
$string['course_removed'] = 'Курс удалён';
$string['delete_template'] = 'Удалить';
$string['edit_template'] = 'Редактировать шаблон';
$string['empty_available'] = 'Для ваших зачислений нет доступных справок.';
$string['empty_available_for_course'] = 'Для выбранного курса нет доступных справок.';
$string['empty_available_select_course'] = 'Выберите курс, чтобы показать доступные справки.';
$string['empty_courses'] = 'В настоящее время вы не зачислены ни на один курс.';
$string['empty_issued'] = 'Вы ещё не создали ни одной справки.';
$string['field_active'] = 'Активно';
$string['field_allcourses'] = 'Действительно для всех курсов';
$string['field_courses'] = 'Курсы';
$string['field_courses_help'] = 'Выберите курсы, для которых можно использовать этот шаблон. Если отмечено "Действительно для всех курсов", этот параметр будет проигнорирован.';
$string['field_html'] = 'HTML-шаблон';
$string['field_html_help'] = 'Введите HTML, который будет использоваться для создания PDF-справки. Вы можете использовать доступные заполнители для вставки динамических данных студента, курса, даты и срока действия.';
$string['field_name'] = 'Название';
$string['field_validmonths'] = 'Действительно в течение (месяцев)';
$string['footer_created'] = 'Создано';
$string['footer_desc'] = 'Электронный документ с проверкой по QR-коду или по ссылке ниже.';
$string['footer_hash'] = 'Подпись';
$string['footer_title'] = 'Цифровая подпись';
$string['footer_validuntil'] = 'Действительно до';
$string['generate'] = 'Создать PDF';
$string['generate_new'] = 'Создать справку';
$string['generate_new_button'] = 'Создать новую справку';
$string['home_kpi_subtitle'] = 'Выданные и действительные документы';
$string['home_kpi_title'] = 'Действительные справки';
$string['issued_desc'] = 'Просмотрите уже созданные справки и проверьте, действительны ли они.';
$string['issued_title'] = 'Созданные справки';
$string['manage_title'] = 'Шаблоны справок';
$string['menu_desc'] = 'Создание PDF-справок на основе HTML-шаблонов.';
$string['menu_title'] = 'Справки';
$string['new_template'] = 'Новый шаблон';
$string['open_attestation'] = 'Открыть справку';
$string['open_valid'] = 'Открыть действительную справку';
$string['placeholders_title'] = 'Доступные заполнители';
$string['pluginname'] = 'Справки';
$string['recreate_valid'] = 'Повторно создать действительную справку';
$string['status_expired'] = 'Истёк срок действия';
$string['status_notgenerated'] = 'Ещё не создано';
$string['status_title'] = 'Статус';
$string['status_valid'] = 'Действительно';
$string['status_valid_expiring'] = 'Действительно и скоро истекает';
$string['student_title'] = 'Мои справки';
$string['studentcard'] = 'Студенческий билет';
$string['studentcard_back_placeholder_description'] = 'Переведённый пояснительный текст, отображаемый на стороне проверки.';
$string['studentcard_back_placeholder_fullname'] = 'Полное имя учащегося.';
$string['studentcard_back_placeholder_hashlabel'] = 'Переведённая метка, отображаемая перед кодом проверки.';
$string['studentcard_back_placeholder_qrcode'] = 'QR-код проверки в виде встроенного URI данных PNG Base64.';
$string['studentcard_back_placeholder_sitefullname'] = 'Отформатированное полное название сайта Moodle.';
$string['studentcard_back_placeholder_title'] = 'Переведённый заголовок стороны проверки.';
$string['studentcard_back_placeholder_userid'] = 'Числовой идентификатор пользователя Moodle.';
$string['studentcard_back_placeholder_validationcode'] = 'Открытый код проверки, созданный для студенческого билета.';
$string['studentcard_back_placeholder_verifyurl'] = 'Открытый URL-адрес, используемый для проверки студенческого билета.';
$string['studentcard_back_placeholder_wwwroot'] = 'Базовый URL-адрес сайта Moodle.';
$string['studentcard_desc'] = '';
$string['studentcard_front_placeholder_coursefullname'] = 'Отформатированное полное название первого видимого курса, на который зачислен учащийся.';
$string['studentcard_front_placeholder_courselabel'] = 'Переведённая метка курса.';
$string['studentcard_front_placeholder_cpf'] = 'CPF, полученный из пользовательского поля профиля; при отсутствии используется идентификационный номер.';
$string['studentcard_front_placeholder_cpflabel'] = 'Метка поля CPF.';
$string['studentcard_front_placeholder_email'] = 'Адрес электронной почты учащегося.';
$string['studentcard_front_placeholder_fullname'] = 'Полное имя учащегося.';
$string['studentcard_front_placeholder_photo'] = 'Фотография профиля в виде встроенного URI данных изображения Base64.';
$string['studentcard_front_placeholder_title'] = 'Переведённый заголовок студенческого билета.';
$string['studentcard_front_placeholder_userid'] = 'Числовой идентификатор пользователя Moodle.';
$string['studentcard_settings_back'] = 'Шаблон обратной стороны';
$string['studentcard_settings_back_template'] = 'Mustache обратной стороны';
$string['studentcard_settings_back_template_help'] = 'Mustache и HTML, обрабатываемые TCPDF на странице 2 студенческого билета. Сохраните URL-адрес проверки или QR-код в макете, чтобы документ оставался проверяемым.';
$string['studentcard_settings_back_variables'] = 'Переменные, доступные на обратной стороне';
$string['studentcard_settings_back_variables_desc'] = 'Эти переменные Mustache заменяются при создании стороны проверки PDF.';
$string['studentcard_settings_description'] = 'Измените два шаблона Mustache/HTML, используемые для создания PDF студенческого билета. Лицевая сторона содержит данные учащегося и курс; обратная сторона содержит код, открытую ссылку и QR-код проверки.';
$string['studentcard_settings_front'] = 'Шаблон лицевой стороны';
$string['studentcard_settings_front_template'] = 'Mustache лицевой стороны';
$string['studentcard_settings_front_template_help'] = 'Mustache и HTML, обрабатываемые TCPDF на странице 1 студенческого билета. Изображение должно сохраняться с помощью переменной фотографии, предоставляемой как встроенный URI данных.';
$string['studentcard_settings_front_variables'] = 'Переменные, доступные на лицевой стороне';
$string['studentcard_settings_front_variables_desc'] = 'Эти переменные Mustache заменяются при создании идентификационной стороны PDF.';
$string['studentcard_settings_menu'] = 'Шаблон студенческого билета';
$string['studentcard_settings_title'] = 'Шаблон студенческого билета';
$string['studentcardgenerate'] = 'Создать PDF';
$string['studentcardnophoto'] = '<h5>У вас нет фотографии профиля.</h5>Отредактируйте профиль и добавьте фотографию, чтобы создать студенческий билет.';
$string['studentcardnovisiblecourses'] = 'Студенческий билет доступен только пользователям, зачисленным на видимые курсы.';
$string['studentcardsignaturedesc'] = 'Эта страница содержит код проверки и публичную ссылку для проверки PDF студенческого билета.';
$string['studentcardsignaturetitle'] = 'Цифровая подпись и валидатор';
$string['studentcardvalidationinvalid'] = 'Недействительный код проверки.';
$string['studentcardvalidationlabel'] = 'Валидатор';
$string['studentcardvalidationtitle'] = 'Проверка студенческого билета';
$string['studentcardvalidationvalid'] = 'Действительный студенческий билет.';
$string['template_removed'] = 'Шаблон удалён';
$string['title_view'] = 'Справки';
$string['verify_title'] = 'Проверка справки';
