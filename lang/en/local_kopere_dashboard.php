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
 * @package  local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['modulename'] = 'Kopere Dashboard';
$string['pluginname'] = 'Kopere Dashboard';
$string['kopere_dashboard:view'] = 'View Kopere Dashboard';
$string['kopere_dashboard:manage'] = 'Manage Kopere Dashboard';
$string['dashboard'] = 'Dashboard';
$string['settings'] = 'Settings';
$string['close'] = 'Close';
$string['crontask_tmp'] = 'Cron clear tmp folder';
$string['crontask_performance'] = 'Cron to save performance data';
$string['crontask_db_report_login'] = 'Cron to store user logins in temporary table';

$string['kopere_dashboard_menu'] = 'Show menu in top bar';
$string['kopere_dashboard_menu_desc'] = 'If checked, the top menu contains a link to the Kopere Dashboard';
$string['kopere_dashboard_menuwebpages'] = 'Show static pages for logged-in users';
$string['kopere_dashboard_menuwebpages_desc'] = 'Enable this option to display static pages in the navigation menu for logged-in users.';

$string['add_report_user_fields'] = 'In reports add the following user fields';
$string['add_report_user_fields_alt'] = 'Select the user fields you want to show in Kopere reports.<br>
     Hold down the CTRL key to select multiple fields.';

$string['kopere_dashboard_monitor'] = 'Server Monitor';
$string['kopere_dashboard_monitor_desc'] = 'Do you want to display the server monitor at the top of Kopere?';

$string['kopere_dashboard_pagefonts'] = 'Google Extra Fonts';
$string['kopere_dashboard_pagefonts_desc'] = 'Add here the @import link from Google for extra fonts.<br>You can put multiple import.<br><a href="https://fonts.google.com/selection/embed" target="google">Embed code</a><br><img src="{$a}" style="max-width: 100%;width: 420px;">';

$string['integracaoroot'] = 'Integration';

$string['messageprovider:kopere_dashboard_messages'] = 'Send Notifications';
$string['kopere_dashboard:emailconfirmsubmission'] = 'Send Notifications';

$string['open_dashboard'] = 'Open Dashboard';

$string['dateformat'] = '%d %B %Y, %I:%M %p';
$string['datetime'] = '%d/%m/%Y, %H:%M';
$string['php_datetime'] = 'm/d/Y H:i';

$string['help_title'] = 'Help with this page';

$string['colors'] = 'Colors';
$string['background'] = 'Backround';
$string['color_red'] = 'Red';
$string['color_blue'] = 'Blue';
$string['color_green'] = 'Green';
$string['color_yellow'] = 'Yellow';
$string['color_orange'] = 'Orange';
$string['color_grey'] = 'Grey';
$string['color_purple'] = 'Purple';
$string['color_brown'] = 'Brown';
$string['filemanager_title'] = 'File Manager';

// DataTables.
$string['datatables_sEmptyTable'] = 'No records found';
$string['datatables_sInfo'] = '_START_ to _END_ of _TOTAL_';
$string['datatables_sInfoEmpty'] = '0 records';
$string['datatables_sInfoFiltered'] = '(Filtered from _MAX_ records)';
$string['datatables_sInfoPostFix'] = '';
$string['datatables_sInfoThousands'] = '.';
$string['datatables_sLengthMenu'] = '_MENU_ per page';
$string['datatables_sLoadingRecords'] = 'Loading ...';
$string['datatables_sProcessing'] = 'Processing ...';
$string['datatables_sErrorMessage'] = '<strong>Error loading data</strong><div>Trying again in {$a} seconds</div>';
$string['datatables_sZeroRecords'] = 'No records found';
$string['datatables_sSearch'] = 'Search';
$string['datatables_oPaginate_sNext'] = 'Next';
$string['datatables_oPaginate_sPrevious'] = 'Previous';
$string['datatables_oPaginate_sFirst'] = 'First';
$string['datatables_oPaginate_sLast'] = 'Last';
$string['datatables_oAria_sSortAscending'] = ': Sorting Columns in ascending order';
$string['datatables_oAria_sSortDescending'] = ': Sorting columns in descending order';
$string['datatables_buttons_print_text'] = 'Print';
$string['datatables_buttons_copy_text'] = 'Copy data';
$string['datatables_buttons_csv_text'] = 'Download CSV';
$string['datatables_buttons_copySuccess1'] = 'Copied one row to clipboard';
$string['datatables_buttons_copySuccess_'] = 'Copied %d rows to clipboard';
$string['datatables_buttons_copyTitle'] = 'Copy to clipboard';
$string['datatables_buttons_copyKeys'] = 'Press <i>ctrl</i> or <i>\u2318</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.';
$string['datatables_buttons_select_rows_'] = '%d rows selected';
$string['datatables_buttons_select_rows1'] = '1 row selected';

