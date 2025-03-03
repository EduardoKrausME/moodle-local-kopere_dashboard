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
 * Lib file
 *
 * introduced 23/05/17 17:59
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\util\url_util;

/**
 * Function local_kopere_dashboard_lang
 */
function local_kopere_dashboard_lang() {

    global $PAGE;

    $PAGE->requires->strings_for_js(["decsep", "thousandssep"], "langconfig");
    $PAGE->requires->strings_for_js(["yes", "no"], "moodle");
    $PAGE->requires->strings_for_js([
        "visible",
        "invisible",
        "active",
        "inactive",
        "date_renderer_format",
        "datetime_renderer_format",
        "datatables_sEmptyTable",
        "datatables_sInfo",
        "datatables_sInfoEmpty",
        "datatables_sInfoFiltered",
        "datatables_sInfoPostFix",
        "datatables_sInfoThousands",
        "datatables_sLengthMenu",
        "datatables_sLoadingRecords",
        "datatables_sProcessing",
        "datatables_sErrorMessage",
        "datatables_sZeroRecords",
        "datatables_sSearch",
        "datatables_buttons_print_text",
        "datatables_buttons_copy_text",
        "datatables_buttons_csv_text",
        "datatables_buttons_copySuccess1",
        "datatables_buttons_copySuccess_",
        "datatables_buttons_copyTitle",
        "datatables_buttons_copyKeys",
        "datatables_buttons_select_rows_",
        "datatables_buttons_select_rows1",
        "datatables_buttons_pageLength_",
        "datatables_buttons_pageLength_1",
        "datatables_oPaginate_sNext",
        "datatables_oPaginate_sPrevious",
        "datatables_oPaginate_sFirst",
        "datatables_oPaginate_sLast",
        "datatables_oAria_sSortAscending",
        "datatables_oAria_sSortDescending",
        "datatables_oPaginate_sFirst",
        "datatables_oPaginate_sLast",
    ], "local_kopere_dashboard");
}

/**
 * Function local_kopere_dashboard_extends_navigation
 *
 * @param global_navigation $nav
 *
 * @throws Exception
 */
function local_kopere_dashboard_extends_navigation(global_navigation $nav) {
    local_kopere_dashboard_extend_navigation($nav);
}

/**
 * Function local_kopere_dashboard_extend_navigation
 *
 * @param global_navigation $nav
 *
 * @throws Exception
 */
function local_kopere_dashboard_extend_navigation(global_navigation $nav) {
    global $CFG, $PAGE;

    $CFG->custommenuitems = trim($CFG->custommenuitems);
    $CFG->custommenuitems = preg_replace('/.*kopere_dashboard.*/', "", $CFG->custommenuitems);
    $CFG->custommenuitems = trim($CFG->custommenuitems);

    if (isloggedin()) {
        if ($CFG->branch > 400 && @get_config("local_kopere_dashboard", "menu")) {
            $context = context_system::instance();
            $hascapability = has_capability("local/kopere_dashboard:view", $context) ||
                has_capability("local/kopere_dashboard:manage", $context);

            if ($hascapability) {
                $name = get_string("modulename", "local_kopere_dashboard");
                $link = url_util::makeurl("dashboard", "start");
                $PAGE->requires->js_call_amd("local_kopere_dashboard/start_load",
                    "moremenu", [$name, $link]);
            }
            if (@get_config("local_kopere_dashboard", "menuwebpages")) {
                local_kopere_dashboard_extend_navigation_400();
            }
        } else {
            $context = context_system::instance();
            if (has_capability("local/kopere_dashboard:view", $context) ||
                has_capability("local/kopere_dashboard:manage", $context)) {

                $node = $nav->add(
                    get_string("pluginname", "local_kopere_dashboard"),
                    new moodle_url(url_util::makeurl("dashboard", "start")),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    "kopere_dashboard",
                    new pix_icon("icon", get_string("pluginname", "local_kopere_dashboard"), "local_kopere_dashboard")
                );

                $node->showinflatnavigation = true;
            }
        }
    } else {
        if ($CFG->branch > 400 && @get_config("local_kopere_dashboard", "menu")) {
            local_kopere_dashboard_extend_navigation_400();
        }
    }
}

/**
 * Function local_kopere_dashboard_extend_navigation_400
 */
function local_kopere_dashboard_extend_navigation_400() {
    global $CFG;

    $cache = \cache::make("local_kopere_dashboard", "report_getdata_cache");
    if (false && $cache->has("local_kopere_dashboard_menu")) {
        $CFG->extramenu = $cache->get("local_kopere_dashboard_menu");
    } else {

        try {
            $CFG->extramenu = "";
            local_kopere_dashboard_extend_navigation__get_menus(0, "");
            $cache->set("local_kopere_dashboard_menu", $CFG->extramenu);
        } catch (dml_exception $e) { // phpcs:disable
        }
    }

    $CFG->custommenuitems = "{$CFG->custommenuitems}\n{$CFG->extramenu}";

    unset($CFG->extramenu);
}

/**
 * Function local_kopere_dashboard_extend_navigation__get_menus
 *
 * @param $menuid
 * @param $prefix
 *
 * @throws dml_exception
 */
function local_kopere_dashboard_extend_navigation__get_menus($menuid, $prefix) {
    global $DB, $CFG;

    $menus = $DB->get_records_sql("
                SELECT *
                  FROM {local_kopere_dashboard_menu}
                 WHERE menuid   = :menuid
                   AND inheader = 1",
        ["menuid" => $menuid]);

    foreach ($menus as $menu) {
        $where = ["visible" => 1, "menuid" => $menu->id];
        $webpages = $DB->get_records("local_kopere_dashboard_pages", $where, "pageorder ASC");
        $CFG->extramenu .= "{$prefix} {$menu->title}|{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}\n";
        if ($webpages) {
            /** @var \local_kopere_dashboard\vo\local_kopere_dashboard_pages $webpage */
            foreach ($webpages as $webpage) {
                $link = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpage->link}";
                $CFG->extramenu .= "{$prefix}- {$webpage->title}|{$link}\n";
            }
        }
        local_kopere_dashboard_extend_navigation__get_menus($menu->id, "{$prefix}-");
    }
}

/**
 * Function local_kopere_dashboard_pluginfile
 *
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 *
 * @return bool
 * @throws coding_exception
 */
function local_kopere_dashboard_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    if ($filearea == "overviewfiles") {
        $filename = array_pop($args);
        $filepath = $args ? "/" . implode("/", $args) . "/" : "/";
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, "course", $filearea, 0, $filepath, $filename);
        if (!$file || $file->is_directory()) {
            die("ops...");
        }

        send_stored_file($file, 0, 0, $forcedownload, $options);
    }

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, "local_kopere_dashboard", "editor_webpages", $args[0], "/", $args[1])) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
    return true;
}
