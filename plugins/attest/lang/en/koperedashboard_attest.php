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
 * koperedashboard_attest.php
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actions_title'] = 'Actions';
$string['already_has_valid'] = 'A valid one already exists';
$string['attest:manage'] = 'Manage attestations';
$string['attest:view'] = 'View attestations';
$string['audit_issue_create'] = 'Attestation generated.';
$string['audit_tpl_create'] = 'Template created.';
$string['audit_tpl_delete'] = 'Template deleted.';
$string['audit_tpl_update'] = 'Template updated.';
$string['available_desc'] = 'Only attestations from courses with enrolment.';
$string['available_title'] = 'Available attestations';
$string['cap_manage'] = 'Attestation manager';
$string['cap_manage_desc'] = 'Can create and manage attestation templates.';
$string['cap_view'] = 'Access to attestations';
$string['cap_view_desc'] = 'Can view and generate their own attestations.';
$string['choose_course'] = 'Choose a course';
$string['choose_course_help'] = 'First select the course to see the available attestations.';
$string['choose_course_placeholder'] = 'Select a course';
$string['close_modal'] = 'Close';
$string['course_removed'] = 'Course removed';
$string['delete_template'] = 'Delete';
$string['edit_template'] = 'Edit template';
$string['empty_available'] = 'No attestations are available for your enrolments.';
$string['empty_available_for_course'] = 'No attestations are available for the selected course.';
$string['empty_available_select_course'] = 'Select a course to list the available attestations.';
$string['empty_courses'] = 'You are not currently enrolled in any courses.';
$string['empty_issued'] = 'You have not generated any attestations yet.';
$string['field_active'] = 'Active';
$string['field_allcourses'] = 'Valid for all courses';
$string['field_courses'] = 'Courses';
$string['field_courses_help'] = 'Select the courses for which this template can be used. If "Valid for all courses" is checked, this setting will be ignored.';
$string['field_html'] = 'HTML template';
$string['field_html_help'] = 'Enter the HTML that will be used to generate the attestation PDF. You can use the available placeholders to insert dynamic student, course, date, and validity data.';
$string['field_name'] = 'Name';
$string['field_validmonths'] = 'Valid for (months)';
$string['footer_created'] = 'Created on';
$string['footer_desc'] = 'Electronic document with validation by QR Code or the link below.';
$string['footer_hash'] = 'Signature';
$string['footer_title'] = 'Digital signature';
$string['footer_validuntil'] = 'Valid until';
$string['generate'] = 'Generate PDF';
$string['generate_new'] = 'Generate attestation';
$string['generate_new_button'] = 'Generate new attestation';
$string['home_kpi_subtitle'] = 'Issued and valid documents';
$string['home_kpi_title'] = 'Valid attestations';
$string['issued_desc'] = 'View the attestations already generated and whether they are still valid.';
$string['issued_title'] = 'Generated attestations';
$string['manage_title'] = 'Attestation templates';
$string['menu_desc'] = 'Generate PDF attestations based on HTML templates.';
$string['menu_title'] = 'Attestations';
$string['new_template'] = 'New template';
$string['open_attestation'] = 'Open attestation';
$string['open_valid'] = 'Open valid attestation';
$string['placeholders_title'] = 'Available placeholders';
$string['pluginname'] = 'Attestations';
$string['recreate_valid'] = 'Recreate valid attestation';
$string['status_expired'] = 'Expired';
$string['status_notgenerated'] = 'Not generated yet';
$string['status_title'] = 'Status';
$string['status_valid'] = 'Valid';
$string['status_valid_expiring'] = 'Valid and close to expiration';
$string['student_title'] = 'My attestations';
$string['studentcard'] = 'Student ID card';
$string['studentcard_back_placeholder_description'] = 'Translated explanatory text displayed on the validation side.';
$string['studentcard_back_placeholder_fullname'] = 'Full name of the student.';
$string['studentcard_back_placeholder_hashlabel'] = 'Translated label displayed before the validation code.';
$string['studentcard_back_placeholder_qrcode'] = 'Validation QR code as an embedded Base64 PNG data URI.';
$string['studentcard_back_placeholder_sitefullname'] = 'Formatted full name of the Moodle site.';
$string['studentcard_back_placeholder_title'] = 'Translated title of the validation side.';
$string['studentcard_back_placeholder_userid'] = 'Numeric Moodle user ID.';
$string['studentcard_back_placeholder_validationcode'] = 'Public validation code generated for the student ID card.';
$string['studentcard_back_placeholder_verifyurl'] = 'Public URL used to validate the student ID card.';
$string['studentcard_back_placeholder_wwwroot'] = 'Base URL of the Moodle site.';
$string['studentcard_desc'] = '';
$string['studentcard_front_placeholder_coursefullname'] = 'Formatted full name of the first visible course in which the student is enrolled.';
$string['studentcard_front_placeholder_courselabel'] = 'Translated course label.';
$string['studentcard_front_placeholder_cpf'] = 'CPF retrieved from the custom profile field, using the identification number as a fallback.';
$string['studentcard_front_placeholder_cpflabel'] = 'CPF field label.';
$string['studentcard_front_placeholder_email'] = 'Student email address.';
$string['studentcard_front_placeholder_fullname'] = 'Full name of the student.';
$string['studentcard_front_placeholder_photo'] = 'Profile photo as an embedded Base64 image data URI.';
$string['studentcard_front_placeholder_title'] = 'Translated title of the student ID card.';
$string['studentcard_front_placeholder_userid'] = 'Numeric Moodle user ID.';
$string['studentcard_settings_back'] = 'Back template';
$string['studentcard_settings_back_template'] = 'Back Mustache template';
$string['studentcard_settings_back_template_help'] = 'Mustache and HTML rendered by TCPDF on page 2 of the student ID card. Keep the validation URL or QR code in the layout so the document remains verifiable.';
$string['studentcard_settings_back_variables'] = 'Variables available on the back';
$string['studentcard_settings_back_variables_desc'] = 'These Mustache variables are replaced when generating the validation side of the PDF.';
$string['studentcard_settings_description'] = 'Edit the two Mustache/HTML templates used to generate the student ID card PDF. The front contains the student identity and course; the back contains the code, public link, and validation QR code.';
$string['studentcard_settings_front'] = 'Front template';
$string['studentcard_settings_front_template'] = 'Front Mustache template';
$string['studentcard_settings_front_template_help'] = 'Mustache and HTML rendered by TCPDF on page 1 of the student ID card. The image must be kept through the photo variable, provided as an embedded data URI.';
$string['studentcard_settings_front_variables'] = 'Variables available on the front';
$string['studentcard_settings_front_variables_desc'] = 'These Mustache variables are replaced when generating the identification side of the PDF.';
$string['studentcard_settings_menu'] = 'Student ID card template';
$string['studentcard_settings_title'] = 'Student ID card template';
$string['studentcardgenerate'] = 'Generate PDF';
$string['studentcardnophoto'] = '<h5>You do not have a profile photo.</h5>Edit your profile and add a photo to generate your student ID card.';
$string['studentcardnovisiblecourses'] = 'The student ID card is only available to users enrolled in visible courses.';
$string['studentcardsignaturedesc'] = 'This page contains the validation code and the public validator link for the student ID card PDF.';
$string['studentcardsignaturetitle'] = 'Digital signature and validator';
$string['studentcardvalidationinvalid'] = 'Invalid validation code.';
$string['studentcardvalidationlabel'] = 'Validator';
$string['studentcardvalidationtitle'] = 'Student ID card validation';
$string['studentcardvalidationvalid'] = 'Valid student ID card.';
$string['template_removed'] = 'Template removed';
$string['title_view'] = 'Attestations';
$string['verify_title'] = 'Attestation verification';