// Util/navigation.
$string['navigation_page'] = 'Page {$a->atualPage} of {$a->countPages}';

// About.
$string['about_title'] = 'About';
$string['about_project'] = 'Open source project developed and maintained by';
$string['about_code'] = 'Code available in';
$string['about_help'] = 'Help is on';
$string['about_bug'] = 'If you found some BUG or would like to suggest improvements open one issue';

// Backup.
$string['backup_title'] = 'Backup';
$string['backup_windows'] = 'Not available on Windows Server!';
$string['backup_hours'] = 'Do not run backup at peak times!';
$string['backup_sleep'] = 'Backup may take several minutes to execute.';
$string['backup_newnow'] = 'Create new Backup now';
$string['backup_newsqlnow'] = 'Create new database backup now';
$string['backup_noshell'] = 'shell_exec function is disabled!';
$string['backup_list'] = 'List of backups';
$string['backup_list_file'] = 'File';
$string['backup_list_created'] = 'Created in';
$string['backup_list_size'] = 'Size';
$string['backup_list_action'] = 'Action';
$string['backup_none'] = 'No backups found!';
$string['backup_execute_success'] = 'Backup created successfully!';
$string['backup_execute_exec'] = 'Execution of Backup';
$string['backup_execute_date'] = 'Generation date:';
$string['backup_execute_database'] = 'Database:';
$string['backup_execute_table'] = 'Running Table Backup';
$string['backup_execute_structure'] = 'Structure for table';
$string['backup_execute_dump'] = 'Dump table data';
$string['backup_execute_dump_error'] = 'Error catching table';
$string['backup_execute_complete'] = 'Backup completed!';
$string['backup_returnlist'] = 'Back to the Backups list';
$string['backup_deletesucessfull'] = 'Backup deleted successfully!';
$string['backup_deleting'] = 'Excluding Backup';
$string['backup_delete_confirm'] = 'Backup Deletion';
$string['backup_delete_title'] = 'Do you really want to delete the <strong>{$a}</strong>';
$string['backup_notound'] = 'File not found!';

// Report_benchmark.
$string['benchmark_title'] = 'Performance test';
$string['benchmark_based'] = 'Plug-in based';
$string['benchmark_info'] = '<p>This test can take up to 1 minute to execute.</p><p>Try to do more than one test for an average.</p><p>And, do not run in peak times.</p>';
$string['benchmark_execute'] = 'Run the test';
$string['benchmark_executing'] = 'Running the test';
$string['benchmark_title2'] = 'Hosting performance test';
$string['benchmark_timetotal'] = 'Total time:';
$string['benchmark_decription'] = 'Description';
$string['benchmark_timesec'] = 'Time, in seconds';
$string['benchmark_seconds'] = 'seconds';
$string['benchmark_max'] = 'Maximum acceptable value';
$string['benchmark_critical'] = 'Critical limit';
$string['benchmark_testconf'] = 'Test Moodle Settings';
$string['benchmark_testconf_problem'] = 'Problem';
$string['benchmark_testconf_status'] = 'Status';
$string['benchmark_testconf_description'] = 'Description';
$string['benchmark_testconf_action'] = 'Action';

