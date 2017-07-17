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

use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputCheckbox;
use local_kopere_dashboard\html\inputs\InputHtmlEditor;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\html\inputs\InputText;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Html;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\util\ServerUtil;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\vo\kopere_dashboard_menu;
use local_kopere_dashboard\vo\kopere_dashboard_webpages;

class WebPages {
    public function dashboard() {
        global $DB, $CFG;

        DashboardUtil::startPage(get_string_kopere('webpages_title'), null, 'WebPages::settings', 'Páginas-estáticas');

        echo '<div class="element-box">';
        TitleUtil::printH3('webpages_subtitle');

        $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');

        Button::add(get_string_kopere('webpages_menu_create'), 'WebPages::editMenu', '', true, false, true);

        if (!$menus) {
            Button::help('webpages', get_string_kopere('webpages_menu_help'), 'Páginas-estáticas');
        } else {
            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
            $table->addHeader(get_string_kopere('webpages_table_link'), 'link');
            $table->addHeader(get_string_kopere('webpages_table_title'), 'title');

            $table->setClickModal('open-ajax-table.php?WebPages::editMenu&id={id}', 'id');
            $table->printHeader('', false);
            $table->setRow($menus);
            $table->close(false);
        }
        echo '</div>';

        if ($menus) {
            echo '<div class="element-box">';
            TitleUtil::printH3('webpages_title');
            Button::add(get_string_kopere('webpages_page_create'), 'WebPages::editPage');

            $pages = $DB->get_records('kopere_dashboard_webpages', null, 'pageorder ASC');

            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
            $table->addHeader(get_string_kopere('webpages_table_link'), 'link');
            $table->addHeader(get_string_kopere('webpages_table_link'), 'title');
            $table->addHeader(get_string_kopere('webpages_table_visible'), 'visible', TableHeaderItem::RENDERER_VISIBLE);
            $table->addHeader(get_string_kopere('webpages_table_order'), 'pageorder', TableHeaderItem::TYPE_INT);

            $table->setClickRedirect('WebPages::details&id={id}', 'id');
            $table->printHeader('', false);
            $table->setRow($pages);
            $table->close(false);

            echo '</div>';
        }

        Button::info(get_string_kopere('webpages_page_crash'), $CFG->wwwroot . '/admin/tool/replace/');

        DashboardUtil::endPage();
    }

    public function details() {
        global $DB, $CFG;

        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        Header::notfoundNull($webpages, get_string_kopere('webpages_page_notfound'));

        DashboardUtil::startPage(array(
            array('WebPages::dashboard', get_string_kopere('webpages_title')),
            $webpages->title
        ));
        echo '<div class="element-box">';

        $linkPagina = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}";

        Button::info(get_string_kopere('webpages_page_view'), $linkPagina, '', false);
        Button::edit(get_string_kopere('webpages_page_edit'), 'WebPages::editPage&id=' . $webpages->id, 'margin-left-15', false);
        Button::delete(get_string_kopere('webpages_page_delete'), 'WebPages::deletePage&id=' . $webpages->id, 'margin-left-15', false, false, true);

        $form = new Form();
        $form->printPanel(get_string_kopere('webpages_table_link'), "<a target='_blank' href='$linkPagina'>$linkPagina</a>");
        $form->printPanel(get_string_kopere('webpages_table_title'), $webpages->title);
        $form->printPanel(get_string_kopere('webpages_table_link'), $webpages->link);
        if ($webpages->courseid) {
            $course = $DB->get_record('course', array('id' => $webpages->courseid));
            if ($course) {
                $form->printPanel(get_string_kopere('webpages_page_course'), '<a href="?Courses::details&courseid=' . $webpages->courseid . '">' . $course->fullname . '</a>');
            }
        }
        $form->printPanel(get_string_kopere('webpages_table_theme'), $this->themeName($webpages->theme));
        $form->printPanel(get_string_kopere('webpages_table_text'), $webpages->text);
        $form->printPanel(get_string_kopere('webpages_table_visible'), $webpages->visible ? get_string('yes') : get_string('no'));

