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
 * webpages_service.php
 *
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_pages\service;

use coding_exception;
use context_system;
use core_text;
use dml_exception;
use html_writer;
use local_kopere_dashboard\util\html;
use moodle_exception;
use moodle_url;
use stdClass;
use Throwable;

/**
 * Service for Kopere Dashboard pages using the legacy Kopere Dashboard tables.
 */
class webpages_service {
    /**
     * Return true when the current user can manage pages.
     *
     * @return bool
     * @throws coding_exception
     * @throws \dml_exception
     */
    public static function can_manage(): bool {
        return has_capability("koperedashboard/pages:manage", context_system::instance());
    }

    /**
     * Return the public index URL.
     *
     * @return moodle_url
     */
    public static function public_index_url(): moodle_url {
        return new moodle_url("/local/kopere_dashboard/page/");
    }

    /**
     * Return a public page URL.
     *
     * @param string $link
     * @return moodle_url
     * @throws \core\exception\moodle_exception
     */
    public static function public_page_url(string $link): moodle_url {
        return new moodle_url("/local/kopere_dashboard/page/", ["p" => $link]);
    }

    /**
     * Return a public menu URL.
     *
     * @param string $link
     * @return moodle_url
     * @throws \core\exception\moodle_exception
     */
    public static function public_menu_url(string $link): moodle_url {
        return new moodle_url("/local/kopere_dashboard/page/", ["menu" => $link]);
    }

    /**
     * Return the plugin management URL.
     *
     * @return moodle_url
     */
    public static function manage_url(): moodle_url {
        return new moodle_url("/local/kopere_dashboard/plugins/pages/");
    }

    /**
     * Return default page layout.
     *
     * @return string
     * @throws \dml_exception
     */
    public static function get_default_theme(): string {
        $theme = trim((string) get_config("koperedashboard_pages", "webpages_theme"));
        if ($theme == "") {
            return "standard";
        }

        return self::clean_theme($theme);
    }

    /**
     * Clean a theme layout name.
     *
     * @param string $theme
     * @return string
     */
    public static function clean_theme(string $theme): string {
        $valid = array_column(self::list_themes(), "key");
        if (in_array($theme, $valid, true)) {
            return $theme;
        }

        return "standard";
    }

    /**
     * Return available Moodle page layouts.
     *
     * @return array
     */
    public static function list_themes(): array {
        return [
            ["key" => "base", "value" => "Base"],
            ["key" => "standard", "value" => "Standard"],
            ["key" => "popup", "value" => "Popup"],
            ["key" => "frametop", "value" => "Frame top"],
            ["key" => "print", "value" => "Print"],
            ["key" => "report", "value" => "Report"],
        ];
    }

    /**
     * Return a readable theme name.
     *
     * @param string $theme
     * @return string
     */
    public static function theme_name(string $theme): string {
        foreach (self::list_themes() as $row) {
            if ($row["key"] == $theme) {
                return $row["value"];
            }
        }

        return "-";
    }

    /**
     * Build a URL slug from a title.
     *
     * @param string $title
     * @return string
     */
    public static function slug(string $title): string {
        $slug = html::link($title);
        $slug = preg_replace('/[^a-z0-9\-_]/', '-', core_text::strtolower($slug));
        $slug = preg_replace('/[-_]{2,}/', '-', $slug);
        $slug = trim($slug, "-_");

        return $slug == "" ? "page" : $slug;
    }

    /**
     * Return a unique page link.
     *
     * @param string $title
     * @param int $id
     * @return string
     * @throws dml_exception
     */
    public static function unique_page_link(string $title, int $id = 0): string {
        return self::unique_link("local_kopere_dashboard_pages", $title, $id);
    }

    /**
     * Return a unique menu link.
     *
     * @param string $title
     * @param int $id
     * @return string
     * @throws dml_exception
     */
    public static function unique_menu_link(string $title, int $id = 0): string {
        return self::unique_link("local_kopere_dashboard_menu", $title, $id);
    }

    /**
     * Return a unique link for a table.
     *
     * @param string $table
     * @param string $title
     * @param int $id
     * @return string
     * @throws dml_exception
     */
    private static function unique_link(string $table, string $title, int $id = 0): string {
        global $DB;

        $base = self::slug($title);
        $link = $base;
        $suffix = 2;

        while ($DB->record_exists_select($table, "link = :link AND id <> :id", ["link" => $link, "id" => $id])) {
            $link = "{$base}-{$suffix}";
            $suffix++;
        }

        return $link;
    }