$string['cloadname'] = 'Moodle loading time';
$string['cloadmoreinfo'] = 'Run the configuration file &laquo;config.php&raquo;';
$string['processorname'] = 'Function called many times';
$string['processormoreinfo'] = 'A function is called in a loop to test processor speed';
$string['filereadname'] = 'Reading files';
$string['filereadmoreinfo'] = 'Test the read speed in Moodle\'s temporary folder';
$string['filewritename'] = 'Creating files';
$string['filewritemoreinfo'] = 'Test the write speed in Moodle\'s temporary folder';
$string['coursereadname'] = 'Reading course';
$string['coursereadmoreinfo'] = 'Test the read speed to read a course';
$string['coursewritename'] = 'Writing course';
$string['coursewritemoreinfo'] = 'Test the database speed to write a course';
$string['querytype1name'] = 'Complex request (n°1)';
$string['querytype1moreinfo'] = 'Test the database speed to execute a complex request';
$string['querytype2name'] = 'Complex request (n°2)';
$string['querytype2moreinfo'] = 'Test the database speed to execute a complex request';
$string['loginguestname'] = 'Time to connect with the guest account';
$string['loginguestmoreinfo'] = 'Measuring the time to load the login page with the guest account';
$string['loginusername'] = 'Time to connect with a fake user account';
$string['loginusermoreinfo'] = 'Measuring the time to load the login page with a fake user account';

// Performancemonitor.
$string['cachedef_performancemonitor_cache'] = 'Performance monitor cache';
$string['performancemonitor_cpu'] = 'CPU Usage';
$string['performancemonitor_memory'] = 'Memory';
$string['performancemonitor_hd'] = 'Moodledata';
$string['performancemonitor_performance'] = 'Performance';
$string['performancemonitor_min'] = '{$a} min:';

// Courses.
$string['courses_title'] = 'Courses';
$string['courses_title1'] = 'Course List';
$string['courses_name'] = 'Course Name';
$string['courses_shortname'] = 'Short Name';
$string['courses_visible'] = 'Visible';
$string['courses_invisible'] = 'Hidden';
$string['courses_enrol'] = 'Nº of enrolled students';
$string['courses_invalid'] = 'Invalid CourseID!';
$string['courses_notound'] = 'Course not found!';
$string['courses_sumary'] = 'Summary';
$string['courses_edit'] = 'Edit';
$string['courses_access'] = 'Access';
$string['courses_titleenrol'] = 'Students enrolled';
$string['courses_enrol_new'] = 'Register new student and enroll';
$string['courses_enrol_new_form'] = 'Register new student before enrolling';
$string['courses_user_create'] = 'Register new student';
$string['courses_validate_user'] = 'Check if student exists or register them';
$string['courses_student_name'] = 'Student name';
$string['courses_student_email'] = 'Student email';
$string['courses_student_password'] = 'Suggested password for the student';
$string['courses_student_ok'] = 'User created successfully:<br><strong>Login:</strong> {$a->login}<br><strong>Password:</strong> {$a->password}';
$string['courses_student_cadastrado'] = '<div>User is already registered in the course. <a href="{$a}">Click here</a> to check';
$string['courses_student_cadastrar'] = 'Enroll student in this course';
$string['courses_student_cadastrar_ok'] = 'User enrolled successfully!';
$string['courses_student_status'] = 'Registration Status';
$string['courses_page_title'] = 'Pages already created';
$string['courses_page_create'] = 'Create page based on this summary';

