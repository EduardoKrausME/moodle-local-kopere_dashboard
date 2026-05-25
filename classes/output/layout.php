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
 * layout.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\output;

use context;
use core_text;
use local_kopere_dashboard\api\subplugin_manager;
use moodle_url;

/**
 * Class layout
 */
class layout {

    /**
     * Function page_render
     *
     * @param \context $context
     * @param $content
     * @param $addpage
     * @param string $classname
     * @param string $afterheader
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \moodle_exception
     */
    public static function page_render(context $context, $content, $addpage, $classname = "", $afterheader = ""): array {
        global $PAGE, $OUTPUT;

        $titles = explode($PAGE::TITLE_SEPARATOR, $PAGE->title);

        $layoutdata = [
            "kopere_dashboard-title" => $titles[0],
            "brand" => get_string("pluginname", "local_kopere_dashboard"),
            "brandurl" => new moodle_url("/local/kopere_dashboard/"),
            "primaryitems" => [],
            "groups" => [],
            "footeritems" => [],
        ];

        $activeparent = null;
        $activechild = null;

        // Primary: Dashboard (only if the user can see it).
        $layoutdata["primaryitems"][] = self::item(
            get_string("dashboard", "local_kopere_dashboard"),
            new moodle_url("/local/kopere_dashboard/"),
            "dashboard"
        );

        // Subplugin groups (Academic / Pedagogic / Financial / Settings).
        $menus = subplugin_manager::get_menu_by_category($context);
        $settingsitems = [];

        foreach ($menus as $category => $menuitens) {
            if (empty($menuitens)) {
                continue;
            }

            $groupitems = [];
            foreach ($menuitens as $menuitem) {
                $built = self::item_from_definition($menuitem, $context);
                if (!empty($built)) {
                    if (empty($activeparent) && !empty($built["active"])) {
                        $activeparent = $built;
                        $activechild = $built["activechild"] ?? null;
                    }

                    $groupitems[] = $built;
                }
            }

            if (empty($groupitems)) {
                continue;
            }

            if ($category == subplugin_manager::CAT_SETTINGS) {
                $settingsitems = array_merge($settingsitems, $groupitems);
            } else {
                $layoutdata["groups"][] = [
                    "title" => subplugin_manager::category_label($category),
                    "items" => $groupitems,
                ];
            }
        }

        // Settings group (subplugins + core pages).

        if (has_capability("local/kopere_dashboard:managepermissions", $context)) {
            $settingsitems[] = self::item(
                get_string("globalsettings", "local_kopere_dashboard"),
                new moodle_url("/local/kopere_dashboard/admin_plugins.php"),
                "settings"
            );
        }

        if (has_capability("local/kopere_dashboard:viewaudit", $context)) {
            $settingsitems[] = self::item(
                get_string("audit", "local_kopere_dashboard"),
                [
                    new moodle_url("/local/kopere_dashboard/audit.php"),
                    new moodle_url("/local/kopere_dashboard/audit_details.php"),
                ],
                "assessment"
            );
        }

        if (has_capability("local/kopere_dashboard:managepermissions", $context)) {
            $settingsitems[] = self::item(
                get_string("permissions", "local_kopere_dashboard"),
                new moodle_url("/local/kopere_dashboard/permissions.php"),
                "admin_panel_settings"
            );
        }

        if (!empty($settingsitems)) {
            foreach ($settingsitems as $settingsitem) {
                if (empty($activeparent) && !empty($settingsitem["active"])) {
                    $activeparent = $settingsitem;
                    $activechild = $settingsitem["activechild"] ?? null;
                }
            }

            $layoutdata["groups"][] = [
                "title" => get_string("cat_settings", "local_kopere_dashboard"),
                "items" => $settingsitems,
            ];
        }

        $layoutdata["footeritems"][] = self::item(
            get_string("menu_about", "local_kopere_dashboard"),
            new moodle_url("/local/kopere_dashboard/about.php"),
            "logo_dev",
            get_string("menu_about_desc", "local_kopere_dashboard")
        );

        foreach ($layoutdata["footeritems"] as $footeritem) {
            if (empty($activeparent) && !empty($footeritem["active"])) {
                $activeparent = $footeritem;
                $activechild = $footeritem["activechild"] ?? null;
            }
        }

        $layoutdata["hasfooteritems"] = !empty($layoutdata["footeritems"]);
        $layoutdata["breadcrumb"] = self::build_breadcrumb($titles[0], $addpage, $activeparent, $activechild);

        $layoutdata["content-class"] = $classname;
        $layoutdata["content"] = $content;

        $PAGE->requires->js_call_amd("local_kopere_dashboard/sidebar", "init");

        echo $OUTPUT->header();
        echo $afterheader;
        echo $OUTPUT->render_from_template("local_kopere_dashboard/page", $layoutdata);
        echo $OUTPUT->footer();
        die;
    }

