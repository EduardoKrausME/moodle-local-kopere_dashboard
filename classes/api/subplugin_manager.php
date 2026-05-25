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
 * subplugin_manager.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\api;

use coding_exception;
use context;
use core_component;

/**
 * Class subplugin_manager
 */
class subplugin_manager {
    /** @var string */
    public const CAT_ACADEMIC = "academic";

    /** @var string */
    public const CAT_PEDAGOGIC = "pedagogic";

    /** @var string */
    public const CAT_FINANCIAL = "financial";

    /** @var string */
    public const CAT_SETTINGS = "settings";

    /** @var string */
    public const CAT_TOOLS = "tools";

    /** @var string */
    private const PLUGINTYPE_KOPERE_DASHBOARD = "koperedashboard";

    /** @var string */
    private const PLUGINTYPE_LOCAL = "local";

    /** @var string */
    private const LOCAL_KOPERE_PREFIX = "kopere_";

    /** @var string */
    private const CLASS_MENU = "menu";

    /** @var string */
    private const CLASS_CAPABILITIES_PROVIDER = "capabilities_provider";

    /** @var string */
    private const CLASS_HOME_KPI = "home_kpi";

    /** @var int */
    private const DEFAULT_SORTORDER = 999999;

    /**
     * Return installed Kopere Dashboard subplugins ordered by custom sort order.
     *
     * This method keeps the original return format used by Moodle's plugininfo
     * class: keys are raw Kopere Dashboard subplugin names and values are paths.
     * External local_kopere_* plugins are exposed by get_plugins_state(), menus
     * and capability providers, but are not returned here as Kopere Dashboard
     * subplugins.
     *
     * @param bool $onlyenabled
     * @return array
     */
    public static function get_installed_subplugins(bool $onlyenabled = false): array {
        $plugins = core_component::get_plugin_list(self::PLUGINTYPE_KOPERE_DASHBOARD);
        $ordered = [];

        foreach (self::get_plugins_state() as $pluginstate) {
            if ($pluginstate["plugintype"] != self::PLUGINTYPE_KOPERE_DASHBOARD) {
                continue;
            }

            $name = $pluginstate["name"];
            if (!array_key_exists($name, $plugins)) {
                continue;
            }

            if ($onlyenabled && !$pluginstate["enabled"]) {
                continue;
            }

            $ordered[$name] = $plugins[$name];
        }

        return $ordered;
    }

    /**
     * Return installed Kopere Dashboard plugins plus compatible local_kopere_* plugins.
     *
     * local_kopere_* plugins are included only when they expose both classes:
     * - \local_kopere_xxx\menu
     * - \local_kopere_xxx\capabilities_provider
     *
     * @return array
     */
    public static function get_plugins_state(): array {
        $rows = array_merge(
            self::get_kopere_dashboard_plugins_state(),
            self::get_local_kopere_plugins_state()
        );

        usort($rows, static function(array $a, array $b): int {
            if ($a["sortorder"] == $b["sortorder"]) {
                return strcmp(strtolower($a["displayname"]), strtolower($b["displayname"]));
            }

            return $a["sortorder"] <=> $b["sortorder"];
        });

        return $rows;
    }

    /**
     * Return installed Kopere Dashboard subplugin state rows.
     *
     * @return array
     */
    private static function get_kopere_dashboard_plugins_state(): array {
        $rows = [];

        foreach (core_component::get_plugin_list(self::PLUGINTYPE_KOPERE_DASHBOARD) as $name => $path) {
            $component = "koperedashboard_{$name}";
            $rows[] = self::build_plugin_state($name, $component, self::PLUGINTYPE_KOPERE_DASHBOARD, $path);
        }

        return $rows;
    }

    /**
     * Return compatible local_kopere_* plugin state rows.
     *
     * @return array
     */
    private static function get_local_kopere_plugins_state(): array {
        $rows = [];

        foreach (core_component::get_plugin_list(self::PLUGINTYPE_LOCAL) as $name => $path) {
            if (strpos($name, self::LOCAL_KOPERE_PREFIX) !== 0) {
                continue;
            }

            $component = "local_{$name}";
            if (!self::has_kopere_dashboard_integration($component)) {
                continue;
            }

            $rows[] = self::build_plugin_state($name, $component, self::PLUGINTYPE_LOCAL, $path);
        }

        return $rows;
    }

    /**
     * Build a normalized plugin state row.
     *
     * @param string $name
     * @param string $component
     * @param string $plugintype
     * @param string $path
     * @return array
     */
    private static function build_plugin_state(string $name, string $component, string $plugintype, string $path): array {
        return [
            "name" => $name,
            "component" => $component,
            "plugintype" => $plugintype,
            "displayname" => self::get_plugin_display_name($component),
            "path" => $path,
            "enabled" => self::is_plugin_enabled($component),
            "sortorder" => self::get_plugin_sortorder($component),
        ];
    }

    /**
     * Check if a local_kopere_* plugin exposes the Kopere Dashboard integration classes.
     *
     * @param string $component
     * @return bool
     */
    private static function has_kopere_dashboard_integration(string $component): bool {
        return class_exists("\\{$component}\\" . self::CLASS_MENU)
            && class_exists("\\{$component}\\" . self::CLASS_CAPABILITIES_PROVIDER);
    }