// Reports.
$string['cachedef_report_getdata_cache'] = 'report getdata cache';
$string['reports_title'] = 'Reports';
$string['reports_download'] = 'Download this data';
$string['reports_selectcourse'] = 'Select the course to generate the report';
$string['reports_notfound'] = 'Report not found!';
$string['reports_reportcat_badge'] = 'Badges Report';
$string['reports_reportcat_courses'] = 'Course report';
$string['reports_reportcat_enrol_cohort'] = 'Cohort Report';
$string['reports_reportcat_enrol_guest'] = 'Visitors Report';
$string['reports_reportcat_server'] = 'System report';
$string['reports_reportcat_user'] = 'User Report';
$string['reports_report_badge-1'] = 'All Badges available in Moodle';
$string['reports_report_badge-2'] = 'All Badges Awarded by Users';
$string['reports_report_courses-1'] = 'Progress with completion percentage';
$string['reports_report_courses-2'] = 'Courses that have groups enabled';
$string['reports_report_courses-3'] = 'Course access report';
$string['reports_report_courses-4'] = 'Course access report with grades';
$string['reports_report_courses-5'] = 'Last access to course';
$string['reports_report_enrol_cohort-1'] = 'Cohorts and users';
$string['reports_report_enrol_guest-1'] = 'Guest Logins Report';
$string['reports_report_server-1'] = 'Disk Usage Report';
$string['reports_report_user-1'] = 'Student count in each course';
$string['reports_report_user-2'] = 'Course Completion with Criteria';
$string['reports_report_user-3'] = 'Daily user access report';
$string['reports_report_user-4'] = 'Student logins report';
$string['reports_report_user-5'] = 'Users who have never logged in';
$string['reports_report_user-6'] = 'Users who completed course';
$string['reports_report_user-7'] = 'Registered users, who do not log in to the Course';
$string['reports_report_user-8'] = 'All users';
$string['reports_timecreated'] = 'Registered in';
$string['reports_coursesize'] = 'Course Files';
$string['reports_modulessize'] = 'Modules Files';
$string['reports_lastlogin'] = 'Login to';
$string['reports_cohort'] = 'Name of Cohorts';
$string['reports_groupnode'] = 'Group Mode';
$string['reports_groupname'] = 'Group Name';
$string['reports_datastudents'] = 'Student Data';
$string['reports_datacourses'] = 'Course Data';
$string['reports_coursecreated'] = 'Date of enrolment';
$string['reports_activitiescomplete'] = 'Activities Completed';
$string['reports_activitiesassigned'] = 'Assigned Activities';
$string['reports_coursecompleted'] = '% Course completed';
$string['reports_badgename'] = 'Badge';
$string['reports_criteriatype'] = 'Criteria';
$string['reports_dateissued'] = 'In';
$string['reports_context'] = 'Context';
$string['reports_export'] = 'Export to Excel';
$string['reports_noneaccess'] = 'No access';
$string['reports_access_n'] = 'accessed {$a} times';
$string['reports_disabled'] = 'Disabled: -';
$string['reports_add_new'] = 'New report';

$string['reports_settings_title'] = 'Edit report';
$string['reports_settings_form_title'] = 'Edit report';
$string['reports_settings_form_enable'] = 'Enabled?';
$string['reports_settings_form_reportsql'] = 'Report SQL';
$string['reports_settings_form_prerequisit'] = 'Prerequisite before loading the report';
$string['reports_settings_form_none'] = 'None';
$string['reports_settings_form_prerequisit_listCourses'] = 'Course list';
$string['reports_settings_form_prerequisit_badge_status_text'] = 'Change the status of the Badge to Text';
$string['reports_settings_form_prerequisit_badge_criteria_type'] = 'Change the Badge criteria to Text';
$string['reports_settings_form_prerequisit_userfullname'] = 'Execute fullname ($ user) on each line of the report';
$string['reports_settings_form_prerequisit_courses_group_mode'] = 'Put the group mode in text';
$string['reports_settings_form_foreach'] = 'Changing SQL columns';
$string['reports_settings_form_colunas'] = 'Columns';
$string['reports_settings_form_colunas_title'] = 'Title';
$string['reports_settings_form_colunas_key'] = 'SQL column';
$string['reports_settings_form_colunas_type'] = 'Data type';
$string['reports_settings_form_colunas_type_int'] = 'Number';
$string['reports_settings_form_colunas_type_date'] = 'Data';
$string['reports_settings_form_colunas_type_currency'] = 'Currencies';
$string['reports_settings_form_colunas_type_text'] = 'Text';
$string['reports_settings_form_colunas_type_bytes'] = 'Bytes';
$string['reports_settings_form_colunas_extra'] = 'These below leave it blank if you don\'t need to!';
$string['reports_settings_savesuccess'] = 'Saved successfully!';
$string['reports_settings_form_save'] = 'Save report';

// Dashboard.
$string['dashboard_title_user'] = 'Users / Assets';
$string['dashboard_title_online'] = 'Online / Last hour';
$string['dashboard_title_course'] = 'Courses / Visible';
$string['dashboard_title_disk'] = 'Disk Usage';
$string['dashboard_grade_title'] = 'Latest notes';
$string['dashboard_grade_inmod'] = 'in module <strong>{$a->itemname}</strong>in course <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_incourse'] = 'in course <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_of'] = 'of';
$string['dashboard_grade_text'] = 'Received note {$a->grade} in {$a->evaluation}';
$string['dashboard_grade_in'] = 'In';
$string['dashboard_enrol_title'] = 'Last Enrolment';
$string['dashboard_enrol_inactive'] = 'the enrolment is inactive';
$string['dashboard_enrol_active'] = 'the enrolment is active';
$string['dashboard_enrol_text'] = 'You have enroled in the course <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->fullname}</a> and';
$string['dashboard_enrol_lastmodifield'] = 'Last change in';