    /**
     * Function item
     *
     * @param string $title
     * @param moodle_url|moodle_url[] $url
     * @param string $icon
     * @param string $description
     * @return array
     * @throws \moodle_exception
     * @throws \core\exception\moodle_exception
     */
    private static function item(string $title, $url, string $icon, string $description = ""): array {
        $primaryurl = self::to_url($url);
        $active = self::is_active_url($url);

        return [
            "title" => $title,
            "description" => $description,
            "url" => $primaryurl->out(false),
            "icon" => $icon,
            "active" => $active,
        ];
    }

    /**
     * Build the breadcrumb using the active parent menu and active child menu.
     *
     * @param string $currenttitle
     * @param bool $addpage
     * @param array|null $activeparent
     * @param array|null $activechild
     * @return array
     * @throws \moodle_exception
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    private static function build_breadcrumb(string $currenttitle, bool $addpage, ?array $activeparent, ?array $activechild
    ): array {
        $breadcrumb = [];

        self::add_breadcrumb_item(
            $breadcrumb,
            get_string("pluginname", "local_kopere_dashboard"),
            new moodle_url("/local/kopere_dashboard/")
        );

        if (!empty($activeparent["title"])) {
            $parenttitle = trim($activeparent["title"]);
            if ($parenttitle != "" && !self::same_label($parenttitle, $currenttitle)) {
                self::add_breadcrumb_item($breadcrumb, $parenttitle, $activeparent["url"] ?? null);
            }
        }

        if (!empty($activechild["title"])) {
            $childtitle = trim($activechild["title"]);
            if ($childtitle != "" && !self::same_label($childtitle, $currenttitle)) {
                self::add_breadcrumb_item($breadcrumb, $childtitle, $activechild["url"] ?? null);
            }
        }

        if ($addpage) {
            self::add_breadcrumb_item($breadcrumb, $currenttitle, null, true);
        }

        $lastindex = count($breadcrumb) - 1;
        foreach ($breadcrumb as $index => $item) {
            $breadcrumb[$index]["iscurrent"] = ($index == $lastindex);
            $breadcrumb[$index]["hasseparator"] = ($index < $lastindex);
        }

        return $breadcrumb;
    }

    /**
     * Add a breadcrumb item if it is not a duplicate of the previous one.
     *
     * @param array $breadcrumb
     * @param string $label
     * @param mixed $url
     * @param bool $forcecurrent
     * @return void
     * @throws \moodle_exception
     * @throws \core\exception\moodle_exception
     */
    private static function add_breadcrumb_item(array &$breadcrumb, string $label, $url = null, bool $forcecurrent = false): void {
        $label = trim($label);
        if ($label == "") {
            return;
        }

        $lastitem = end($breadcrumb);
        if ($lastitem !== false && self::same_label($lastitem["label"] ?? "", $label)) {
            if ($forcecurrent) {
                $lastindex = array_key_last($breadcrumb);
                $breadcrumb[$lastindex]["url"] = null;
            }
            return;
        }

        $item = [
            "label" => $label,
            "url" => null,
        ];

        if (!$forcecurrent && !empty($url)) {
            $item["url"] = self::to_url($url)->out(false);
        }

        $breadcrumb[] = $item;
    }

    /**
     * Compare breadcrumb labels in a case-insensitive way.
     *
     * @param string $left
     * @param string $right
     * @return bool
     */
    private static function same_label(string $left, string $right): bool {
        return core_text::strtolower(trim($left)) == core_text::strtolower(trim($right));
    }

