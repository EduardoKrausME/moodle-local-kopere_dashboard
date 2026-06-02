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
 * Print the table of all installed Kopere Dashboard plugins
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\api\subplugin_manager;

require_once(__DIR__ . "/../../config.php");
require_once("{$CFG->libdir}/adminlib.php");
require_once("{$CFG->libdir}/tablelib.php");

$action = optional_param("action", "", PARAM_ALPHA);
$plugin = optional_param("plugin", "", PARAM_PLUGIN);

$baseurl = new moodle_url("/local/kopere_dashboard/admin_plugins.php");

$context = context_system::instance();
$PAGE->set_url($baseurl);
$PAGE->set_context($context);
$PAGE->set_title(get_string("managekopere_dashboardplugins", "local_kopere_dashboard"));

require_admin();
admin_externalpage_setup("managelocalplugins");

if ($action != "" && $plugin != "") {
    require_sesskey();

    if ($action == "toggle") {
        subplugin_manager::set_plugin_enabled($plugin, !subplugin_manager::is_plugin_enabled($plugin));
    } else if ($action == "moveup") {
        subplugin_manager::move_plugin($plugin, "up");
    } else if ($action == "movedown") {
        subplugin_manager::move_plugin($plugin, "down");
    }

    redirect($baseurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string("managekopere_dashboardplugins", "local_kopere_dashboard"));

echo html_writer::div(
    html_writer::link(
        new moodle_url("/admin/settings.php", ["section" => "local_kopere_dashboard"]),
        get_string("settings"),
        ["class" => "btn btn-secondary"]
    ),
    "mb-3"
);

echo html_writer::div(
    get_string("managekopere_dashboardplugins_desc", "local_kopere_dashboard"),
    "alert alert-info"
);

$plugins = subplugin_manager::get_plugins_state();
$categorysort = subplugin_manager::categorysorts();

foreach ($plugins as $index => $row) {
    $class = "";
    if (class_exists("\\koperedashboard_{$row["name"]}\\menu")) {
        $class = "\\koperedashboard_{$row["name"]}\\menu";
    } else if (class_exists("\\local_{$row["name"]}\\menu")) {
        $class = "\\local_{$row["name"]}\\menu";
    }

    $category = "";
    if ($class) {
        $definition = (new $class())->get_definition($context);
        $category = $definition["category"] ?? "";
    }

    $plugins[$index]["categorykey"] = $category;

    if ($row["sortorder"] < $categorysort[$category]) {
        $sortorder = $categorysort[$category] + $row["sortorder"];
        set_config("sortorder", $sortorder, $row["component"]);
        $reload = true;
    } else if ($row["sortorder"] > $categorysort[$category] + 99) {
        $sortorder = $categorysort[$category] + 10;
        set_config("sortorder", $sortorder, $row["component"]);
        $reload = true;
    }
}
if (isset($reload) && $reload) {
    redirect($baseurl);
}

$lastindex = count($plugins) - 1;
foreach ($categorysort as $category => $num) {

    $categorymenu = get_string("cat_{$category}", "local_kopere_dashboard");

    echo "<h2>{$categorymenu}</h2>";

    $table = new flexible_table("localplugins_administration_table_{$categorymenu}");
    $table->define_columns(["name", "status", "sortorder", "version", "uninstall"]);
    $table->define_headers([
        get_string("plugin"),
        get_string("status", "local_kopere_dashboard"),
        get_string("order", "local_kopere_dashboard"),
        get_string("version"),
        get_string("uninstallplugin", "core_admin"),
    ]);
    $table->define_baseurl($baseurl);
    $table->set_attribute("id", "localplugins");
    $table->set_attribute("class", "admintable generaltable");
    $table->setup();
    foreach ($plugins as $index => $row) {
        if ($row["categorykey"] != $category) {
            continue;
        }

        $toggleurl = new moodle_url($baseurl, [
            "action" => "toggle",
            "plugin" => $row["name"],
            "sesskey" => sesskey(),
        ]);

        $statuslabel = $row["enabled"]
            ? get_string("pluginstatus_active", "local_kopere_dashboard")
            : get_string("pluginstatus_inactive", "local_kopere_dashboard");
        $statusclass = $row["enabled"] ? "success" : "secondary";
        $togglelabel = $row["enabled"]
            ? get_string("pluginstatus_deactivate", "local_kopere_dashboard")
            : get_string("pluginstatus_activate", "local_kopere_dashboard");

        $statushtml = html_writer::tag(
            "span",
            $statuslabel,
            ["class" => "badge text-bg-{$statusclass}", "style" => "margin-right:8px;"]
        );
        $statushtml .= html_writer::link($toggleurl, $togglelabel, ["class" => "btn btn-sm btn-outline-primary"]);

        $orderactions = [];
        if ($index > 0 && $plugins[$index - 1]["categorykey"] == $category) {
            $moveupurl = new moodle_url($baseurl, [
                "action" => "moveup",
                "plugin" => $row["name"],
                "sesskey" => sesskey(),
            ]);
            $orderactions[] = html_writer::link(
                $moveupurl,
                "↑",
                ["class" => "btn btn-sm btn-outline-secondary", "title" => get_string("moveup", "local_kopere_dashboard")]
            );
        }

        if ($index < $lastindex && $plugins[$index + 1]["categorykey"] == $category) {
            $movedownurl = new moodle_url($baseurl, [
                "action" => "movedown",
                "plugin" => $row["name"],
                "sesskey" => sesskey(),
            ]);
            $orderactions[] = html_writer::link(
                $movedownurl,
                "↓",
                ["class" => "btn btn-sm btn-outline-secondary", "title" => get_string("movedown", "local_kopere_dashboard")]
            );
        }

        $uninstall = "";
        if ($uninstallurl = core_plugin_manager::instance()->get_uninstall_url("koperedashboard_{$row["name"]}", "manage")) {
            $uninstall = html_writer::link($uninstallurl, get_string("uninstallplugin", "core_admin"));
        } else {
            $uninstall = get_string("plugin_external", "local_kopere_dashboard");
        }

        $version = "";
        $versionfile = get_config("koperedashboard_{$row["name"]}");
        if (!empty($versionfile->version)) {
            $version = $versionfile->version;
        } else {
            $versionfile = get_config("local_{$row["name"]}");
            if (!empty($versionfile->version)) {
                $version = $versionfile->version;
            }
        }

        $table->add_data([
            format_string($row["displayname"]),
            $statushtml,
            implode(" ", $orderactions),
            $version,
            $uninstall,
        ]);
    }

    $table->print_html();
}

echo $OUTPUT->footer();