// Notifications.
$string['notification_title'] = 'Notifications';
$string['notification_subtitle'] = '<p>Receive notifications whenever an action occurs in Moodle.</p>';
$string['notification_new'] = 'New notification';
$string['notification_testsmtp'] = 'Test if SMTP settings are correct.';
$string['notification_testsmtp_message'] = '<p> This is an Email submission test. </p>';
$string['notification_testsmtp_error'] = 'Moodle does not send email when recipient and sender are the same! <br> And you are the main administrator of this moodle. So to test you must log in with another administrator.';
$string['notification_testsmtp_subject'] = 'Testing Email Submission - ';
$string['notification_table_module'] = 'Module';
$string['notification_table_action'] = 'Action';
$string['notification_table_subject'] = 'Subject';
$string['notification_table_active'] = 'Active';
$string['notification_table_empty'] = 'No notification!';
$string['notification_add_module'] = 'Which module do you want to receive notification of?';
$string['notification_add_moduledesc'] = 'Modules / Unused Activities do not appear!';
$string['notification_add_selectmodule'] = 'Select Module!';
$string['notification_add_action'] = 'Which action do you want to receive notifications about?';
$string['notification_add_create'] = 'Create notification';
$string['notification_notound'] = 'Notification not found!';
$string['notification_editing'] = 'Editing Notification';
$string['notification_from'] = 'From';
$string['notification_fromdesc'] = 'Who will be the sender of the message?';
$string['notification_from_admin'] = 'Site Administrator';
$string['notification_to'] = 'To';
$string['notification_todesc'] = 'Who will receive these messages?';
$string['notification_todesc_admin'] = 'Site Administrator (Primary only)';
$string['notification_todesc_admins'] = 'Site Administrators (All Administrators)';
$string['notification_todesc_teachers'] = 'Course teachers (Only if it is within a course)';
$string['notification_todesc_student'] = 'The Student (Send to the student who did the action)';
$string['notification_status'] = 'Status';
$string['notification_statusdesc'] = 'If you want to stop notifications, mark it as "Idle" and save!';
$string['notification_status_active'] = 'Active';
$string['notification_status_inactive'] = 'Inactive';
$string['notification_subject'] = 'Subject';
$string['notification_subjectdesc'] = 'Message subject';
$string['notification_message_html'] = '<p>Hi {[to.fullname]},</p><p>&nbsp;</p><p>Att,<br>{[from.fullname]}.</p>';;
$string['notification_message'] = 'Message';
$string['notification_update'] = 'Update alert';
$string['notification_create'] = 'Create alert';
$string['notification_created'] = 'Notification created!';
$string['notification_notfound'] = 'Notification not found!';
$string['notification_delete_success'] = 'Notification deleted successfully!';
$string['notification_delete_yes'] = 'Do you really want to delete this Notification?';
$string['notification_setting_config'] = 'E-mail Settings';
$string['notification_setting_template'] = 'Template';
$string['notification_setting_templatelocation'] = 'Templates are in the folder';
$string['notification_setting_preview'] = 'Preview';
$string['notification_setting_edit'] = 'Edit template HTML';
$string['notification_manager'] = 'Manage Messages';
$string['notification_core_course_category'] = 'Course Category';
$string['notification_core_course'] = 'Courses';
$string['notification_core_user'] = 'Users';
$string['notification_core_user_enrolment'] = 'User Registration';
$string['notification_duplicate'] = 'This module and event combination already has a listener!';
$string['notification_local_kopere_dashboard'] = 'Kopere Dashboard';
$string['notification_local_kopere_hotmoodle'] = 'Kopere HotMoodle';
$string['notification_local_kopere_moocommerce'] = 'Kopere MooCommerce';
$string['notification_local_kopere_dashboard_payment'] = 'Kopere Payment';
$string['notification_error_smtp'] = '<p>In order for students to receive the messages, SMTP must be configured.</p>
          <p><a href="https://moodle.eduardokraus.com/configurar-o-smtp-no-moodle"
             target="_blank">Read here how to configure SMTP</a></p>
          <p><a href="{$a->wwwroot}/admin/settings.php?section={$a->mail}"
             target="_blank">Click here to configure email output</a></p>';