    /**
     * Return a menu record.
     *
     * @param int $id
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_menu(int $id): ?stdClass {
        global $DB;

        if (!$id) {
            return null;
        }

        $menu = $DB->get_record("local_kopere_dashboard_menu", ["id" => $id]);
        return $menu ?: null;
    }

    /**
     * Return a menu by public link.
     *
     * @param string $link
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_menu_by_link(string $link): ?stdClass {
        global $DB;

        $menu = $DB->get_record("local_kopere_dashboard_menu", ["link" => $link]);
        return $menu ?: null;
    }

    /**
     * Return a page record.
     *
     * @param int $id
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_page(int $id): ?stdClass {
        global $DB;

        if (!$id) {
            return null;
        }

        $page = $DB->get_record("local_kopere_dashboard_pages", ["id" => $id]);
        return $page ?: null;
    }

    /**
     * Return a page by public link.
     *
     * @param string $link
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_page_by_link(string $link): ?stdClass {
        global $DB;

        $page = $DB->get_record("local_kopere_dashboard_pages", ["link" => $link]);
        return $page ?: null;
    }

    /**
     * Create a default menu record object.
     *
     * @param int $parentid
     * @return stdClass
     */
    public static function default_menu(int $parentid = 0): stdClass {
        $menu = new stdClass();
        $menu->id = 0;
        $menu->menuid = $parentid;
        $menu->title = "";
        $menu->link = "";
        $menu->inheader = 1;
        $menu->icon = "article";

        return $menu;
    }

    /**
     * Create a default page record object.
     *
     * @param int $menuid
     * @return stdClass
     * @throws dml_exception
     */
    public static function default_page(int $menuid = 0): stdClass {
        $page = new stdClass();
        $page->id = 0;
        $page->menuid = $menuid;
        $page->courseid = 0;
        $page->title = "";
        $page->link = "";
        $page->text = "";
        $page->theme = self::get_default_theme();
        $page->visible = 1;
        $page->pageorder = self::next_page_order($menuid);
        $page->config = "";

        return $page;
    }

