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
 * local_kopere_dashboard.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['datatables_buttons_copyKeys'] = 'Press <i>ctrl</i> or <i>\\u2318</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.';
$string['datatables_buttons_copySuccess1'] = 'Copied one row to clipboard';
$string['datatables_buttons_copySuccess_'] = 'Copied %d rows to clipboard';
$string['datatables_buttons_copyTitle'] = 'Copy to clipboard';
$string['datatables_buttons_copy_text'] = 'Copy data';
$string['datatables_buttons_csv_text'] = 'Download CSV';
$string['datatables_buttons_pageLength_'] = 'Show %d items';
$string['datatables_buttons_pageLength_1'] = 'Show all';
$string['datatables_buttons_print_text'] = 'Print';
$string['datatables_buttons_select_rows1'] = '1 row selected';
$string['datatables_buttons_select_rows_'] = '%d rows selected';
$string['datatables_oAria_sSortAscending'] = ': Sorting Columns in ascending order';
$string['datatables_oAria_sSortDescending'] = ': Sorting columns in descending order';
$string['datatables_oPaginate_sFirst'] = 'First';
$string['datatables_oPaginate_sLast'] = 'Last';
$string['datatables_oPaginate_sNext'] = 'Next';
$string['datatables_oPaginate_sPrevious'] = 'Previous';
$string['datatables_sEmptyTable'] = 'No records found';
$string['datatables_sErrorMessage'] = '<strong>Error loading data</strong><div>Trying again in {$a} seconds</div>';
$string['datatables_sInfo'] = '_START_ to _END_ of _TOTAL_';
$string['datatables_sInfoEmpty'] = '0 records';
$string['datatables_sInfoFiltered'] = '(Filtered from _MAX_ records)';
$string['datatables_sInfoPostFix'] = '';
$string['datatables_sInfoThousands'] = '.';
$string['datatables_sLengthMenu'] = '_MENU_ per page';
$string['datatables_sLoadingRecords'] = 'Loading ...';
$string['datatables_sProcessing'] = 'Processing ...';
$string['datatables_sSearch'] = 'Search';
$string['datatables_sZeroRecords'] = 'No records found';