$string['notification_message_not'] = 'First, save the notification in order to create the message.';
$string['notification_message_edit'] = 'Edit the message content';

// Profile.
$string['profile_invalid'] = 'Invalid UserId!';
$string['profile_notfound'] = 'User not found!';
$string['profile_title'] = 'Users';
$string['profile_notenrol'] = 'User has no registration!';
$string['profile_edit'] = 'Edit this registration';
$string['profile_enrol_inactive'] = 'Registration is inactive';
$string['profile_enrol_active'] = 'Registration is active';
$string['profile_enrol_expires'] = 'Expires on';
$string['profile_enrol_notexpires'] = 'and never expires';
$string['profile_enrol_start'] = 'Start at';
$string['profile_enrol_profile'] = 'Profiles';
$string['profile_access_title'] = 'Access';
$string['profile_access_first'] = 'First access in:';
$string['profile_access_last'] = 'Last access on:';
$string['profile_access_lastlogin'] = 'Last login on:';
$string['profile_userdate_title'] = 'Data';
$string['profile_link_title'] = 'Useful Links';
$string['profile_link_profile'] = 'View profile';
$string['profile_link_edit'] = 'Edit Profile';
$string['profile_access'] = 'Access as';
$string['profile_courses_title'] = 'Registered Courses';

// Settings.
$string['setting_saved'] = 'Settings saved!';

// Userenrolment.
$string['userenrolment_notfound'] = 'User Enrolment not found!';
$string['userenrolment_edit'] = 'Edit registration date';
$string['userenrolment_detail'] = 'Registration details';
$string['userenrolment_status'] = 'Registration is';
$string['userenrolment_status_active'] = 'Active';
$string['userenrolment_status_inactive'] = 'Inactive';
$string['userenrolment_timestart'] = 'The subscription starts at';
$string['userenrolment_timeendstatus'] = 'Enable subscription term';
$string['userenrolment_timeend'] = 'The subscription ends in';
$string['userenrolment_created'] = 'Enrolment created in';
$string['userenrolment_updated'] = 'Enrolment last modified in';
$string['userenrolment_updatesuccess'] = 'Enrolment changed successfully!';

// User.
$string['user_title'] = 'Users';
$string['user_table_fullname'] = 'Name';
$string['user_table_username'] = 'Username';
$string['user_table_email'] = 'E-mail';
$string['user_table_phone'] = 'Fixed Phone';
$string['user_table_celphone'] = 'Mobile';
$string['user_table_city'] = 'City';

// Useronline.
$string['useronline_title'] = 'Online Users';
$string['useronline_subtitle'] = 'Open tabs with Moodle';
$string['useronline_table_fullname'] = 'Name';
$string['useronline_table_date'] = 'Data';
$string['useronline_table_page'] = 'Page';
$string['useronline_table_focus'] = 'Focus';
$string['useronline_table_screen'] = 'Monitor';
$string['useronline_table_navigator'] = 'Browser';
$string['useronline_table_os'] = 'Operating System';
$string['useronline_table_device'] = 'Device';
$string['useronline_settings_title'] = 'Server Settings Online User Synchronization';
$string['useronline_settings_status'] = 'Enable Online Users Synchronization Server';
$string['useronline_settings_ssl'] = 'Enable SSL?';
$string['useronline_settings_url'] = 'Server URL';
$string['useronline_settings_port'] = 'Server port';

// Acessos dos usuários.
$string['useraccess_title'] = 'User access';

