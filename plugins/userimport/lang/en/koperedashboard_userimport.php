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
 * Strings for component koperedashboard_userimport.
 *
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_mapping'] = 'Back to mapping';
$string['cannotreadcsv'] = 'Could not read the CSV file.';
$string['cap_manage'] = 'Manage user imports';
$string['cap_manage_desc'] = 'Upload CSV files, preview rows and execute user imports inside Kopere Dashboard.';
$string['confirm_import'] = 'Import users now';
$string['course_not_found'] = 'Course not found.';
$string['customfields'] = 'Custom profile fields';
$string['filename'] = 'File';
$string['group_not_found'] = 'Group not found in the selected course.';
$string['idnumbercourse'] = 'Course idnumber';
$string['invalidcsv'] = 'The file does not look like a valid CSV with headers.';
$string['invalidemail'] = 'Invalid email address.';
$string['invalidfiletype'] = 'Only .csv or .txt files are accepted.';
$string['invalidtoken'] = 'The import token is invalid or expired.';
$string['invalidusername'] = 'Invalid username value.';
$string['manage_intro'] = 'Upload a CSV file to create users, reuse existing accounts, fill custom profile fields and optionally enrol them in courses and groups.';
$string['manualinstance_missing'] = 'The course {$a} does not have an enabled manual enrolment instance.';
$string['manualplugin_missing'] = 'The manual enrolment plugin is not available in this Moodle site.';
$string['mappingerror_email'] = 'Map the email column.';
$string['mappingerror_firstname'] = 'Map the firstname column (or the full name column).';
$string['menu_desc'] = 'Import users by CSV, preview the result, create accounts and optionally enrol them in courses/groups.';
$string['menu_title'] = 'User import';
$string['missingemail'] = 'Missing email.';
$string['missingfirstname'] = 'Missing firstname.';
$string['missingtempfile'] = 'The temporary CSV file no longer exists.';
$string['missingusername'] = 'Missing username.';
$string['pluginname'] = 'User import';
$string['preview_intro'] = 'Review the first rows, map each CSV column and run a dry preview before importing.';
$string['preview_title'] = 'Preview and mapping';
$string['result_alreadyenrolled'] = 'Already enrolled';
$string['result_courseenrolled'] = 'Enrolled in {$a}';
$string['result_groupadded'] = 'Added to group {$a}';
$string['result_usercreated'] = 'User created';
$string['result_userexists'] = 'User already existed';
$string['run_preview'] = 'Run preview';
$string['saveuploaderror'] = 'Could not save the uploaded file into Moodle temporary storage.';
$string['select_column'] = 'Select a column';
$string['separator'] = 'Detected separator';
$string['separator_comma'] = 'Comma (,)';
$string['separator_semicolon'] = 'Semicolon (;)';
$string['separator_tab'] = 'Tab';
$string['shortnamecourse'] = 'Course shortname/fullname';
$string['start_over'] = 'Start a new import';
$string['status_created'] = 'Created';
$string['status_error'] = 'Error';
$string['status_existing'] = 'Existing user';
$string['status_ok'] = 'Ready';
$string['status_willcreate'] = 'Will create';
$string['summary_create'] = 'Will create';
$string['summary_created'] = 'Created users';
$string['summary_enrolled'] = 'New enrolments';
$string['summary_errors'] = 'Errors';
$string['summary_existing'] = 'Existing';
$string['summary_total'] = 'Rows';
$string['summary_withcourse'] = 'Rows with course';
$string['table_course'] = 'Course';
$string['table_email'] = 'Email';
$string['table_firstname'] = 'Firstname';
$string['table_group'] = 'Group';
$string['table_lastname'] = 'Lastname';
$string['table_line'] = 'Line';
$string['table_message'] = 'Message';
$string['table_status'] = 'Status';
$string['table_username'] = 'Username';
$string['tip_detectseparator'] = 'The plugin auto-detects semicolon, comma and tab-separated files.';
$string['tip_existing'] = 'Existing users are not duplicated. They can still receive custom field data and course enrolments.';
$string['tip_headers'] = 'The first CSV row is treated as the header row.';
$string['tip_password'] = 'If a new user has no password or a very short one, a random password is generated and force-password-change is enabled.';
$string['upload_csv'] = 'Upload CSV file';
$string['upload_submit'] = 'Continue';
$string['uploaderror'] = 'Send a valid CSV file.';