    /**
     * Return menu options for forms.
     *
     * @param int $notmenuid
     * @param int $parentid
     * @param string $prefix
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function menu_options(int $notmenuid = 0, int $parentid = 0, string $prefix = ""): array {
        global $DB;

        $options = [];
        if ($parentid == 0) {
            $options[] = ["key" => 0, "value" => get_string("root_menu", "koperedashboard_pages")];
        }

        $menus = $DB->get_records("local_kopere_dashboard_menu", ["menuid" => $parentid], "title ASC");
        foreach ($menus as $menu) {
            if ((int) $menu->id == $notmenuid) {
                continue;
            }

            $options[] = [
                "key" => (int) $menu->id,
                "value" => $prefix . format_string($menu->title),
            ];

            $children = self::menu_options($notmenuid, (int) $menu->id, $prefix . "- ");
            if (!empty($children)) {
                $options = array_merge($options, $children);
            }
        }

        return $options;
    }

    /**
     * Return course options for forms.
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function course_options(): array {
        global $DB;

        $rows = [["key" => 0, "value" => get_string("none_course", "koperedashboard_pages")]];
        $courses = $DB->get_records_sql("SELECT id, fullname FROM {course} WHERE id > 1 ORDER BY fullname ASC");

        foreach ($courses as $course) {
            $rows[] = [
                "key" => (int) $course->id,
                "value" => format_string($course->fullname),
            ];
        }

        return $rows;
    }

    /**
     * Save a menu.
     *
     * @param stdClass $menu
     * @return int
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function save_menu(stdClass $menu): int {
        global $DB;

        $menu->id = (int) ($menu->id ?? 0);
        $menu->menuid = (int) ($menu->menuid ?? 0);
        $menu->title = trim((string) ($menu->title ?? ""));
        $menu->link = trim((string) ($menu->link ?? ""));
        $menu->inheader = !empty($menu->inheader) ? 1 : 0;
        $menu->icon = trim((string) ($menu->icon ?? ""));

        if ($menu->title == "") {
            throw new moodle_exception("error_menu_title", "koperedashboard_pages");
        }

        if ($menu->link == "") {
            $menu->link = self::unique_menu_link($menu->title, $menu->id);
        } else {
            $menu->link = self::slug($menu->link);
        }

        if ($DB->record_exists_select(
            "local_kopere_dashboard_menu", "link = :link AND id <> :id", ["link" => $menu->link, "id" => $menu->id]
        )) {
            throw new moodle_exception("error_duplicate_link", "koperedashboard_pages");
        }

        if ($menu->id) {
            $DB->update_record("local_kopere_dashboard_menu", $menu);
            return $menu->id;
        }

        return (int) $DB->insert_record("local_kopere_dashboard_menu", $menu);
    }

    /**
     * Save a page.
     *
     * @param stdClass $page
     * @return int
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function save_page(stdClass $page): int {
        global $DB;

        $page->id = (int) ($page->id ?? 0);
        $page->menuid = (int) ($page->menuid ?? 0);
        $page->courseid = (int) ($page->courseid ?? 0);
        $page->title = trim((string) ($page->title ?? ""));
        $page->link = trim((string) ($page->link ?? ""));
        $page->text = (string) ($page->text ?? "");
        $page->theme = self::clean_theme((string) ($page->theme ?? "standard"));
        $page->visible = !empty($page->visible) ? 1 : 0;
        $page->pageorder = (int) ($page->pageorder ?? 0);
        $page->config = (string) ($page->config ?? "");

        if ($page->title == "") {
            throw new moodle_exception("error_page_title", "koperedashboard_pages");
        }

        if ($page->link == "") {
            $page->link = self::unique_page_link($page->title, $page->id);
        } else {
            $page->link = self::slug($page->link);
        }

        if (!$page->pageorder) {
            $page->pageorder = self::next_page_order($page->menuid);
        }

        if ($DB->record_exists_select(
            "local_kopere_dashboard_pages", "link = :link AND id <> :id", ["link" => $page->link, "id" => $page->id]
        )) {
            throw new moodle_exception("error_duplicate_link", "koperedashboard_pages");
        }

        if ($page->id) {
            $DB->update_record("local_kopere_dashboard_pages", $page);
            return $page->id;
        }

        return (int) $DB->insert_record("local_kopere_dashboard_pages", $page);
    }

    /**
     * Return the next page order for a menu.
     *
     * @param int $menuid
     * @return int
     * @throws dml_exception
     */
    public static function next_page_order(int $menuid): int {
        global $DB;

        $max = $DB->get_field_sql(
            "SELECT MAX(pageorder) FROM {local_kopere_dashboard_pages} WHERE menuid = :menuid", ["menuid" => $menuid]
        );
        return ((int) $max) + 10;
    }

    /**
     * Delete a page.
     *
     * @param int $id
     * @return void
     * @throws dml_exception
     */
    public static function delete_page(int $id): void {
        global $DB;

        $DB->delete_records("local_kopere_dashboard_pages", ["id" => $id]);
    }

    /**
     * Delete a menu when it has no children or pages.
     *
     * @param int $id
     * @return void
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function delete_menu(int $id): void {
        global $DB;

        if (self::menu_has_children_or_pages($id)) {
            throw new moodle_exception("error_menu_not_empty", "koperedashboard_pages");
        }

        $DB->delete_records("local_kopere_dashboard_menu", ["id" => $id]);
    }

    /**
     * Check if a menu has children or pages.
     *
     * @param int $id
     * @return bool
     * @throws dml_exception
     */
    public static function menu_has_children_or_pages(int $id): bool {
        global $DB;

        return $DB->record_exists("local_kopere_dashboard_menu", ["menuid" => $id]) ||
            $DB->record_exists("local_kopere_dashboard_pages", ["menuid" => $id]);
    }

    /**
     * Move a page up or down inside the same menu.
     *
     * @param int $pageid
     * @param string $direction
     * @return void
     * @throws dml_exception
     */
    public static function move_page(int $pageid, string $direction): void {
        global $DB;

        $page = self::get_page($pageid);
        if (!$page) {
            return;
        }

        $operator = $direction == "up" ? "<" : ">";
        $sort = $direction == "up" ? "pageorder DESC, id DESC" : "pageorder ASC, id ASC";
        $sql = "SELECT *
                  FROM {local_kopere_dashboard_pages}
                 WHERE menuid = :menuid
                   AND pageorder {$operator} :pageorder
              ORDER BY {$sort}";
        $other = $DB->get_record_sql($sql, ["menuid" => $page->menuid, "pageorder" => $page->pageorder], IGNORE_MULTIPLE);

        if (!$other) {
            return;
        }

        $currentorder = (int) $page->pageorder;
        $page->pageorder = (int) $other->pageorder;
        $other->pageorder = $currentorder;

        $DB->update_record("local_kopere_dashboard_pages", $page);
        $DB->update_record("local_kopere_dashboard_pages", $other);
    }