// UserImport.
$string['userimport_title'] = 'Import Users';
$string['userimport_upload'] = 'Drag CSV files here or click to open the search box.';
$string['userimport_moveuploadedfile_error'] = 'ERROR while moving file!';
$string['userimport_title_proccess'] = 'Processing file "{$a}"';
$string['userimport_separator_error'] = 'You should export CSV with separator "; Or ","!';
$string['userimport_first10'] = 'First 10 records from your CSV';
$string['userimport_linkall'] = 'Click here to see all CSV records';
$string['userimport_colname'] = 'Column {$a}';
$string['userimport_colselect'] = '..::Select column::..';
$string['userimport_empty'] = 'If you do not select it, it will use the default "{$a}"';
$string['userimport_userdata'] = 'User Data';
$string['userimport_userfields'] = 'Extra profile fields';
$string['userimport_firstname'] = 'First name or full name';
$string['userimport_firstname_desc'] = 'If you have the full name in CSV, just fill in this field Kopere will be responsible for generating both fields. If your CSV has a first name and Last Name, select this and Last Name.';
$string['userimport_courseenrol'] = 'Enroll in a course';
$string['userimport_courseenrol_desc'] = 'If you want the student to be enrolled in a course, select the course identifier column.';
$string['userimport_date_desc'] = 'The system automatically detects the main date format.';
$string['userimport_group_desc'] = 'If you want the student to be bound to a group in the course, the column must be identical to the group name or internal ID.';
$string['userimport_next'] = 'Process';
$string['userimport_import_user_created_name'] = 'User imported and registered in Moodle';
$string['userimport_import_course_enrol_name'] = 'Imported user was enrolled in the Course';
$string['userimport_import_user_created_and_enrol_name'] = 'Imported user, registered in Moodle and Course';
$string['userimport_messages'] = 'Messages that students will receive during import';
$string['userimport_receivemessage'] = 'User will receive the message with the title {$a}';
$string['userimport_messageinactive'] = 'Message titled {$a} is inactive and will not be sent';
$string['userimport_notreceivemessage'] = 'User will not receive any messages in this action!';
$string['userimport_referencedata'] = 'Referencing Moodle data with CSV';
$string['userimport_dataok'] = 'Data OK, Insert into Moodle';
$string['userimport_datanotok'] = 'Not OK, I forgot something';
$string['userimport_wait'] = 'Please wait for the data to be processed. After processing, an spreadsheet with inserted data will be available. ';
$string['userimport_noterror'] = 'No error found';
$string['userimport_inserted'] = 'User entered';
$string['userimport_cript'] = '--encrypted--';
$string['userimport_exist'] = 'User already exists. Ignored';
$string['userimport_passcreate'] = '--It will be created--';
$string['userimport_filenotfound'] = 'File "{$a}" was not found. Upload CSV file again!';

$string['userimport_event_import_course_enrol_subject'] = 'Welcome Welcome - {[course.fullname]}';
$string['userimport_event_import_course_enrol_message'] = '<p>Hello {[to.fullname]},</p>
<p>You have been successfully enrolled in {[course.fullname]}. You can now login to the student area to begin studying when and where you want.</p>
<p>It is with great satisfaction that {[moodle.fullname]} welcomes you.</p>
<p>Access {{course.link}}, and good studies.</p>
<p>If you have any doubts, help is available.</p>
<p>Sincerely,<br>
   Support Team</p>';

$string['userimport_event_import_user_created_subject'] = 'Welcome! - {[moodle.fullname]}';
$string['userimport_event_import_user_created_message'] = '<p>Hello {[to.fullname]},</p>
<p>An account was created for you on the site {[moodle.fullname]}.</p>
<p>Now, I invite you to login to the student area with the following data:</p>
<p><strong>Site:</strong> {[moodle.link]}<br>
   <strong>Login:</strong> {[to.username]}<br>
   <strong>Password:</strong> {[to.password]}</p>
<p>If you have any doubts, help is available.</p>
<p>Sincerely,<br>
   Support Team</p>';

$string['userimport_event_import_user_created_and_enrol_subject'] = 'Welcome Welcome - {[course.fullname]}';
$string['userimport_event_import_user_created_and_enrol_message'] = '<p>Hello {[to.fullname]},</p>
<p>You have been successfully enrolled in {[course.fullname]}. You can now login to the student area to begin studying when and where you want.</p>
<p>Now, I invite you to login to the student area with the following data:</p>
<p><strong>Site:</strong> {[moodle.link]}<br>
   <strong>Login:</strong> {[to.username]}<br>
   <strong>Password:</strong> {[to.password]}</p>
<p>If you have any doubts, help is available.</p>
<p>Sincerely,<br>
   Support Team</p>';

