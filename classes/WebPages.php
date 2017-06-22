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

use local_kopere_dashboard\html\Botao;
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
use local_kopere_dashboard\vo\kopere_dashboard_menu;
use local_kopere_dashboard\vo\kopere_dashboard_webpages;

class WebPages {
    public function dashboard() {
        global $DB, $CFG;

        DashboardUtil::startPage('Páginas estáticas', null, 'WebPages::settings', 'Páginas-estáticas');

        echo '<div class="element-box">';
        echo '<h3>Menus de navegação</h3>';

        $menus = $DB->get_records('kopere_dashboard_menu', null, 'title ASC');

        Botao::add('Criar novo Menu', 'WebPages::editMenu', '', true, false, true);

        if (!$menus) {
            Botao::help('webpages', 'Ajuda com Menus', 'Páginas-estáticas');
        } else {
            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
            $table->addHeader('Link', 'link');
            $table->addHeader('Título', 'title');

            $table->setClickModal('open-ajax-table.php?WebPages::editMenu&id={id}', 'id');
            $table->printHeader('', false);
            $table->setRow($menus);
            $table->close(false);
        }
        echo '</div>';

        if ($menus) {
            echo '<div class="element-box">';
            echo '<h3>Páginas</h3>';
            Botao::add('Criar nova página', 'WebPages::editPage');

            $pages = $DB->get_records('kopere_dashboard_webpages', null, 'pageorder ASC');

            $table = new DataTable();
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px');
            $table->addHeader('Link', 'link');
            $table->addHeader('Título', 'title');
            $table->addHeader('Visível', 'visible', TableHeaderItem::RENDERER_VISIBLE);
            $table->addHeader('Ordem', 'pageorder', TableHeaderItem::TYPE_INT);

            $table->setClickRedirect('WebPages::details&id={id}', 'id');
            $table->printHeader('', false);
            $table->setRow($pages);
            $table->close(false);

            echo '</div>';
        }

        Botao::info('Se alterar a URL e as imagens derem CRASH, clique aqui', $CFG->wwwroot . '/admin/tool/replace/');

        DashboardUtil::endPage();
    }