    /**
     * Return rows for the management page.
     *
     * @param int $parentid
     * @param int $level
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \dml_exception
     */
    public static function admin_rows(int $parentid = 0, int $level = 0): array {
        global $DB;

        $rows = [];
        $menus = $DB->get_records("local_kopere_dashboard_menu", ["menuid" => $parentid], "title ASC");

        foreach ($menus as $menu) {
            $rows[] = self::admin_menu_row($menu, $level);
            $rows = array_merge($rows, self::admin_rows((int) $menu->id, $level + 1));
        }

        $pages = $DB->get_records("local_kopere_dashboard_pages", ["menuid" => $parentid], "pageorder ASC, title ASC");
        foreach ($pages as $page) {
            $rows[] = self::admin_page_row($page, $level);
        }

        return $rows;
    }

    /**
     * Build an admin menu row.
     *
     * @param stdClass $menu
     * @param int $level
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    private static function admin_menu_row(stdClass $menu, int $level): array {
        return [
            "ismenu" => true,
            "ispage" => false,
            "id" => (int) $menu->id,
            "indent" => $level * 24,
            "icon" => trim((string) $menu->icon) ?: "folder",
            "title" => format_string($menu->title),
            "link" => s($menu->link),
            "publicurl" => self::public_menu_url((string) $menu->link),
            "editurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/menu_edit.php", ["id" => $menu->id])),
            "deleteurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/menu_delete.php", ["id" => $menu->id])),
            "pagecreateurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php", ["menuid" => $menu->id])),
            "menucreateurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/menu_edit.php", ["menuid" => $menu->id])),
            "visiblelabel" => !empty($menu->inheader) ? get_string("yes") : get_string("no"),
            "typelabel" => get_string("type_menu", "koperedashboard_pages"),
        ];
    }

    /**
     * Build an admin page row.
     *
     * @param stdClass $page
     * @param int $level
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    private static function admin_page_row(stdClass $page, int $level): array {
        return [
            "ismenu" => false,
            "ispage" => true,
            "id" => (int) $page->id,
            "indent" => $level * 24,
            "icon" => "description",
            "title" => format_string($page->title),
            "link" => s($page->link),
            "publicurl" => self::public_page_url((string) $page->link),
            "viewurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_view.php", ["id" => $page->id])),
            "editurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_edit.php", ["id" => $page->id])),
            "deleteurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/page_delete.php", ["id" => $page->id])),
            "moveupurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/sort.php", [
                "id" => $page->id,
                "direction" => "up",
                "sesskey" => sesskey(),
            ])),
            "movedownurl" => (new moodle_url("/local/kopere_dashboard/plugins/pages/sort.php", [
                "id" => $page->id,
                "direction" => "down",
                "sesskey" => sesskey(),
            ])),
            "visiblelabel" => !empty($page->visible) ? get_string("yes") : get_string("no"),
            "typelabel" => get_string("type_page", "koperedashboard_pages"),
        ];
    }

    /**
     * Return public listing data.
     *
     * @param string $menulink
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function public_listing(string $menulink = ""): array {
        global $DB;

        $data = ["menus" => []];

        if ($menulink != "") {
            $menu = self::get_menu_by_link($menulink);
            if (!$menu) {
                return ["menus" => [], "hasmenus" => false];
            }
            $menus = [$menu];
        } else {
            $menus = $DB->get_records("local_kopere_dashboard_menu", ["inheader" => 1], "title ASC");
        }

        foreach ($menus as $menu) {
            $pages = $DB->get_records(
                "local_kopere_dashboard_pages",
                ["visible" => 1, "menuid" => $menu->id], "pageorder ASC, title ASC"
            );
            $pagecontexts = [];
            foreach ($pages as $page) {
                $pagecontexts[] = self::public_page_card($page);
            }

            $data["menus"][] = [
                "title" => format_string($menu->title),
                "link" => s($menu->link),
                "url" => self::public_menu_url((string) $menu->link),
                "showlink" => $menulink == "",
                "pages" => $pagecontexts,
                "haspages" => !empty($pagecontexts),
            ];
        }
        $data["hasmenus"] = !empty($data["menus"]);

        return $data;
    }

    /**
     * Build a public page card.
     *
     * @param stdClass $page
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    public static function public_page_card(stdClass $page): array {
        global $OUTPUT;

        $courseprice = self::optional_course_price((int) $page->courseid);

        return [
            "title" => format_string($page->title),
            "link" => self::public_page_url($page->link),
            "summary" => self::page_summary($page->text),
            "access" => get_string("webpages_access", "koperedashboard_pages"),
            "image" => $OUTPUT->image_url("icon", "local_kopere_dashboard"),
            "courseprice" => $courseprice,
            "hascourseprice" => $courseprice != "",
        ];
    }

    /**
     * Build a text summary.
     *
     * @param string $text
     * @return string
     */
    public static function page_summary(string $html): string {
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = strip_tags($html);
        $html = preg_replace('/\s+/', ' ', $html);
        $html = trim($html);

        return s(html::truncate_text($html, 260));
    }

