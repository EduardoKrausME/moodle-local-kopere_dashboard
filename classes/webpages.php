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
 * @created    13/05/17 13:28
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_checkbox;
use local_kopere_dashboard\html\inputs\input_html_editor;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\inputs\input_text;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\config;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\end_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\html;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\server_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\vo\kopere_dashboard_menu;
use local_kopere_dashboard\vo\kopere_dashboard_webpages;

/**
 * Class webpages
 * @package local_kopere_dashboard
 */
class webpages {

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $DB, $CFG;

        dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'));
        dashboard_util::start_page('?classname=webpages&method=settings', 'Páginas-estáticas');


        title_util::print_h3('webpages_subtitle');
        title_util::print_h6('webpages_subtitle_help');
        $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');
        button::add(get_string_kopere('webpages_menu_create'), '?classname=webpages&method=menu_edit', '', true, false, true);

        echo '<div class="element-box">';

        if (!$menus) {
            button::help('webpages', get_string_kopere('webpages_menu_help'), 'Páginas-estáticas');
        } else {
            foreach ($menus as $key => $menu) {
                $menu->actions
                    = "<div class=\"text-center\">
                    " . button::icon_popup_table('edit', "?classname=webpages&method=menu_edit&id={$menu->id}") . "&nbsp;&nbsp;&nbsp;
                    " . button::icon_popup_table('delete', "?classname=webpages&method=menu_delete&id={$menu->id}") . "
                   </div>";

                $menus[$key] = $menu;
            }

            $table = new data_table();
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width: 20px');
            $table->add_header(get_string_kopere('webpages_table_link'), 'link');
            $table->add_header(get_string_kopere('webpages_table_title'), 'title');
            $table->add_header('', 'actions', table_header_item::TYPE_ACTION);

            $table->print_header('', false);
            $table->set_row($menus);
            $table->close(false);
        }
        echo '</div>';

        if ($menus) {
            title_util::print_h3('webpages_title');
            button::add(get_string_kopere('webpages_page_create'), '?classname=webpages&method=page_edit');

            echo '<div class="element-box">';

            $pages = $DB->get_records('kopere_dashboard_webpages', null, 'pageorder ASC');
            foreach ($pages as $key => $page) {
                $page->actions
                    = "<div class=\"text-center\">
                    " . button::icon('details', "?classname=webpages&method=page_details&id={$page->id}", false) . "&nbsp;&nbsp;&nbsp;
                    " . button::icon_popup_table('delete', "?classname=webpages&method=page_delete&id={$page->id}") . "
                   </div>";

                $page->menu = $DB->get_field('kopere_dashboard_menu', 'title', ['id' => $page->menuid]);

                $pages[$key] = $page;
            }

            $table = new data_table();
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width: 20px');
            $table->add_header(get_string_kopere('webpages_table_link'), 'link');
            $table->add_header(get_string_kopere('webpages_table_link'), 'title');
            $table->add_header(get_string_kopere('webpages_table_menutitle'), 'menu');
            $table->add_header(get_string_kopere('webpages_table_visible'), 'visible', table_header_item::TYPE_INT);
            $table->add_header(get_string_kopere('webpages_table_order'), 'pageorder', table_header_item::TYPE_INT);
            $table->add_header('', 'actions', table_header_item::TYPE_ACTION);

            $table->print_header('', false);
            $table->set_row($pages);
            $table->close(false);

            echo '</div>';
        }

        button::info(get_string_kopere('webpages_page_crash'), $CFG->wwwroot . '/admin/tool/replace/');

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_details() {
        global $DB, $CFG;

        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        header::notfound_null($webpages, get_string_kopere('webpages_page_notfound'));

        dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'), '?classname=webpages&method=dashboard');
        dashboard_util::add_breadcrumb($webpages->title);
        dashboard_util::start_page();
        echo '<div class="element-box">';

        $linkpagina = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}";

        button::info(get_string_kopere('webpages_page_view'), $linkpagina, '', false);
        button::edit(get_string_kopere('webpages_page_edit'), '?classname=webpages&method=page_edit&id=' . $webpages->id, 'margin-left-15', false);
        button::delete(get_string_kopere('webpages_page_delete'),
            '?classname=webpages&method=page_delete&id=' . $webpages->id, 'margin-left-15', false, false, true);

