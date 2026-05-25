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
 * koperedashboard_pages.php
 *
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['ajax_error'] = 'Could not generate the link automatically.';
$string['cap_manage'] = 'Manage pages';
$string['cap_manage_desc'] = 'Allows creating, editing, and deleting Kopere Dashboard public menus and pages.';
$string['delete_menu_confirm'] = 'Are you sure you want to delete the menu <strong>{$a}</strong>?';
$string['delete_page_confirm'] = 'Are you sure you want to delete the page <strong>{$a}</strong>?';
$string['empty_desc'] = 'First create a menu, then add pages inside it.';
$string['empty_public'] = 'No public pages available.';
$string['empty_public_menu'] = 'This menu does not have any visible pages yet.';
$string['empty_title'] = 'No pages configured';
$string['error_duplicate_link'] = 'Another record is already using this link.';
$string['error_menu_not_empty'] = 'This menu has submenus or pages. Remove the internal items before deleting it.';
$string['error_menu_not_found'] = 'Menu not found.';
$string['error_menu_title'] = 'Enter the menu title.';
$string['error_page_not_found'] = 'Page not found.';
$string['error_page_title'] = 'Enter the page title.';
$string['field_analytics'] = 'Analytics';
$string['field_analytics_help'] = 'Enter a Google Analytics/Tag Manager ID or paste the full script.';
$string['field_course'] = 'Linked course';
$string['field_default_theme'] = 'Default layout';
$string['field_icon'] = 'Icon';
$string['field_icon_help'] = 'Material Symbols icon name. Example: article, school, folder.';
$string['field_inheader'] = 'Show this menu in the main public listing';
$string['field_link'] = 'Link';
$string['field_link_help'] = 'Use only the URL identifier. Example: my-page.';
$string['field_menu'] = 'Menu';
$string['field_pageorder'] = 'Order';
$string['field_parent_menu'] = 'Parent menu';
$string['field_text'] = 'HTML content';
$string['field_text_help'] = 'Accepts HTML and legacy shortcodes in the format [[kopere_plugin::class->method(parameter)]].';
$string['field_theme'] = 'Page layout';
$string['field_title'] = 'Title';
$string['field_visible'] = 'Show in public listing';
$string['menu_delete'] = 'Delete menu';
$string['menu_desc'] = 'Manage public menus and pages using the Kopere Dashboard tables.';
$string['menu_edit'] = 'Edit menu';
$string['menu_new'] = 'New menu';
$string['menu_new_short'] = '+ menu';
$string['menu_title'] = 'Pages';
$string['msg_created'] = 'Record created successfully.';
$string['msg_deleted'] = 'Record deleted successfully.';
$string['msg_settings'] = 'Settings saved successfully.';
$string['msg_updated'] = 'Record updated successfully.';
$string['none_course'] = 'No course';
$string['page_delete'] = 'Delete page';
$string['page_edit'] = 'Edit page';
$string['page_manage_title'] = 'Static pages';
$string['page_new'] = 'New page';
$string['page_new_short'] = '+ page';
$string['page_view_public'] = 'View public page';
$string['pluginname'] = 'Pages';
$string['public_pages'] = 'Public pages';
$string['public_pages_desc'] = 'Opens the public page listing.';
$string['root_menu'] = 'Root';
$string['settings_title'] = 'Page settings';
$string['table_actions'] = 'Actions';
$string['table_link'] = 'Link';
$string['table_title'] = 'Title';
$string['table_type'] = 'Type';
$string['table_visible'] = 'Visible';
$string['type_menu'] = 'Menu';
$string['type_page'] = 'Page';
$string['webpages_access'] = 'Access';
$string['webpages_allpages'] = 'All pages';
$string['webpages_edit'] = 'Edit the page content';
$string['webpages_firstsave'] = 'First, save the content so you can create the page afterward.';
$string['webpages_free'] = 'Free';