        echo '</div>';
        DashboardUtil::endPage();

    }

    public function editPage() {
        global $DB;

        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        if (!$webpages) {
            $webpages = kopere_dashboard_webpages::createNew();
            $webpages->theme = get_config('local_kopere_dashboard', 'webpages_theme');
            DashboardUtil::startPage(array(
                array('WebPages::dashboard', get_string_kopere('webpages_title')),
                get_string_kopere('webpages_page_new')
            ));
        } else {
            $webpages = kopere_dashboard_webpages::createBlank($webpages);
            DashboardUtil::startPage(array(
                array('WebPages::dashboard', get_string_kopere('webpages_title')),
                array('WebPages::details&id=' . $webpages->id, $webpages->title),
                get_string_kopere('webpages_page_edit')
            ));
        }

        echo '<div class="element-box">';

        $form = new Form('WebPages::editPageSave');
        $form->createHiddenInput('id', $webpages->id);
        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('webpages_page_title'))
                ->setName('title')
                ->setValue($webpages->title)
                ->setRequired()
        );
        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('webpages_page_link'))
                ->setName('link')
                ->setValue($webpages->link)
                ->setRequired()
        );
        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('webpages_page_menu'))
                ->setName('menuid')
                ->setValues(self::listMenus())
                ->setValue($webpages->menuid));
        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('webpages_table_theme'))
                ->setName('theme')
                ->setValues($this->listThemes())
                ->setValue($webpages->theme));

        $form->addInput(
            InputHtmlEditor::newInstance()->setTitle(get_string_kopere('webpages_table_text'))
                ->setName('text')
                ->setValue($webpages->text)
                ->setRequired()
        );

        $form->addInput(
            InputCheckbox::newInstance()->setTitle(get_string_kopere('webpages_table_visible'))
                ->setName('visible')
                ->setChecked($webpages->visible));

        $form->createSubmitInput(get_string_kopere('webpages_page_save'));
        $form->close();

        ?>
        <script>
            $('#title').focusout(function () {
                var url = 'open-ajax-table.php?WebPages::ajaxGetPageUrl';
                var postData = {
                    title: $(this).val(),
                    id: $('#id').val()
                };
                $.post(url, postData, function (data) {
                    $('#link').val(data);
                    $('#theme').focus();
                }, 'text');
            });
        </script>
        <?php
        echo '</div>';
        DashboardUtil::endPage();
    }

    public function editPageSave() {
        global $DB;

        $webpages = kopere_dashboard_webpages::createNew();
        $webpages->id = optional_param('id', 0, PARAM_INT);

        if ($webpages->title == '' || $webpages->text == '') {
            Mensagem::agendaMensagemWarning(get_string_kopere('webpages_page_error'));
            $this->editPage();
        } else {
            if ($webpages->id) {
                Mensagem::agendaMensagemSuccess(get_string_kopere('webpages_page_updated'));
                $DB->update_record('kopere_dashboard_webpages', $webpages);
            } else {
                Mensagem::agendaMensagemSuccess(get_string_kopere('webpages_page_created'));
                $webpages->id = $DB->insert_record('kopere_dashboard_webpages', $webpages);
            }

            self::deleteCache();
            Header::location('WebPages::details&id=' . $webpages->id);
        }
    }

    public function deletePage() {
        global $DB, $CFG;

        $status = optional_param('status', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        Header::notfoundNull($webpages, get_string_kopere('webpages_page_notfound'));

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_webpages', array('id' => $id));

            self::deleteCache();
            Mensagem::agendaMensagemSuccess(get_string_kopere('webpages_page_deleted'));
            Header::location('WebPages::dashboard');
        }

        DashboardUtil::startPage(array(
            array('WebPages::dashboard', get_string_kopere('webpages_title')),
            array('WebPages::details&id=' . $webpages->id, $webpages->title),
            get_string_kopere('webpages_page_delete')
        ));

        TitleUtil::printH3('Excluíndo Página');
        echo "<p>".get_string_kopere('webpages_page_delete_confirm', $webpages)."</p>";
        Button::delete(get_string('yes'), 'WebPages::deletePage&status=sim&id=' . $webpages->id, '', false);
        Button::add(get_string('no'), 'WebPages::details&id=' . $webpages->id, 'margin-left-10', false);

        DashboardUtil::endPage();
    }

    public function editMenu() {
        global $DB;

        $id = optional_param('id', 0, PARAM_INT);

        /** @var kopere_dashboard_menu $webpages */
        $menus = $DB->get_record('kopere_dashboard_menu', array('id' => $id));
        if (!$menus) {
            $menus = kopere_dashboard_menu::createNew();
            $menus->theme = get_config('kopere_dashboard_menu', 'webpages_theme');
            if (!AJAX_SCRIPT) {
                DashboardUtil::startPage(array(
                    array('WebPages::dashboard', get_string_kopere('webpages_title')),
                    get_string_kopere('webpages_menu_new')
                ));
            } else {
                DashboardUtil::startPopup(get_string_kopere('webpages_menu_new'), 'WebPages::editMenuSave');
            }
        } else {
            $menus = kopere_dashboard_menu::createBlank($menus);

            if (!AJAX_SCRIPT) {
                DashboardUtil::startPage(array(
                    array('WebPages::dashboard', get_string_kopere('webpages_title')),
                    get_string_kopere('webpages_menu_edit')
                ));
            } else {
                DashboardUtil::startPopup(get_string_kopere('webpages_menu_edit'), 'WebPages::editMenuSave');
            }
        }

        echo '<div class="element-box">';

        if (!AJAX_SCRIPT) {
            $form = new Form('WebPages::editMenuSave');
        } else {
            $form = new Form();
        }
        $form->createHiddenInput('id', $menus->id);
        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('webpages_menu_title'))
                ->setName('title')
                ->setValue($menus->title)
                ->setRequired()
        );

        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('webpages_menu_link'))
                ->setName('link')
                ->setValue($menus->link)
                ->setRequired()
        );
        if (!AJAX_SCRIPT) {
            $form->createSubmitInput(get_string_kopere('webpages_menu_save'));
        }
        $form->close();

        echo '</div>';

        ?>
        <script>
            $('#title').focusout(function () {
                var url = 'open-ajax-table.php?WebPages::ajaxGetMenuUrl';
                var postData = {
                    title: $(this).val(),
                    id: $('#id').val()
                };
                $.post(url, postData, function (data) {
                    $('#link').val(data).focus();
                }, 'text');
            });
        </script>
        <?php
        echo '</div>';

        if (!AJAX_SCRIPT) {
            DashboardUtil::endPage();
        } else {
            if ($id) {
                DashboardUtil::endPopup('WebPages::deleteMenu&id=' . $id);
            } else {
                DashboardUtil::endPopup();
            }
        }
    }

    public function editMenuSave() {
        global $DB;

        $menu = kopere_dashboard_menu::createNew();
        $menu->id = optional_param('id', 0, PARAM_INT);

        if ($menu->title == '') {
            Mensagem::agendaMensagemWarning(get_string_kopere('webpages_menu_error'));
        } else {
            if ($menu->id) {
                Mensagem::agendaMensagemSuccess(get_string_kopere('webpages_menu_updated'));
                $DB->update_record('kopere_dashboard_menu', $menu);
            } else {
                Mensagem::agendaMensagemSuccess(get_string_kopere('webpages_menu_created'));
                $menu->id = $DB->insert_record('kopere_dashboard_menu', $menu);
            }

            self::deleteCache();
            Header::location('WebPages::dashboard');
        }
    }

    public function deleteMenu() {
        global $DB;

        $status = optional_param('status', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_menu $menu */
        $menu = $DB->get_record('kopere_dashboard_menu', array('id' => $id));
        Header::notfoundNull($menu, get_string_kopere('webpages_page_notfound'));

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_menu', array('id' => $id));

            self::deleteCache();
            Mensagem::agendaMensagemSuccess(get_string_kopere('webpages_menu_deleted'));
            Header::location('WebPages::dashboard');
        }

        DashboardUtil::startPage(array(
            array('WebPages::dashboard', get_string_kopere('webpages_menu_subtitle')),
            array('WebPages::details&id=' . $menu->id, $menu->title),
            get_string_kopere('webpages_menu_delete')
        ));

        echo "<p>Deseja realmente excluir o menu <strong>{$menu->title}</strong>?</p>";
        Button::delete(get_string('yes'), 'WebPages::deleteMenu&status=sim&id=' . $menu->id, '', false);
        Button::add(get_string('no'), 'WebPages::dashboard', 'margin-left-10', false);

        DashboardUtil::endPage();
    }

    public function ajaxGetPageUrl() {
        global $DB;

        $title = optional_param('title', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);

        if ($title == '') {
            die('');
        }

        $title = Html::link($title);

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
            die($title . '-2');
        } else {
            die($title);
        }
    }

    public function ajaxGetMenuUrl() {
        global $DB;

        $title = optional_param('title', '', PARAM_TEXT);
        $id = optional_param('id', 0, PARAM_INT);

        if ($title == '') {
            die('');
        }

        $title = Html::link($title);

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
            die($title . '-2');
        } else {
            die($title);
        }
    }

    private function themeName($theme) {
        $themes = $this->listThemes();

        foreach ($themes as $t) {
            return $t['value'];
        }

        return '-';
    }

    public static function listMenus() {
        global $DB;

        $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');

        $returnMenus = array();
        /** @var kopere_dashboard_menu $menu */
        foreach ($menus as $menu) {
            $returnMenus[] = array('key' => $menu->id, 'value' => $menu->title);
        }

        return $returnMenus;
    }

    private function listThemes()
    {
        $layouts = array(
            array(
                'key' => 'base',
                'value' => 'webpages_theme_base'
            ),
            array(
                'key' => 'standard',
                'value' => 'webpages_theme_standard'
            ),
            array(
                'key' => 'frontpage',
                'value' => 'webpages_theme_frontpage'
            ),
            array(
                'key' => 'popup',
                'value' => 'webpages_theme_popup'
            ),
            array(
                'key' => 'frametop',
                'value' => 'webpages_theme_frametop'
            ),
            array(
                'key' => 'print',
                'value' => 'webpages_theme_print'
            ),
            array(
                'key' => 'report',
                'value' => 'webpages_theme_report'
            )
        );

        return $layouts;
    }

    public function settings() {
        ob_clean();
        DashboardUtil::startPopup(get_string_kopere('webpages_page_settigs'), 'Settings::settingsSave');

        $form = new Form();

        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('webpages_page_theme'))
                ->setValues($this->listThemes())
                ->setValueByConfig('webpages_theme'));

        $form->addInput(
            InputText::newInstance()->setTitle(get_string_kopere('webpages_page_analytics'))
                ->setValueByConfig('webpages_analytics_id')
                ->setDescription(get_string_kopere('webpages_page_analyticsdesc') ));

        $form->close();

        DashboardUtil::endPopup();
    }

    public static function getCacheDir() {
        $path = ServerUtil::getKoperePath(true) . 'cache';

        @mkdir($path);

        return $path . '/';
    }

    private static function deleteCache() {
        $caches = glob(self::getCacheDir() . '*');
        foreach ($caches as $cache) {
            unlink($cache);
        }
    }
}