    /**
     * Return enabled compatible plugins.
     *
     * @return array
     */
    private static function get_integrated_plugins(): array {
        $plugins = [];

        foreach (self::get_plugins_state() as $pluginstate) {
            if (!$pluginstate["enabled"]) {
                continue;
            }

            $plugins[] = $pluginstate;
        }

        return $plugins;
    }

    /**
     * Check if a plugin is enabled.
     *
     * @param string $pluginname
     * @return bool
     */
    public static function is_plugin_enabled(string $pluginname): bool {
        $component = self::resolve_component_name($pluginname);
        $enabled = get_config($component, "enabled");
        if ($enabled === false || $enabled === null || $enabled === "") {
            return true;
        }

        return (bool) $enabled;
    }

    /**
     * Enable or disable a plugin.
     *
     * @param string $pluginname
     * @param bool $enabled
     * @return void
     */
    public static function set_plugin_enabled(string $pluginname, bool $enabled): void {
        $component = self::resolve_component_name($pluginname);
        set_config("enabled", $enabled ? 1 : 0, $component);
    }

    /**
     * Move a plugin up or down in the menu ordering.
     *
     * @param string $pluginname
     * @param string $direction
     * @return void
     */
    public static function move_plugin(string $pluginname, string $direction): void {
        $pluginname = self::resolve_plugin_name($pluginname);
        $orderednames = array_column(self::get_plugins_state(), "name");
        $index = array_search($pluginname, $orderednames, true);

        if ($index === false) {
            return;
        }

        if ($direction == "up" && $index > 0) {
            $swapindex = $index - 1;
        } else if ($direction == "down" && $index < count($orderednames) - 1) {
            $swapindex = $index + 1;
        } else {
            return;
        }

        $current = $orderednames[$index];
        $orderednames[$index] = $orderednames[$swapindex];
        $orderednames[$swapindex] = $current;

        self::save_plugin_order($orderednames);
    }

    /**
     * Persist the plugin order.
     *
     * @param array $orderednames
     * @return void
     */
    public static function save_plugin_order(array $orderednames): void {
        $sortorder = 1;
        foreach ($orderednames as $pluginname) {
            $component = self::resolve_component_name($pluginname);
            set_config("sortorder", $sortorder, $component);
            $sortorder++;
        }
    }

    /**
     * Function categorysorts
     *
     * @return int[]
     */
    public static function categorysorts() {
        return [
            self::CAT_ACADEMIC => 100,
            self::CAT_PEDAGOGIC => 200,
            self::CAT_FINANCIAL => 300,
            self::CAT_TOOLS => 800,
            self::CAT_SETTINGS => 900,
        ];
    }

    /**
     * Function get_menu_by_category
     *
     * @param context $context
     * @return array|array[]
     */
    public static function get_menu_by_category(context $context): array {
        $categories = [
            self::CAT_ACADEMIC => [],
            self::CAT_PEDAGOGIC => [],
            self::CAT_FINANCIAL => [],
            self::CAT_TOOLS => [],
            self::CAT_SETTINGS => [],
        ];

        foreach (self::get_integrated_plugins() as $pluginstate) {
            $component = $pluginstate["component"];
            $menufqcn = "\\{$component}\\" . self::CLASS_MENU;

            if (!class_exists($menufqcn) || !method_exists($menufqcn, "get_definition")) {
                continue;
            }

            try {
                $definition = $menufqcn::get_definition($context);
            } catch (\Throwable $e) {
                debugging("Kopere Dashboard menu failed for {$component}: " . $e->getMessage(), DEBUG_DEVELOPER);
                continue;
            }

            if (empty($definition) || empty($definition["category"]) || empty($definition["items"])) {
                continue;
            }

            $cat = $definition["category"];
            if (!array_key_exists($cat, $categories)) {
                continue;
            }

            foreach ($definition["items"] as $item) {
                $categories[$cat][] = $item;
            }
        }

        return $categories;
    }


    /**
     * Return the dashboard KPIs exposed by enabled plugins.
     *
     * Each plugin can expose a class named \component\home_kpi with:
     * - public static $priority = 10;
     * - public static function get_metric(context $context): ?array;
     *
     * Lower priority numbers are displayed first.
     *
     * @param context $context
     * @param int $limit
     * @return array
     */
    public static function get_home_kpis(context $context, int $limit = 4): array {
        $limit = max(1, min(12, $limit));
        $rows = [];

        foreach (self::get_integrated_plugins() as $pluginstate) {
            $component = $pluginstate["component"];
            $kpifqcn = "\\{$component}\\" . self::CLASS_HOME_KPI;

            if (!class_exists($kpifqcn) || !method_exists($kpifqcn, "get_metric")) {
                continue;
            }

            $priority = self::DEFAULT_SORTORDER;
            if (property_exists($kpifqcn, "priority")) {
                $priority = (int) $kpifqcn::$priority;
            }

            try {
                $metric = $kpifqcn::get_metric($context);
            } catch (\Throwable $e) {
                debugging("Kopere Dashboard KPI failed for {$component}: " . $e->getMessage(), DEBUG_DEVELOPER);
                continue;
            }

            $metric = self::normalise_home_kpi($component, $priority, $metric);
            if ($metric !== null) {
                $rows[] = $metric;
            }
        }

        usort($rows, static function(array $a, array $b): int {
            if ($a["priority"] == $b["priority"]) {
                return strcmp($a["plugin"], $b["plugin"]);
            }

            return $a["priority"] <=> $b["priority"];
        });

        return array_slice($rows, 0, $limit);
    }

