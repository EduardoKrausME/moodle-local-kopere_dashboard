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
 * webpages file
 *
 * introduced 13/05/17 13:28
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use context_system;
use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_checkbox_select;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\util\config;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\end_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\html;
use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\server_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\util\url_util;
use local_kopere_dashboard\vo\local_kopere_dashboard_menu;
use local_kopere_dashboard\vo\local_kopere_dashboard_pages;
use moodle_url;

/**
 * Class webpages
 *
 * @package local_kopere_dashboard
 */
class webpages {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $DB, $CFG, $PAGE, $OUTPUT;

        dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"));
        dashboard_util::start_page(url_util::makeurl("webpages", "settings"), "Páginas-estáticas");

        $botao = button::add(get_string("webpages_menu_create", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "menu_edit"), "ml-4", false, true);
        title_util::print_h3(get_string("webpages_subtitle", "local_kopere_dashboard") . $botao, false);
        title_util::print_h6("webpages_subtitle_help");

        $menus = $DB->get_records("local_kopere_dashboard_menu", ["menuid" => 0], "title ASC");

        echo '<div class="element-box">';

        $detailsopen = $OUTPUT->image_url("details_open", "local_kopere_dashboard")->out(false);
        $detailsclose = $OUTPUT->image_url("details_close", "local_kopere_dashboard")->out(false);

        if (!$menus) {
            button::help("webpages", get_string("webpages_menu_help", "local_kopere_dashboard"), "Páginas-estáticas");
        } else {
            foreach ($menus as $key => $menu) {

                $bt1 = button::icon_popup_table("edit",
                    url_util::makeurl("webpages", "menu_edit", ["id" => $menu->id]));
                $bt2 = button::icon_popup_table("delete",
                    url_util::makeurl("webpages", "menu_delete", ["id" => $menu->id]));
                $menu->htmlid = "
                    <img src='{$detailsopen}'
                         src-open='{$detailsclose}'
                         src-close='{$detailsclose}'
                         data-id='{$menu->id}'
                         class='webpages_menu_open mr-4'
                         style='cursor:pointer;display:none;'>";
                $menu->actions = "
                    <div class='text-center' style='white-space:nowrap'>
                        {$bt1}
                        &nbsp;&nbsp;&nbsp;
                        {$bt2}
                    </div>";
                $menu->link = "<a href='{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}'
                                  target=\"_blank\">{$menu->link}</a>";
                $menu->visible = get_string("yes");

                $menus[$key] = $menu;
            }

            $table = new table();
            $table->add_header("#", "htmlid", null, "width:50px");
            $table->add_header("", "actions", null, "width:100px");
            $table->add_header(get_string("webpages_table_title", "local_kopere_dashboard"), "title");
            $table->add_header(get_string("webpages_table_link", "local_kopere_dashboard"), "link");
            $table->add_header(get_string("webpages_table_visible", "local_kopere_dashboard"), "visible");

            $table->set_row($menus);
            $table->close(true, ["ordering" => false]);

            $PAGE->requires->js_call_amd("local_kopere_dashboard/webpages", "load_pages");
        }
        echo "</div>";

        button::info(get_string("webpages_page_crash", "local_kopere_dashboard"), "{$CFG->wwwroot}/admin/tool/replace/");