    public function details() {
        global $DB, $CFG;

        $id = optional_param('id', 0, PARAM_INT);
        /** @var kopere_dashboard_webpages $webpages */
        $webpages = $DB->get_record('kopere_dashboard_webpages', array('id' => $id));
        Header::notfoundNull($webpages, 'Página não localizada!');

        DashboardUtil::startPage(array(
            array('WebPages::dashboard', 'Páginas estáticas'),
            $webpages->title
        ));
        echo '<div class="element-box">';

        $linkPagina = "{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}";

        Botao::info('Visualizar a página', $linkPagina, '', false);
        Botao::edit('Editar página', 'WebPages::editPage&id=' . $webpages->id, 'margin-left-15', false);
        Botao::delete('Excluir página', 'WebPages::deletePage&id=' . $webpages->id, 'margin-left-15', false, false, true);

        $form = new Form();
        $form->printPanel('Link', "<a target='_blank' href='$linkPagina'>$linkPagina</a>");
        $form->printPanel('Título', $webpages->title);
        $form->printPanel('Link', $webpages->link);
        if ($webpages->courseid) {
            $course = $DB->get_record('course', array('id' => $webpages->courseid));
            if ($course) {
                $form->printPanel('Curso Vinculado', '<a href="?Courses::details&courseid=' . $webpages->courseid . '">' . $course->fullname . '</a>');
            }
        }
        $form->printPanel('Layout', $this->themeName($webpages->theme));
        $form->printPanel('Texto', $webpages->text);
        $form->printPanel('Visível', $webpages->visible ? 'sim' : 'não');

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
                array('WebPages::dashboard', 'Páginas estáticas'),
                'Nova página'
            ));
        } else {
            $webpages = kopere_dashboard_webpages::createBlank($webpages);
            DashboardUtil::startPage(array(
                array('WebPages::dashboard', 'Páginas estáticas'),
                array('WebPages::details&id=' . $webpages->id, $webpages->title),
                'Editando página'
            ));
        }

        echo '<div class="element-box">';

        $form = new Form('WebPages::editPageSave');
        $form->createHiddenInput('id', $webpages->id);
        $form->addInput(
            InputText::newInstance()->setTitle('Título')
                ->setName('title')
                ->setValue($webpages->title)
                ->setRequired()
        );
        $form->addInput(
            InputText::newInstance()->setTitle('Link')
                ->setName('link')
                ->setValue($webpages->link)
                ->setRequired()
        );
        $form->addInput(
            InputSelect::newInstance()->setTitle('Menu')
                ->setName('menuid')
                ->setValues(self::listMenus())
                ->setValue($webpages->menuid));
        $form->addInput(
            InputSelect::newInstance()->setTitle('Layout')
                ->setName('theme')
                ->setValues($this->listThemes())
                ->setValue($webpages->theme));

        $form->addInput(
            InputHtmlEditor::newInstance()->setTitle('Texto')
                ->setName('text')
                ->setValue($webpages->text)
                ->setRequired()
        );

        $form->addInput(
            InputCheckbox::newInstance()->setTitle('Visível')
                ->setName('visible')
                ->setChecked($webpages->visible));

        $form->createSubmitInput('Salvar');
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
            Mensagem::agendaMensagemWarning('Todos os dados devem ser preenchidos!');
            $this->editPage();
        } else {
            if ($webpages->id) {
                Mensagem::agendaMensagemSuccess('Página atualizada!');
                $DB->update_record('kopere_dashboard_webpages', $webpages);
            } else {
                Mensagem::agendaMensagemSuccess('Página criada!');
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
        Header::notfoundNull($webpages, 'Página não localizada!');

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_webpages', array('id' => $id));

            self::deleteCache();
            Mensagem::agendaMensagemSuccess('Página excluída com sucesso!');
            Header::location('WebPages::dashboard');
        }

        DashboardUtil::startPage(array(
            array('WebPages::dashboard', 'Páginas estáticas'),
            array('WebPages::details&id=' . $webpages->id, $webpages->title),
            'Excluíndo Página'
        ));

        echo "<h3>Excluíndo Página</h3>
              <p>Deseja realmente excluir a página <strong>{$webpages->title}</strong>?</p>";
        Botao::delete('Sim', 'WebPages::deletePage&status=sim&id=' . $webpages->id, '', false);
        Botao::add('Não', 'WebPages::details&id=' . $webpages->id, 'margin-left-10', false);

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
                    array('WebPages::dashboard', 'Páginas estáticas'),
                    'Novo Menu'
                ));
            } else {
                DashboardUtil::startPopup('Novo Menu', 'WebPages::editMenuSave');
            }
        } else {
            $menus = kopere_dashboard_menu::createBlank($menus);

            if (!AJAX_SCRIPT) {
                DashboardUtil::startPage(array(
                    array('WebPages::dashboard', 'Páginas estáticas'),
                    'Editando Menu'
                ));
            } else {
                DashboardUtil::startPopup('Editando Menu', 'WebPages::editMenuSave');
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
            InputText::newInstance()->setTitle('Título do Menu')
                ->setName('title')
                ->setValue($menus->title)
                ->setRequired()
        );

        $form->addInput(
            InputText::newInstance()->setTitle('Link do Menu  ')
                ->setName('link')
                ->setValue($menus->link)
                ->setRequired()
        );
        if (!AJAX_SCRIPT) {
            $form->createSubmitInput('Salvar');
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
            Mensagem::agendaMensagemWarning('Todos os dados devem ser preenchidos!');
        } else {
            if ($menu->id) {
                Mensagem::agendaMensagemSuccess('Menu atualizado!');
                $DB->update_record('kopere_dashboard_menu', $menu);
            } else {
                Mensagem::agendaMensagemSuccess('Menu criado!');
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
        Header::notfoundNull($menu, 'Página não localizada!');

        if ($status == 'sim') {
            $DB->delete_records('kopere_dashboard_menu', array('id' => $id));

            self::deleteCache();
            Mensagem::agendaMensagemSuccess('Menu excluída com sucesso!');
            Header::location('WebPages::dashboard');
        }

        DashboardUtil::startPage(array(
            array('WebPages::dashboard', 'Menu estáticas'),
            array('WebPages::details&id=' . $menu->id, $menu->title),
            'Excluíndo Menu'
        ));

        echo "<p>Deseja realmente excluir o menu <strong>{$menu->title}</strong>?</p>";
        Botao::delete('Sim', 'WebPages::deleteMenu&status=sim&id=' . $menu->id, '', false);
        Botao::add('Não', 'WebPages::dashboard', 'margin-left-10', false);

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

    private function listThemes() {
        $layouts = array(
            array('key' => 'base',
                'value' => 'O layout sem os blocos'
            ),
            array('key' => 'standard',
                'value' => 'Layout padrão com blocos'
            ),
            array('key' => 'frontpage',
                'value' => 'Layout da home page do site.'
            ),
            array('key' => 'popup',
                'value' => 'Sem navegação, sem blocos, sem cabeçalho'
            ),
            array('key' => 'frametop',
                'value' => 'Sem blocos e rodapé mínimo'
            ),
            array('key' => 'print',
                'value' => 'Deve exibir apenas o conteúdo e os cabeçalhos básicos'
            ),
            array('key' => 'report',
                'value' => 'O layout da página usado para relatórios'
            ),
        );

        return $layouts;
    }

    public function settings() {
        ob_clean();
        DashboardUtil::startPopup('Configurações das páginas estáticas', 'Settings::settingsSave');

        $form = new Form();

        $form->addInput(
            InputSelect::newInstance()->setTitle('Layout da página "Todas as páginas"')
                ->setValues($this->listThemes())
                ->setValueByConfig('webpages_theme'));

        $form->addInput(
            InputText::newInstance()->setTitle('ID de acompanhamento do Google Analytics')
                ->setValueByConfig('webpages_analytics_id')
                ->setDescription('Sequencia de 13 caracteres, iniciando em UA'));

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