    /**
     * Return optional Kopere Pay price when the old integration exists.
     *
     * @param int $courseid
     * @return string
     */
    private static function optional_course_price(int $courseid): string {
        global $CFG, $DB;

        if (!$courseid || !file_exists($CFG->dirroot . "/local/kopere_pay/lib.php")) {
            return "";
        }

        try {
            $dbman = $DB->get_manager();
            if (!$dbman->table_exists("kopere_pay_detalhe")) {
                return "";
            }

            $detail = $DB->get_record("kopere_pay_detalhe", ["course" => $courseid]);
            if (!$detail || !isset($detail->preco)) {
                return "";
            }

            $price = str_replace([".", ","], ["", "."], (string) $detail->preco);
            if ((float) ("0" . $price) <= 0) {
                return get_string("webpages_free", "koperedashboard_pages");
            }

            return "R$ " . s($detail->preco);
        } catch (Throwable $e) {
            debugging("Kopere Dashboard pages price lookup failed: " . $e->getMessage(), DEBUG_DEVELOPER);
            return "";
        }
    }

    /**
     * Expand legacy Kopere Dashboard dynamic shortcodes.
     *
     * Syntax: [[kopere_x::classname->method(parameter)]]
     *
     * @param string $text
     * @return string
     */
    public static function expand_shortcodes(string $text): string {
        $regex = '/\[\[(kopere_\w+)::(\w+)(?:->|-&gt;)(\w+)\((.*?)\)\]\]/';
        if (!preg_match_all($regex, $text, $matches, PREG_SET_ORDER)) {
            return $text;
        }

        foreach ($matches as $match) {
            $component = $match[1];
            $classname = $match[2];
            $method = $match[3];
            $parameter = $match[4];
            $fqcn = "\\local_{$component}\\{$classname}";

            if (!class_exists($fqcn) || !method_exists($fqcn, $method)) {
                continue;
            }

            try {
                $replacement = call_user_func([$fqcn, $method], $parameter);
                $text = str_replace($match[0], (string) $replacement, $text);
            } catch (Throwable $e) {
                debugging("Kopere Dashboard page shortcode failed: " . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }

        return $text;
    }

    /**
     * Return analytics HTML from settings.
     *
     * @return string
     * @throws \dml_exception
     */
    public static function analytics_html(): string {
        $value = trim((string) get_config("koperedashboard_pages", "webpages_analytics_id"));
        if ($value == "") {
            return "";
        }

        if (stripos($value, "<script") !== false || stripos($value, "gtag(") !== false) {
            return $value;
        }

        $id = s($value);
        return "\n<script async src=\"https://www.googletagmanager.com/gtag/js?id={$id}\"></script>\n" .
            "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}" .
            "gtag('js',new Date());gtag('config','{$id}');</script>\n";
    }

    /**
     * Return HTML to display scheduled notifications.
     *
     * @return string
     * @throws \coding_exception
     */
    public static function flash(): string {
        $message = optional_param("message", "", PARAM_ALPHAEXT);
        if ($message == "") {
            return "";
        }

        $map = [
            "created" => get_string("msg_created", "koperedashboard_pages"),
            "updated" => get_string("msg_updated", "koperedashboard_pages"),
            "deleted" => get_string("msg_deleted", "koperedashboard_pages"),
            "settings" => get_string("msg_settings", "koperedashboard_pages"),
        ];

        if (!isset($map[$message])) {
            return "";
        }

        return html_writer::div($map[$message], "alert alert-success");
    }
}