    /**
     * Check if the current page matches one of the menu URLs.
     *
     * This comparison ignores a trailing "/index.php" and trailing slashes.
     *
     * @param mixed $url
     * @return bool
     */
    private static function is_active_url($url): bool {
        global $PAGE;

        if (is_string($url) || $url instanceof moodle_url) {
            $url = [$url];
        }

        $normalizepath = static function(string $path): string {
            $parsedpath = parse_url($path, PHP_URL_PATH);
            if (is_string($parsedpath) && $parsedpath !== "") {
                $path = $parsedpath;
            }

            $path = preg_replace("#/index\\.php$#", "", $path);
            $path = rtrim($path, "/");

            return $path === "" ? "/" : $path;
        };

        $currentpath = $normalizepath($PAGE->url->get_path());

        foreach ($url as $menuurl) {
            if ($menuurl instanceof moodle_url) {
                $path = $menuurl->get_path();
            } else if (is_string($menuurl)) {
                $path = $menuurl;
            } else {
                continue;
            }

            if ($normalizepath($path) === $currentpath) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build a menu item from a subplugin definition.
     *
     * @param array $definition
     * @param context $context
     * @return array|null
     * @throws \coding_exception
     * @throws \moodle_exception
     * @throws \core\exception\moodle_exception
     */
    private static function item_from_definition(array $definition, context $context): ?array {
        if (!self::can_render_definition($definition, $context)) {
            return null;
        }

        $title = $definition["title"] ?? "";
        $description = $definition["description"] ?? "";
        $icon = $definition["icon"] ?? "menu";
        $url = $definition["url"] ?? "";

        $item = self::item($title, $url, $icon, $description);

        $children = [];
        $hasactivechild = false;
        foreach (($definition["children"] ?? []) as $childdef) {
            if (empty($childdef["title"]) || empty($childdef["url"])) {
                continue;
            }

            if (!self::can_render_definition($childdef, $context)) {
                continue;
            }

            $child = self::item(
                $childdef["title"],
                $childdef["url"],
                $childdef["icon"] ?? "menu",
                $childdef["description"] ?? ""
            );
            $child["ischild"] = true;

            if (!empty($child["active"])) {
                $hasactivechild = true;
            }

            $children[] = $child;
        }

        if (!empty($children)) {
            $item["haschildren"] = true;
            $item["children"] = $children;
            $item["hasactivechild"] = $hasactivechild;
            $item["expanded"] = $hasactivechild;
            $item["active"] = (!empty($item["active"]) || $hasactivechild);

            if ($hasactivechild) {
                foreach ($children as $child) {
                    if (!empty($child["active"])) {
                        $item["activechild"] = $child;
                        break;
                    }
                }
            }
        }

        return $item;
    }

    /**
     * Check if a menu definition can be rendered for the current user.
     *
     * Supported formats for "capability":
     * - string: "capability/name"
     * - list:   ["cap/a", "cap/b"] (ANY/OR)
     * - map:    ["all" => ["cap/a"], "any" => ["cap/b", "cap/c"]]
     *
     * If missing/empty, the item is considered public to the current page.
     *
     * @param array $definition
     * @param context $context
     * @return bool
     * @throws \coding_exception
     */
    private static function can_render_definition(array $definition, context $context): bool {
        if (!array_key_exists("capability", $definition)) {
            return true;
        }

        $capability = $definition["capability"];

        if ($capability == null) {
            return true;
        }

        if (is_string($capability)) {
            $capability = trim($capability);
            if ($capability == "") {
                return true;
            }
            return has_capability($capability, $context);
        }

        if (!is_array($capability)) {
            return true;
        }

        $isassoc = array_keys($capability) != range(0, count($capability) - 1);
        if ($isassoc) {
            $all = (array) ($capability["all"] ?? []);
            $any = (array) ($capability["any"] ?? []);

            foreach ($all as $cap) {
                $cap = trim($cap);
                if ($cap != "" && !has_capability($cap, $context)) {
                    return false;
                }
            }

            if (empty($any)) {
                return true;
            }

            foreach ($any as $cap) {
                $cap = trim($cap);
                if ($cap != "" && has_capability($cap, $context)) {
                    return true;
                }
            }

            return false;
        }

        // List format: ANY/OR.
        foreach ($capability as $cap) {
            $cap = trim($cap);
            if ($cap != "" && has_capability($cap, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize definition URL values into a moodle_url.
     *
     * Subplugins may return moodle_url instances or strings.
     *
     * @param mixed $url
     * @return moodle_url
     * @throws \moodle_exception
     * @throws \core\exception\moodle_exception
     */
    private static function to_url($url): moodle_url {
        if (is_array($url)) {
            $first = reset($url);
            return self::to_url($first);
        }

        if ($url instanceof moodle_url) {
            return $url;
        }

        return new moodle_url($url);
    }
}