    /**
     * Normalise one KPI row for the dashboard template.
     *
     * @param string $component
     * @param int $priority
     * @param array|null $metric
     * @return array|null
     */
    private static function normalise_home_kpi(string $component, int $priority, ?array $metric): ?array {
        if (empty($metric)) {
            return null;
        }

        $title = trim((string)($metric["title"] ?? self::get_plugin_display_name($component)));
        $value = trim((string)($metric["value"] ?? ""));
        if ($title == "" || $value == "") {
            return null;
        }

        $url = trim((string)($metric["url"] ?? ""));

        return [
            "plugin" => $component,
            "priority" => $priority,
            "title" => $title,
            "value" => $value,
            "subtitle" => trim((string)($metric["subtitle"] ?? "")),
            "style" => trim((string)($metric["style"] ?? "kopere_dashboard-kpi-white")),
            "url" => $url,
            "hasurl" => $url != "",
        ];
    }

    /**
     * Function get_all_capability_definitions
     *
     * @return array
     */
    public static function get_all_capability_definitions(): array {
        $caps = [];

        foreach (self::get_integrated_plugins() as $pluginstate) {
            $component = $pluginstate["component"];
            $capsfqcn = "\\{$component}\\" . self::CLASS_CAPABILITIES_PROVIDER;

            if (!class_exists($capsfqcn) || !method_exists($capsfqcn, "get_capabilities")) {
                continue;
            }

            try {
                $caps += $capsfqcn::get_capabilities();
            } catch (\Throwable $e) {
                debugging("Kopere Dashboard capabilities failed for {$component}: " . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }

        return $caps;
    }

    /**
     * Function category_label
     *
     * @param string $category
     * @return string
     * @throws coding_exception
     */
    public static function category_label(string $category): string {
        if ($category == self::CAT_ACADEMIC) {
            return get_string("cat_academic", "local_kopere_dashboard");
        }
        if ($category == self::CAT_PEDAGOGIC) {
            return get_string("cat_pedagogic", "local_kopere_dashboard");
        }
        if ($category == self::CAT_FINANCIAL) {
            return get_string("cat_financial", "local_kopere_dashboard");
        }
        if ($category == self::CAT_TOOLS) {
            return get_string("cat_tools", "local_kopere_dashboard");
        }
        if ($category == self::CAT_SETTINGS) {
            return get_string("cat_settings", "local_kopere_dashboard");
        }
        return $category;
    }

    /**
     * Return the plugin display name.
     *
     * @param string $pluginname
     * @return string
     */
    public static function get_plugin_display_name(string $pluginname): string {
        $component = self::resolve_component_name($pluginname);
        if (get_string_manager()->string_exists("pluginname", $component)) {
            return get_string("pluginname", $component);
        }

        return self::resolve_plugin_name($pluginname);
    }

    /**
     * Return the configured sort order of a plugin.
     *
     * @param string $pluginname
     * @return int
     */
    private static function get_plugin_sortorder(string $pluginname): int {
        $component = self::resolve_component_name($pluginname);
        $sortorder = get_config($component, "sortorder");
        if ($sortorder === false || $sortorder === null || $sortorder === "") {
            return self::DEFAULT_SORTORDER;
        }

        return (int) $sortorder;
    }

    /**
     * Resolve a plugin name or component into a Moodle component name.
     *
     * @param string $pluginname
     * @return string
     */
    private static function resolve_component_name(string $pluginname): string {
        if (strpos($pluginname, "koperedashboard_") === 0 || strpos($pluginname, "local_") === 0) {
            return $pluginname;
        }

        $koperedashboardplugins = core_component::get_plugin_list(self::PLUGINTYPE_KOPERE_DASHBOARD);
        if (array_key_exists($pluginname, $koperedashboardplugins)) {
            return "koperedashboard_{$pluginname}";
        }

        $localplugins = core_component::get_plugin_list(self::PLUGINTYPE_LOCAL);
        if (array_key_exists($pluginname, $localplugins)) {
            return "local_{$pluginname}";
        }

        return "koperedashboard_{$pluginname}";
    }

    /**
     * Resolve a plugin name or component into its short plugin name.
     *
     * @param string $pluginname
     * @return string
     */
    private static function resolve_plugin_name(string $pluginname): string {
        if (strpos($pluginname, "koperedashboard_") === 0) {
            return substr($pluginname, strlen("koperedashboard_"));
        }

        if (strpos($pluginname, "local_") === 0) {
            return substr($pluginname, strlen("local_"));
        }

        return $pluginname;
    }
}