$string['about_description_text'] = 'Kopere Dashboard centralizes academic, financial, operational, and administrative routines in a single interface within Moodle.';
$string['about_description_title'] = 'About the plugin';
$string['about_github_label'] = 'GitHub';
$string['about_support_text'] = 'For documentation, source code, or project evolution, use the links below.';
$string['about_support_title'] = 'Useful links';
$string['about_website_label'] = 'Developer website';
$string['active'] = 'Active';
$string['audit'] = 'Audit log';
$string['audit_action'] = 'Action';
$string['audit_component'] = 'Component';
$string['audit_description'] = 'Description';
$string['audit_filter'] = 'Filter';
$string['audit_latest'] = 'Latest Activities (Audit)';
$string['audit_search'] = 'Search';
$string['audit_time'] = 'Time';
$string['audit_user'] = 'User';
$string['cat_academic'] = 'Academic';
$string['cat_financial'] = 'Financial';
$string['cat_pedagogic'] = 'Pedagogical';
$string['cat_settings'] = 'Settings';
$string['cat_tools'] = 'Tools';
$string['chart_contracts_title'] = 'Contract Status';
$string['chart_enrollments_title'] = 'Enrollment Overview';
$string['contracts_expired'] = 'Expired';
$string['contracts_pending'] = 'Pending';
$string['contracts_signed'] = 'Signed';
$string['dashboard'] = 'Dashboard';
$string['globalsettings'] = 'Global settings';
$string['inactive'] = 'Inactive';
$string['invisible'] = 'Hidden';
$string['js_completed'] = 'Completed';
$string['js_processing'] = 'Processing...';
$string['kpi_active_students'] = 'Active Students';
$string['kpi_active_students_sub'] = 'Trend: Active Students';
$string['kpi_new_enrollments_month'] = 'New Enrollments (Month)';
$string['kpi_new_enrollments_month_sub'] = 'New entries in the current month';
$string['kpi_pending_contracts'] = 'Pending Contracts';
$string['kpi_pending_contracts_sub'] = 'Awaiting acceptance';
$string['kpi_profile_updates_today'] = 'Profile Updates';
$string['kpi_profile_updates_today_sub'] = 'Today';
$string['m1'] = '1 month';
$string['m2'] = '2 months';
$string['m3'] = '3 months';
$string['m4'] = '4 months';
$string['m5'] = '5 months';
$string['m6'] = '6 months';
$string['managekopere_dashboardplugins'] = 'Manage Kopere Dashboard plugins';
$string['managekopere_dashboardplugins_desc'] = 'Enable, disable, and organize the plugin order. The order defined here will also be used to build the Kopere Dashboard menus.';
$string['menu_about'] = 'About';
$string['menu_about_desc'] = 'Version information, useful links, and Kopere Dashboard details.';
$string['menu_close'] = 'Close menu';
$string['menu_navigation'] = 'Navigation menu';
$string['menu_open'] = 'Open';
$string['messageprovider:local_kopere_dashboard_messages'] = 'Send Notifications';
$string['movedown'] = 'Move down';
$string['moveup'] = 'Move up';
$string['order'] = 'Order';
$string['permissions'] = 'Permissions';
$string['permissions_add'] = '← Add';
$string['permissions_current_users'] = 'Users with access';
$string['permissions_desc'] = 'This screen brings together the permissions for Kopere Dashboard and its plugins. Each block represents an available permission in the system and shows what users are allowed to do. When managing permissions, you can define who will have access to each action, organizing profiles according to the responsibility of each team, such as office staff, finance, coordination, or support. Use this area to carefully review access, avoiding granting permissions beyond what is necessary.';
$string['permissions_manage'] = 'Manage';
$string['permissions_potential_users'] = 'Users without access';
$string['permissions_remove'] = 'Remove →';
$string['permissions_users_desc'] = 'Use the selectors to add or remove users for this permission (role assignment in the system context).';
$string['permissions_users_title'] = 'Assign users';
$string['plugin_external'] = 'External plugin';
$string['pluginmenu_hidden'] = 'Hidden in menu';
$string['pluginname'] = 'Kopere Dashboard';
$string['pluginstatus_activate'] = 'Activate';
$string['pluginstatus_active'] = 'Active';
$string['pluginstatus_deactivate'] = 'Deactivate';
$string['pluginstatus_inactive'] = 'Inactive';
$string['reports'] = 'Reports';
$string['status'] = 'Status';
$string['student_courses_empty'] = 'You do not have any active courses to follow here yet.';
$string['student_courses_title'] = 'My courses';
$string['student_kpi_completed_courses'] = 'Completed courses';
$string['student_kpi_completed_courses_sub'] = 'Already completed by you';
$string['student_kpi_enrolled_courses'] = 'Enrolled courses';
$string['student_kpi_enrolled_courses_sub'] = 'Total active courses at the moment';
$string['student_kpi_in_progress_courses'] = 'In progress';
$string['student_kpi_in_progress_courses_sub'] = 'Courses with recent activity';
$string['student_kpi_tracked_courses'] = 'With progress';
$string['student_kpi_tracked_courses_sub'] = 'Courses tracked by completion';
$string['student_lastaccess'] = 'Last access';
$string['student_never_accessed'] = 'Not accessed yet';
$string['student_progress'] = 'Progress';
$string['student_progress_not_available'] = 'Not available';
$string['student_quicklink_calendar'] = 'Calendar';
$string['student_quicklink_calendar_desc'] = 'View deadlines, events, and important activities.';
$string['student_quicklink_mycourses'] = 'My courses';
$string['student_quicklink_mycourses_desc'] = 'Open the full list of your courses.';
$string['student_quicklink_profile'] = 'My profile';
$string['student_quicklink_profile_desc'] = 'Review your personal information and preferences.';
$string['student_quicklinks_title'] = 'Quick shortcuts';
$string['student_status_completed'] = 'Completed';
$string['student_status_inprogress'] = 'In progress';
$string['student_status_notstarted'] = 'Not started';
$string['student_welcome_desc'] = 'Follow your courses, view your progress, and quickly resume what you need to study.';
$string['student_welcome_title'] = 'Your student dashboard';
$string['subplugintype_koperedashboard'] = 'Plugin';
$string['subplugintype_koperedashboard_plural'] = 'Plugins';
$string['visible'] = 'Visible';
$string['welcome_desc'] = 'The complete hub for academic and pedagogical management in your Moodle dashboard.';
$string['welcome_title'] = 'Welcome to Kopere Dashboard!';