// WebPages.
$string['webpages_title'] = 'Static Pages';
$string['webpages_subtitle'] = 'Navigation Menus';
$string['webpages_subtitle_help'] = 'These menus appear under Navigation under "My Courses"';
$string['webpages_table_link'] = 'Link';
$string['webpages_table_menutitle'] = 'Menu';
$string['webpages_table_title'] = 'Title';
$string['webpages_table_visible'] = 'Visible';
$string['webpages_table_image'] = 'Choose an image or drag it here.';
$string['webpages_error_page'] = 'Page not found!';
$string['webpages_error_menu'] = 'Menu not found!';
$string['webpages_table_order'] = 'Order';
$string['webpages_table_theme'] = 'Layout';
$string['webpages_table_text'] = 'Page content';
$string['webpages_table_text_not'] = 'First, save the content so you can create the page later.';
$string['webpages_table_text_edit'] = 'Edit the page content';
$string['webpages_page_title'] = 'Title';
$string['webpages_page_menu'] = 'Menu';
$string['webpages_page_create'] = 'Create new page';
$string['webpages_page_crash'] = 'If you change the Moodle URL and the images give CRASH, click here';
$string['webpages_page_notfound'] = 'Page not found!';
$string['webpages_page_nomenudelete'] = '<p>This menu has internal pages and can not be deleted!</p>';
$string['webpages_page_confirmdeletemenu'] = '<p>Do you really want to delete the <strong>{$a}</strong> menu?</p>';
$string['webpages_page_view'] = 'View page';
$string['webpages_page_edit'] = 'Edit page';
$string['webpages_page_delete'] = 'Delete page';
$string['webpages_page_course'] = 'Linked Course';
$string['webpages_page_new'] = 'New page';
$string['webpages_page_edit'] = 'Editing page';
$string['webpages_page_save'] = 'Save page';
$string['webpages_page_error'] = 'All data must be filled in!';
$string['webpages_page_created'] = 'Created page!';
$string['webpages_page_updated'] = 'Updated page!';
$string['webpages_page_deleted'] = 'Page successfully deleted!';
$string['webpages_page_delete'] = 'Excluding Page';
$string['webpages_page_delete_confirm'] = 'Do you really want to delete the page <strong>{$a->title}</strong>?';
$string['webpages_menu_create'] = 'Create new Menu';
$string['webpages_menu_help'] = 'Help with Menus';
$string['webpages_menu_new'] = 'New Menu';
$string['webpages_menu_edit'] = 'Editing Menu';
$string['webpages_menu_title'] = 'Menu Title';
$string['webpages_menu_link'] = 'Menu Link';
$string['webpages_menu_menuid']= 'Sub-menu';
$string['webpages_menu_save'] = 'Save';
$string['webpages_menu_error'] = 'All data must be filled in!';
$string['webpages_menu_link_duplicate'] = '"Link" is duplicated!';
$string['webpages_menu_updated'] = 'Menu updated!';
$string['webpages_menu_created'] = 'Menu created!';
$string['webpages_menu_deleted'] = 'Menu deleted successfully!';
$string['webpages_menu_subtitle'] = 'Static Menu';
$string['webpages_menu_delete'] = 'Excluding Menu';
$string['webpages_menu_nodelete'] = 'You can not delete a menu that has pages registered!';
$string['webpages_page_settigs'] = 'Static Pages Settings';
$string['webpages_page_theme'] = 'Page Layout "All Pages"';
$string['webpages_page_analytics'] = 'Google Analytics Tracking ID';
$string['webpages_page_analyticsdesc'] = 'Sequence of 13 characters, starting in UA';
$string['webpages_theme_base'] = 'The layout without the blocks';
$string['webpages_theme_standard'] = 'Default layout with blocks';
$string['webpages_theme_frontpage'] = 'Layout of the site\'s home page.';
$string['webpages_theme_popup'] = 'No navigation, no blocks, no header';
$string['webpages_theme_frametop'] = 'No blocks and minimum footer';
$string['webpages_theme_print'] = 'Must only display content and basic headers';
$string['webpages_theme_report'] = 'The page layout used for reporting';
$string['webpages_allpages'] = 'All pages';

$string['notification_local_kopere_dashboard'] = 'Kopere Dashboard';
$string['notification_local_kopere_hotmoodle'] = 'Kopere HotMoodle';
$string['notification_local_kopere_moocommerce'] = 'Kopere MooCommerce';
$string['notification_local_kopere_pay'] = 'Kopere Pagamento';

$string['privacy:metadata'] = 'The Kopere Dashboard plugin does not store any personal data.';