        $form = new form();
        $form->print_panel(get_string_kopere('webpages_table_link'), "<a target='_blank' href='$linkpagina'>$linkpagina</a>");
        $form->print_panel(get_string_kopere('webpages_table_title'), $webpages->title);
        $form->print_panel(get_string_kopere('webpages_table_link'), $webpages->link);
        if ($webpages->courseid) {
            $course = $DB->get_record('course', array('id' => $webpages->courseid));
            if ($course) {
                $form->print_panel(get_string_kopere('webpages_page_course'),
                    '<a href="?classname=courses&method=page_details&courseid=' . $webpages->courseid . '">' . $course->fullname . '</a>');
            }
        }
        $form->print_panel(get_string_kopere('webpages_table_theme'), $this->theme_name($webpages->theme));
        $form->print_panel(get_string_kopere('webpages_table_text'), $webpages->text);
        $form->print_panel(get_string_kopere('webpages_table_visible'), $webpages->visible ? get_string('yes') : get_string('no'));

        echo '</div>';
        dashboard_util::end_page();

    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_edit() {
        global $DB, $PAGE;

        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        if (!$webpages) {
            $webpages = kopere_dashboard_webpages::create_by_default();
            $webpages->theme = config::get_key('webpages_theme');
            dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'), '?classname=webpages&method=dashboard');
            dashboard_util::add_breadcrumb(get_string_kopere('webpages_page_new'));
        } else {
            $webpages = kopere_dashboard_webpages::create_by_object($webpages);
            dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'), '?classname=webpages&method=dashboard');
            dashboard_util::add_breadcrumb($webpages->title, '?classname=webpages&method=page_details&id=' . $webpages->id);;
            dashboard_util::add_breadcrumb(get_string_kopere('webpages_page_edit'));
        }
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $form = new form('?classname=webpages&method=page_edit_save');
        $form->create_hidden_input('id', $webpages->id);
        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('webpages_page_title'))
                ->set_name('title')
                ->set_value($webpages->title)
                ->set_required()
        );
        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('webpages_table_link'))
                ->set_name('link')
                ->set_value($webpages->link)
                ->set_required()
        );

        $courses1 = [(object)[
            'id' => 0,
            'fullname' => "(Nenhum curso)"]];
        $courses2 = $DB->get_records_sql('SELECT id, fullname FROM {course} WHERE id > 1 ORDER BY fullname ASC');
        $courses = array_merge($courses1, $courses2);;

        $form->add_input(
            input_select::new_instance()
                ->set_title('Curso')
                ->set_name('courseid')
                ->set_value($webpages->courseid)
                ->set_values($courses, 'id', 'fullname'));

        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('webpages_page_menu'))
                ->set_name('menuid')
                ->set_values(self::list_menus())
                ->set_value($webpages->menuid));
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('webpages_table_theme'))
                ->set_name('theme')
                ->set_values(self::list_themes())
                ->set_value($webpages->theme));

        $form->add_input(
            input_html_editor::new_instance()
                ->set_title(get_string_kopere('webpages_table_text'))
                ->set_name('text')
                ->set_value($webpages->text)
                ->set_required()
        );

        $form->add_input(
            input_checkbox::new_instance()
                ->set_title(get_string_kopere('webpages_table_visible'))
                ->set_name('visible')
                ->set_checked($webpages->visible));

        $form->create_submit_input(get_string_kopere('webpages_page_save'));
        $form->close();

        $PAGE->requires->js_call_amd('local_kopere_dashboard/form_exec', 'webpages_page_ajax_get_url');

        echo '</div>';
        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_edit_save() {
        global $DB;

        $webpages = kopere_dashboard_webpages::create_by_default();
        $webpages->id = optional_param('id', 0, PARAM_INT);

        if ($webpages->title == '' || $webpages->text == '') {
            mensagem::agenda_mensagem_warning(get_string_kopere('webpages_page_error'));
            $this->page_edit();
        } else {
            if ($webpages->id) {

                $exists = $DB->record_exists_select('kopere_dashboard_webpages',
                    'link = :link AND id != :id',
                    ['menu' => $webpages->link, 'id' => $webpages->id]);
                if ($exists) {
                    mensagem::agenda_mensagem_danger(get_string_kopere('webpages_menu_link_duplicate'));
                } else {
                    try {
                        $DB->update_record('kopere_dashboard_webpages', $webpages);
                        self::cache_delete();
                        mensagem::agenda_mensagem_success(get_string_kopere('webpages_page_updated'));
                        header::location('?classname=webpages&method=page_details&id=' . $webpages->id);
                    } catch (\dml_exception $e) {
                        mensagem::print_danger($e->getMessage());
                    }
                }
            } else {
                $exists = $DB->record_exists('kopere_dashboard_webpages', ['menu' => $webpages->link]);
                if ($exists) {
                    mensagem::agenda_mensagem_danger(get_string_kopere('webpages_menu_link_duplicate'));
                } else {
                    try {
                        $webpages->id = $DB->insert_record('kopere_dashboard_webpages', $webpages);
                        mensagem::agenda_mensagem_success(get_string_kopere('webpages_page_created'));

                        self::cache_delete();
                        header::location('?classname=webpages&method=page_details&id=' . $webpages->id);
                    } catch (\dml_exception $e) {
                        mensagem::print_danger($e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_delete() {
        global $DB;

        $status = optional_param('status', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        header::notfound_null($webpages, get_string_kopere('webpages_page_notfound'));

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_webpages', array('id' => $id));

            self::cache_delete();
            mensagem::agenda_mensagem_success(get_string_kopere('webpages_page_deleted'));
            header::location('?classname=webpages&method=dashboard');
        }

        dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'), '?classname=webpages&method=dashboard');
        dashboard_util::add_breadcrumb($webpages->title, '?classname=webpages&method=page_details&id=' . $webpages->id);;
        dashboard_util::add_breadcrumb(get_string_kopere('webpages_page_delete'));
        dashboard_util::start_page();

        echo "<p>" . get_string_kopere('webpages_page_delete_confirm', $webpages) . "</p>";
        button::delete(get_string('yes'), '?classname=webpages&method=page_delete&status=sim&id=' . $webpages->id, '', false);
        button::close_popup(get_string('no'));

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_edit() {
        global $DB, $PAGE;

        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_menu $webpages */
        $menus = $DB->get_record('kopere_dashboard_menu', array('id' => $id));
        if (!$menus) {
            $menus = kopere_dashboard_menu::create_by_default();
            $menus->theme = get_config('kopere_dashboard_menu', 'webpages_theme');

            dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'), '?classname=webpages&method=dashboard');
            dashboard_util::add_breadcrumb(get_string_kopere('webpages_menu_new'));
        } else {
            $menus = kopere_dashboard_menu::create_by_object($menus);

            dashboard_util::add_breadcrumb(get_string_kopere('webpages_title'), '?classname=webpages&method=dashboard');
            dashboard_util::add_breadcrumb(get_string_kopere('webpages_menu_edit'));
        }
        dashboard_util::start_page();

        echo '<div class="element-box">';


        $form = new form('?classname=webpages&method=menu_edit_save');

        $form->create_hidden_input('id', $menus->id);
        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('webpages_menu_title'))
                ->set_name('title')
                ->set_value($menus->title)
                ->set_required()
        );

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('webpages_menu_link'))
                ->set_name('link')
                ->set_value($menus->link)
                ->set_required()
        );

        $form->create_submit_input(get_string_kopere('webpages_menu_save'));
        $form->close();

        $PAGE->requires->js_call_amd('local_kopere_dashboard/form_exec', 'webpages_menu_ajax_get_url');

        echo '</div>';

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_edit_save() {
        global $DB;

        $menu = kopere_dashboard_menu::create_by_default();
        $menu->id = optional_param('id', 0, PARAM_INT);

        if ($menu->title == '') {
            mensagem::agenda_mensagem_warning(get_string_kopere('webpages_menu_error'));
        } else {
            if ($menu->id) {
                $exists = $DB->record_exists_select('kopere_dashboard_menu',
                    'link = :link AND id != :id',
                    ['menu' => $menu->link, 'id' => $menu->id]);
                if ($exists) {
                    mensagem::agenda_mensagem_danger(get_string_kopere('webpages_menu_link_duplicate'));
                } else {
                    mensagem::agenda_mensagem_success(get_string_kopere('webpages_menu_updated'));
                    $DB->update_record('kopere_dashboard_menu', $menu);
                }
            } else {
                $exists = $DB->record_exists('kopere_dashboard_menu', ['link' => $menu->link]);
                if ($exists) {
                    mensagem::agenda_mensagem_danger(get_string_kopere('webpages_menu_link_duplicate'));
                } else {
                    mensagem::agenda_mensagem_success(get_string_kopere('webpages_menu_created'));
                    $menu->id = $DB->insert_record('kopere_dashboard_menu', $menu);
                }
            }

            self::cache_delete();
            header::location('?classname=webpages&method=dashboard');
        }
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_delete() {
        global $DB;

        $status = optional_param('status', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_menu $menu */
        $menu = $DB->get_record('kopere_dashboard_menu', array('id' => $id));
        header::notfound_null($menu, get_string_kopere('webpages_page_notfound'));

        dashboard_util::add_breadcrumb(get_string_kopere('webpages_menu_subtitle'), '?classname=webpages&method=dashboard');
        dashboard_util::add_breadcrumb($menu->title, '?classname=webpages&method=page_details&id=' . $menu->id);;
        dashboard_util::add_breadcrumb(get_string_kopere('webpages_menu_delete'));
        dashboard_util::start_page();

        $pages = $DB->get_records('kopere_dashboard_webpages', ['menuid' => $menu->id]);
        if ($pages) {
            echo get_string_kopere('webpages_menu_nodelete');
        } else {
            if ($status == 'sim') {
                $DB->delete_records('kopere_dashboard_menu', array('id' => $id));

                self::cache_delete();
                mensagem::agenda_mensagem_success(get_string_kopere('webpages_menu_deleted'));
                header::location('?classname=webpages&method=dashboard');
            }

            echo get_string_kopere('webpages_page_confirmdeletemenu', $menu->title);
            button::delete(get_string('yes'), '?classname=webpages&method=menu_delete&status=sim&id=' . $menu->id, '', false);
            button::close_popup(get_string('no'));
        }

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function page_ajax_get_url() {
        global $DB;

        $title = optional_param('title', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);

        if ($title == '') {
            end_util::end_script_show('');
        }

        $title = html::link($title);

        $sql
            = "SELECT *
                 FROM {kopere_dashboard_webpages}
                WHERE id    !=   :id
                  AND title LIKE :title";

        $webpages = $DB->get_record_sql($sql,
            array(
                'id' => $id,
                'title' => $title,
            ));
        if ($webpages) {
            end_util::end_script_show($title . '-2');
        } else {
            end_util::end_script_show($title);
        }
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function menu_ajax_get_url() {
        global $DB;

        $title = optional_param('title', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);

        if ($title == '') {
            end_util::end_script_show();
        }

        $title = html::link($title);

        $sql
            = "SELECT *
                 FROM {kopere_dashboard_menu}
                WHERE id    !=   :id
                  AND title LIKE :title";

        $webpages = $DB->get_record_sql($sql,
            array(
                'id' => $id,
                'title' => $title,
            ));
        if ($webpages) {
            end_util::end_script_show($title . '-2');
        } else {
            end_util::end_script_show($title);
        }
    }

    /**
     * @param $themekey
     * @return string
     */
    private function theme_name($themekey) {
        $themes = self::list_themes();

        foreach ($themes as $theme) {
            if ($theme['key'] == $themekey) {
                return $theme['value'];
            }
        }

        return '-';
    }

    /**
     * @return array
     * @throws \dml_exception
     */
    public static function list_menus() {
        global $DB;

        $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');

        $return = array();
        /** @var kopere_dashboard_menu $menu */
        foreach ($menus as $menu) {
            $return[] = array('key' => $menu->id, 'value' => $menu->title);
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function list_themes() {
        $layouts = array(
            array(
                'key' => 'base',
                'value' => 'theme_base'
            ),
            array(
                'key' => 'standard',
                'value' => 'theme_standard'
            ),
            array(
                'key' => 'popup',
                'value' => 'theme_popup'
            ),
            array(
                'key' => 'frametop',
                'value' => 'theme_frametop'
            ),
            array(
                'key' => 'print',
                'value' => 'theme_print'
            ),
            array(
                'key' => 'report',
                'value' => 'theme_report'
            )
        );

        return $layouts;
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function settings() {
        ob_clean();
        $redirect = urlencode("classname=webpages&method=dashboard");
        dashboard_util::add_breadcrumb(get_string_kopere('webpages_page_settigs'));
        dashboard_util::start_page();

        $form = new form("?classname=settings&method=save&redirect={$redirect}");

        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('webpages_page_theme'))
                ->set_values(self::list_themes())
                ->set_value_by_config('webpages_theme'));

        $form->add_input(
            input_text::new_instance()
                ->set_title(get_string_kopere('webpages_page_analytics'))
                ->set_value_by_config('webpages_analytics_id')
                ->set_description(get_string_kopere('webpages_page_analyticsdesc')));

        $form->create_submit_input(get_string('savechanges'));
        $form->close();

        dashboard_util::end_page();
    }

    /**
     * @return string
     */
    public static function cache_get_dir() {
        $path = server_util::get_kopere_pathath(true) . 'cache';

        @mkdir($path);

        return $path . '/';
    }

    /**
     *
     */
    private static function cache_delete() {
        $caches = glob(self::cache_get_dir() . '*');
        foreach ($caches as $cache) {
            unlink($cache);
        }
    }
}