        dashboard_util::end_page();
    }

    /**
     * Function menu_get_itens
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_get_itens() {
        global $DB, $CFG, $OUTPUT;

        $menuid = required_param("menuid", PARAM_INT);

        echo button::add(get_string("webpages_page_create", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "page_edit", ["menuid" => $menuid]), "", false, true);
        echo button::add(get_string("webpages_menu_create", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "menu_edit", ["menuid" => $menuid]), "ml-4", false, true);

        $detailsopen = $OUTPUT->image_url("details_open", "local_kopere_dashboard")->out(false);
        $detailsclose = $OUTPUT->image_url("details_close", "local_kopere_dashboard")->out(false);

        $itens = [];

        $menus = $DB->get_records("local_kopere_dashboard_menu", ["menuid" => $menuid], "title ASC");
        foreach ($menus as $key => $menu) {
            $menu->htmlid = "
                <img src='{$detailsopen}'
                     src-open='{$detailsopen}'
                     src-close='{$detailsclose}'
                     data-id='{$menu->id}'
                     class='webpages_menu_open mr-4'
                     style='cursor:pointer;'>";

            $bt1 = button::icon_popup_table("edit",
                url_util::makeurl("webpages", "menu_edit", ["id" => $menu->id]));
            $bt2 = button::icon_popup_table("delete",
                url_util::makeurl("webpages", "menu_delete", ["id" => $menu->id]));
            $menu->actions = "
                <div class='text-center'>
                    {$bt1}
                    &nbsp;&nbsp;&nbsp;
                    {$bt2}
                </div>";
            $menu->link = "<a href='{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}'
                              target=\"_blank\">{$menu->link}</a>";
            $menu->visible = get_string("yes");

            $itens[] = $menu;
        }

        $pages = $DB->get_records("local_kopere_dashboard_pages", ["menuid" => $menuid], "pageorder ASC");
        foreach ($pages as $key => $page) {
            $page->htmlid = "{$page->id}";

            $bt1 = button::icon("details",
                url_util::makeurl("webpages", "page_details", ["id" => $page->id]));
            $bt2 = button::icon_popup_table("delete",
                url_util::makeurl("webpages", "page_delete", ["id" => $page->id]));
            $page->actions = "
                <div class='text-center'>
                    {$bt1}
                    &nbsp;&nbsp;&nbsp;
                    {$bt2}
                </div>";

            $page->menu = $DB->get_field("local_kopere_dashboard_menu", "title", ["id" => $page->menuid]);
            $page->link = "<a href='{$CFG->wwwroot}/local/kopere_dashboard/?p={$page->link}'
                              target=\"_blank\">{$page->link}</a>";
            $page->visible = $page->visible ? get_string("yes") : get_string("no");

            $itens[] = $page;
        }

        $table = new table();
        $table->add_header("", "htmlid", null, "", "width:50px");
        $table->add_header("", "actions", null, "", "width:100px");
        $table->add_header("", "title");
        $table->add_header("", "link");

        $table->set_row($itens);
        $table->close(true, ["ordering" => false]);

        echo "<style>
                #{$table->tableid}{margin:0;}
                #{$table->tableid} thead{display:none}
              </style>";
    }

    /**
     * Function page_details
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_details() {
        global $DB, $CFG, $PAGE;

        $id = optional_param("id", 0, PARAM_INT);
        /** @var local_kopere_dashboard_pages $webpages */
        $webpages = $DB->get_record("local_kopere_dashboard_pages", ["id" => $id]);
        header::notfound_null($webpages, get_string("webpages_page_notfound", "local_kopere_dashboard"));

        dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "dashboard"));
        dashboard_util::add_breadcrumb($webpages->title);
        dashboard_util::start_page();
        echo '<div class="element-box">';

        $linkpagina = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}";

        button::info(get_string("webpages_page_view", "local_kopere_dashboard"), $linkpagina, "", false);
        button::edit(get_string("webpages_page_edit", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "page_edit",
                ["id" => $webpages->id]), "margin-left-15", false);
        button::delete(get_string("webpages_page_delete", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "page_delete",
                ["id" => $webpages->id]), "margin-left-15", false, false);

        $form = new form();
        $form->print_panel(get_string("webpages_table_link", "local_kopere_dashboard"),
            "<a target=\"_blank\" href='{$linkpagina}'>$linkpagina</a>");
        $form->print_panel(get_string("webpages_table_title", "local_kopere_dashboard"), $webpages->title);
        if ($webpages->courseid) {
            $course = $DB->get_record("course", ["id" => $webpages->courseid]);
            if ($course) {
                $url = url_util::makeurl("courses", "page_details",
                    ["courseid" => $webpages->courseid]);
                $form->print_panel(get_string("webpages_page_course", "local_kopere_dashboard"),
                    "<a href='{$url}'>{$course->fullname}</a>");
            }
        }

        $imagem = "";
        $fs = get_file_storage();
        $file = $fs->get_file(context_system::instance()->id, "local_kopere_dashboard", "webpage_image",
            $webpages->id, "/", "webpage_image.svg");
        if ($file && isset($file->get_filename()[3])) {
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(),
                $file->get_filearea(), $file->get_itemid(), "/", $file->get_filename());
            $imagem = "<p><a href='{$url}' target=\"_blank\"><img src='{$url}'
                             style='max-width:300px;max-height:300px;'></a></p>";
        }

        $href = "{$CFG->wwwroot}/local/kopere_dashboard/_editor/?page=webpages&id={$webpages->id}&link={$webpages->link}";
        $text = get_string("webpages_table_text_edit", "local_kopere_dashboard");
        $link = "<a class='btn btn-info' target=\"editor\" href='{$href}'>{$text}</a>";

        $form->print_panel(get_string("webpages_table_text", "local_kopere_dashboard"),
            $imagem . $webpages->text . $link);
        $PAGE->requires->js_call_amd("local_kopere_dashboard/webpages", "view_page");

        echo "<div class=\"row\">";
        echo "<div class='col-md'>";
        $form->print_panel(get_string("webpages_table_theme", "local_kopere_dashboard"), $this->theme_name($webpages->theme));
        echo "</div>";
        echo "<div class='col-md'>";
        $form->print_panel(get_string("webpages_table_visible", "local_kopere_dashboard"),
            $webpages->visible ? get_string("yes") : get_string("no"));
        echo "</div>";
        echo "</div>";

        echo "</div>";

        dashboard_util::end_page();

    }

    /**
     * Function page_edit
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_edit() {
        global $DB, $PAGE, $CFG;

        $id = optional_param("id", 0, PARAM_INT);

        /** @var local_kopere_dashboard_pages $webpages */
        $webpages = $DB->get_record("local_kopere_dashboard_pages", ["id" => $id]);
        if (!$webpages) {
            $webpages = local_kopere_dashboard_pages::create_by_default();
            $webpages->theme = config::get_key("webpages_theme");
            dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"),
                url_util::makeurl("webpages", "dashboard"));
            dashboard_util::add_breadcrumb(get_string("webpages_page_new", "local_kopere_dashboard"));
        } else {
            $webpages = local_kopere_dashboard_pages::create_by_object($webpages);
            dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"),
                url_util::makeurl("webpages", "dashboard"));
            dashboard_util::add_breadcrumb($webpages->title,
                url_util::makeurl("webpages", "page_details", ["id" => $webpages->id]));;
            dashboard_util::add_breadcrumb(get_string("webpages_page_edit", "local_kopere_dashboard"));
        }
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $form = new form(url_util::makeurl("webpages", "page_edit_save"));
        $form->create_hidden_input("id", $webpages->id);
        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string("webpages_page_title", "local_kopere_dashboard"))
                ->set_name("title")
                ->set_value($webpages->title)
                ->set_required()
        );
        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string("webpages_table_link", "local_kopere_dashboard"))
                ->set_name("link")
                ->set_value($webpages->link)
                ->set_required()
        );

        $courses1 = [(object)[
            "id" => 0,
            "fullname" => get_string("none_course", "local_kopere_dashboard")]];
        $courses2 = $DB->get_records_sql("SELECT id, fullname FROM {course} WHERE id > 1 ORDER BY fullname ASC");
        $courses = array_merge($courses1, $courses2);;

        echo "<div class=\"row\">";
        echo "<div class='col-md'>";
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string("contextcourse", "local_kopere_dashboard"))
                ->set_name("courseid")
                ->set_value($webpages->courseid, PARAM_INT)
                ->set_values($courses, "id", "fullname"));
        echo "</div>";
        echo "<div class='col-md'>";
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string("webpages_page_menu", "local_kopere_dashboard"))
                ->set_name("menuid")
                ->set_values(self::list_menus())
                ->set_value($webpages->menuid, PARAM_INT));
        echo "</div>";
        echo "<div class='col-md'>";
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string("webpages_table_theme", "local_kopere_dashboard"))
                ->set_name("theme")
                ->set_values(self::list_themes())
                ->set_value($webpages->theme));
        echo "</div>";
        echo "</div>";

        if (!$webpages->id) {
            $text = message::info(get_string("webpages_table_text_not", "local_kopere_dashboard"));
            $form->print_row(get_string("webpages_table_text", "local_kopere_dashboard"), $text);
        } else {
            $href = "{$CFG->wwwroot}/local/kopere_dashboard/_editor/?page=webpages&id={$webpages->id}&link={$webpages->link}";
            $text = get_string("webpages_table_text_edit", "local_kopere_dashboard");
            $link = "<a class='btn btn-info' target=\"editor\" href='{$href}'>{$text}</a>";
            $form->print_row(get_string("webpages_table_text", "local_kopere_dashboard"), $link);
        }

        $form->add_input(
            input_checkbox_select::new_instance()
                ->set_title(get_string("webpages_table_visible", "local_kopere_dashboard"))
                ->set_name("visible")
                ->set_checked($webpages->visible));

        $form->create_submit_input(get_string("webpages_page_save", "local_kopere_dashboard"));
        $form->close();

        $PAGE->requires->js_call_amd('local_kopere_dashboard/webpages', "webpages_page_ajax_get_url");

        echo "</div>";

        dashboard_util::end_page();
    }

    /**
     * Function page_edit_save
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public function page_edit_save() {
        global $DB;

        require_sesskey();

        $webpages = local_kopere_dashboard_pages::create_by_default();
        $webpages->id = optional_param("id", 0, PARAM_INT);

        if ($webpages->title == "") {
            message::schedule_message_warning(get_string("webpages_page_error", "local_kopere_dashboard"));
            $this->page_edit();
        } else {
            if ($webpages->id) {

                $exists = $DB->record_exists_select("local_kopere_dashboard_pages",
                    "link = :link AND id != :id",
                    ["link" => $webpages->link, "id" => $webpages->id]);
                if ($exists) {
                    message::schedule_message_danger(get_string("webpages_menu_link_duplicate", "local_kopere_dashboard"));
                } else {
                    try {
                        unset($webpages->text);
                        $DB->update_record("local_kopere_dashboard_pages", $webpages);
                        (\cache::make("local_kopere_dashboard", "report_getdata_cache"))->delete("local_kopere_dashboard_menu");

                        message::schedule_message_success(get_string("webpages_page_updated", "local_kopere_dashboard"));
                        header::location(
                            url_util::makeurl("webpages", "page_details", ["id" => $webpages->id]));
                    } catch (\dml_exception $e) {
                        message::print_danger($e->getMessage());
                    }
                }
            } else {
                $exists = $DB->record_exists("local_kopere_dashboard_pages", ["link" => $webpages->link]);
                if ($exists) {
                    message::schedule_message_danger(get_string("webpages_menu_link_duplicate", "local_kopere_dashboard"));
                } else {
                    try {
                        $webpages->id = $DB->insert_record("local_kopere_dashboard_pages", $webpages);
                        message::schedule_message_success(get_string("webpages_page_created", "local_kopere_dashboard"));
                        (\cache::make("local_kopere_dashboard", "report_getdata_cache"))->delete("local_kopere_dashboard_menu");

                        header::location(
                            url_util::makeurl("webpages", "page_details", ["id" => $webpages->id]));
                    } catch (\dml_exception $e) {
                        message::print_danger($e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * Function page_delete
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_delete() {
        global $DB;

        $status = optional_param("status", "", PARAM_TEXT);
        $id = optional_param("id", 0, PARAM_INT);
        /** @var local_kopere_dashboard_pages $webpages */
        $webpages = $DB->get_record("local_kopere_dashboard_pages", ["id" => $id]);
        header::notfound_null($webpages, get_string("webpages_page_notfound", "local_kopere_dashboard"));

        if ($status == "sim") {
            $DB->delete_records("local_kopere_dashboard_pages", ["id" => $id]);
            (\cache::make("local_kopere_dashboard", "report_getdata_cache"))->delete("local_kopere_dashboard_menu");

            message::schedule_message_success(get_string("webpages_page_deleted", "local_kopere_dashboard"));
            header::location(url_util::makeurl("webpages", "dashboard"));
        }

        dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "dashboard"));
        dashboard_util::add_breadcrumb($webpages->title,
            url_util::makeurl("webpages", "page_details", ["id" => $webpages->id]));;
        dashboard_util::add_breadcrumb(get_string("webpages_page_delete", "local_kopere_dashboard"));
        dashboard_util::start_page();

        echo "<p>" . get_string("webpages_page_delete_confirm", "local_kopere_dashboard", $webpages) . "</p>";
        button::delete(get_string("yes"),
            url_util::makeurl("webpages", "page_delete", ["status" => "sim", "id" => $webpages->id]), "", false);
        button::close_popup(get_string("no"));

        dashboard_util::end_page();
    }

    /**
     * Function menu_edit
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_edit() {
        global $DB, $PAGE;

        $id = optional_param("id", 0, PARAM_INT);

        $menus = $DB->get_record("local_kopere_dashboard_menu", ["id" => $id]);
        if (!$menus) {
            $menus = local_kopere_dashboard_menu::create_by_default();
            $menus->theme = get_config("local_kopere_dashboard", "webpages_theme");

            dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"),
                url_util::makeurl("webpages", "dashboard"));
            dashboard_util::add_breadcrumb(get_string("webpages_menu_new", "local_kopere_dashboard"));
        } else {
            $menus = local_kopere_dashboard_menu::create_by_object($menus);

            dashboard_util::add_breadcrumb(get_string("webpages_title", "local_kopere_dashboard"),
                url_util::makeurl("webpages", "dashboard"));
            dashboard_util::add_breadcrumb(get_string("webpages_menu_edit", "local_kopere_dashboard"));
        }
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $form = new form(url_util::makeurl("webpages", "menu_edit_save"));

        $form->create_hidden_input("id", $menus->id);
        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string("webpages_menu_title", "local_kopere_dashboard"))
                ->set_name("title")
                ->set_value($menus->title)
                ->set_required()
        );

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string("webpages_menu_link", "local_kopere_dashboard"))
                ->set_name("link")
                ->set_value($menus->link)
                ->set_required()
        );

        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string("webpages_menu_menuid", "local_kopere_dashboard"))
                ->set_name("menuid")
                ->set_values(self::list_menus(0, $menus->id))
                ->set_value($menus->menuid, PARAM_INT)
                ->set_required()
        );

        $form->add_input(
            input_checkbox_select::new_instance()
                ->set_title(get_string("webpages_menu_inheader", "local_kopere_dashboard"))
                ->set_name("inheader")
                ->set_value($menus->inheader)
        );

        $form->create_submit_input(get_string("webpages_menu_save", "local_kopere_dashboard"));
        $form->close();

        $PAGE->requires->js_call_amd("local_kopere_dashboard/webpages", "webpages_menu_ajax_get_url");

        echo "</div>";

        dashboard_util::end_page();
    }

    /**
     * Get array menus
     *
     * @param int $menuid
     * @param int $notmenuid
     * @param string $spaces
     *
     * @return array
     *
     * @throws \dml_exception
     */
    public static function list_menus($menuid = 0, $notmenuid = 0, $spaces = "") {
        global $DB;

        $menus = $DB->get_records("local_kopere_dashboard_menu", ["menuid" => $menuid]);
        if ($menuid == 0) {
            $listmenus = [
                ["key" => 0, "value" => "Root"],
            ];
        } else {
            $listmenus = [];
        }
        if ($menus) {
            /** @var local_kopere_dashboard_menu $menu */
            foreach ($menus as $menu) {
                $listmenus[] = ["key" => $menu->id, "value" => "    {$spaces}{$menu->title}"];
                if ($notmenuid != $menu->id) {
                    $listmenussub = self::list_menus($menu->id, $notmenuid, "{$spaces}    ");
                    if ($listmenussub) {
                        $listmenus = array_merge($listmenus, $listmenussub);
                    }
                }
            }
        }

        return $listmenus;
    }

    /**
     * Function menu_edit_save
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_edit_save() {
        global $DB;

        $menu = local_kopere_dashboard_menu::create_by_default();
        $menu->id = optional_param("id", 0, PARAM_INT);

        if ($menu->title == "") {
            message::schedule_message_warning(get_string("webpages_menu_error", "local_kopere_dashboard"));
        } else {
            if ($menu->id) {
                $exists = $DB->record_exists_select("local_kopere_dashboard_menu",
                    "link = :link AND id != :id",
                    ["link" => $menu->link, "id" => $menu->id]);
                if ($exists) {
                    message::schedule_message_danger(get_string("webpages_menu_link_duplicate", "local_kopere_dashboard"));
                } else {
                    message::schedule_message_success(get_string("webpages_menu_updated", "local_kopere_dashboard"));
                    $DB->update_record("local_kopere_dashboard_menu", $menu);
                    (\cache::make("local_kopere_dashboard", "report_getdata_cache"))->delete("local_kopere_dashboard_menu");
                }
            } else {
                $exists = $DB->record_exists("local_kopere_dashboard_menu", ["link" => $menu->link]);
                if ($exists) {
                    message::schedule_message_danger(get_string("webpages_menu_link_duplicate", "local_kopere_dashboard"));
                } else {
                    message::schedule_message_success(get_string("webpages_menu_created", "local_kopere_dashboard"));
                    $menu->id = $DB->insert_record("local_kopere_dashboard_menu", $menu);
                    (\cache::make("local_kopere_dashboard", "report_getdata_cache"))->delete("local_kopere_dashboard_menu");
                }
            }

            header::location(url_util::makeurl("webpages", "dashboard"));
        }
    }

    /**
     * Function menu_delete
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_delete() {
        global $DB;

        $status = optional_param("status", "", PARAM_TEXT);
        $id = optional_param("id", 0, PARAM_INT);
        /** @var local_kopere_dashboard_menu $menu */
        $menu = $DB->get_record("local_kopere_dashboard_menu", ["id" => $id]);
        header::notfound_null($menu, get_string("webpages_page_notfound", "local_kopere_dashboard"));

        dashboard_util::add_breadcrumb(get_string("webpages_menu_subtitle", "local_kopere_dashboard"),
            url_util::makeurl("webpages", "dashboard"));
        dashboard_util::add_breadcrumb($menu->title,
            url_util::makeurl("webpages", "page_details", ["id" => $menu->id]));
        dashboard_util::add_breadcrumb(get_string("webpages_menu_delete", "local_kopere_dashboard"));
        dashboard_util::start_page();

        $pages = $DB->get_records("local_kopere_dashboard_pages", ["menuid" => $menu->id]);
        if ($pages) {
            echo get_string("webpages_menu_nodelete", "local_kopere_dashboard");
        } else {
            if ($status == "sim") {
                $DB->delete_records("local_kopere_dashboard_menu", ["id" => $id]);
                (\cache::make("local_kopere_dashboard", "report_getdata_cache"))->delete("local_kopere_dashboard_menu");

                message::schedule_message_success(get_string("webpages_menu_deleted", "local_kopere_dashboard"));
                header::location(url_util::makeurl("webpages", "dashboard"));
            }

            echo get_string("webpages_page_confirmdeletemenu", "local_kopere_dashboard", $menu->title);
            button::delete(get_string("yes"),
                url_util::makeurl("webpages", "menu_delete",
                    ["status" => "sim", "id" => $menu->id]), "", false);
            button::close_popup(get_string("no"));
        }

        dashboard_util::end_page();
    }

    /**
     * Function page_ajax_get_url
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_ajax_get_url() {
        global $DB;

        $title = optional_param("title", "", PARAM_TEXT);
        $id = optional_param("id", 0, PARAM_INT);

        if ($title == "") {
            end_util::end_script_show("");
        }

        $title = html::link($title);

        $sql = "
            SELECT *
              FROM {local_kopere_dashboard_pages}
             WHERE id    !=   :id
               AND title LIKE :title";

        $webpages = $DB->get_record_sql($sql,
            [
                "id" => $id,
                "title" => $title,
            ]);
        if ($webpages) {
            end_util::end_script_show("{$title}-2");
        } else {
            end_util::end_script_show($title);
        }
    }

    /**
     * Function menu_ajax_get_url
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_ajax_get_url() {
        global $DB;

        $title = optional_param("title", "", PARAM_TEXT);
        $id = optional_param("id", 0, PARAM_INT);

        if ($title == "") {
            end_util::end_script_show();
        }

        $title = html::link($title);

        $sql = "
            SELECT *
              FROM {local_kopere_dashboard_menu}
             WHERE id    !=   :id
               AND title LIKE :title";

        $webpages = $DB->get_record_sql($sql,
            [
                "id" => $id,
                "title" => $title,
            ]);
        if ($webpages) {
            end_util::end_script_show("{$title}-2");
        } else {
            end_util::end_script_show($title);
        }
    }

    /**
     * Function theme_name
     *
     * @param $themekey
     *
     * @return string
     */
    private function theme_name($themekey) {
        $themes = self::list_themes();

        foreach ($themes as $theme) {
            if ($theme["key"] == $themekey) {
                return $theme["value"];
            }
        }

        return "-";
    }

    /**
     * Function list_themes
     *
     * @return array
     */
    public static function list_themes() {
        $layouts = [
            [
                "key" => "base",
                "value" => "theme_base",
            ], [
                "key" => "standard",
                "value" => "theme_standard",
            ], [
                "key" => "popup",
                "value" => "theme_popup",
            ], [
                "key" => "frametop",
                "value" => "theme_frametop",
            ], [
                "key" => "print",
                "value" => "theme_print",
            ], [
                "key" => "report",
                "value" => "theme_report",
            ],
        ];

        return $layouts;
    }

    /**
     * Function settings
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function settings() {
        $redirect = urlencode("classname=webpages&method=dashboard");
        dashboard_util::add_breadcrumb(get_string("webpages_page_settigs", "local_kopere_dashboard"));
        dashboard_util::start_page();

        $form = new form(url_util::makeurl("settings", "save", ["redirect" => $redirect]));

        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string("webpages_page_theme", "local_kopere_dashboard"))
                ->set_values(self::list_themes())
                ->set_value_by_config("webpages_theme"));

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string("webpages_page_analytics", "local_kopere_dashboard"))
                ->set_value_by_config("webpages_analytics_id")
                ->set_description(get_string("webpages_page_analyticsdesc", "local_kopere_dashboard")));

        $form->create_submit_input(get_string("savechanges"));
        $form->close();

        dashboard_util::end_page();
    }
}
