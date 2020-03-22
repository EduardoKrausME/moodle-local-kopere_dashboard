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
 * @created    14/05/17 22:54
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\util\export;
use local_kopere_dashboard\util\url_util;

/**
 * Class data_table
 *
 * @package local_kopere_dashboard\html
 */
class data_table {
    /**
     * @var array
     */
    private $column = array();
    /**
     * @var array
     */
    private $columninfo = array();
    /**
     * @var array
     */
    private $columndata = array();
    /**
     * @var array
     */
    private $columndefs = array();

    /**
     * @var string
     */
    private $ajaxurl = null;

    /**
     * @var array
     */
    private $clickredirect = null;

    /**
     * @var string
     */
    private $tableid = '';

    /**
     * @var boolean
     */
    private $isexport = false;

    /**
     * data_table constructor.
     *
     * @param array $column
     * @param array $columninfo
     */
    public function __construct($column = array(), $columninfo = array()) {
        $this->tableid = 'datatable_' . uniqid();
        $this->column = $column;
        $this->columninfo = $columninfo;
    }

    /**
     * @param boolean $isexport
     */
    public function set_is_export($isexport) {
        $this->isexport = $isexport;
    }

    /**
     * @return string
     */
    public function get_table_id() {
        return $this->tableid;
    }

    /**
     * @param string $tableid
     */
    public function set_table_id($tableid) {
        $this->tableid = $tableid;
    }

    /**
     * @param $ajaxurl
     */
    public function set_ajax_url($ajaxurl) {
        $this->ajaxurl = $ajaxurl;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function set_click_redirect($url, $chave) {
        $this->clickredirect = [
            'chave' => $chave,
            'url' => $url
        ];
    }

    /**
     * @param $title
     * @param $cols
     */
    public function add_info_header($title, $cols) {
        $column = new table_header_item();
        $column->title = $title;
        $column->cols = $cols;

        $this->columninfo[] = $column;
    }

    /**
     * @param        $title
     * @param null $chave
     * @param string $type
     * @param null $funcao
     * @param null $styleheader
     * @param null $stylecol
     */
    public function add_header($title, $chave = null, $type = table_header_item::TYPE_TEXT,
                               $funcao = null, $styleheader = null, $stylecol = null) {
        $column = new table_header_item();
        $column->chave = $chave;
        $column->type = $type;
        $column->title = $title;
        $column->funcao = $funcao;
        $column->style_header = $styleheader;
        $column->style_col = $stylecol;

        $this->column[] = $column;
    }

    /**
     * @param string $class
     * @param bool $printbody
     *
     * @throws \coding_exception
     */
    public function print_header($class = '', $printbody = true) {
        if ($this->isexport && $this->ajaxurl == null) {
            button::info(get_string_kopere('reports_export'), url_util::querystring() . "&export=xls");
        }

        echo '<table id="' . $this->tableid . '" class="table table-hover" >';
        echo '<thead>';

        if ($this->columninfo) {
            echo '<tr class="' . $class . '">';
            /** @var table_header_item $columninfo */
            foreach ($this->columninfo as $key => $columninfo) {
                echo "<th class=\"header-col text-center\" colspan=\"{$columninfo->cols}\">";

                if (strpos($columninfo->title, '[[[') === 0) {
                    echo get_string(substr($columninfo->title, 3, -3));
                } elseif (strpos($columninfo->title, '[[') === 0) {
                    echo get_string_kopere(substr($columninfo->title, 2, -2));
                } else {
                    echo $columninfo->title;
                }

                echo '</th>';
            }
            echo '</tr>';

            $this->columndefs[] = (object)array("visible" => false, "targets" => -1);
        }

        echo '<tr class="' . $class . '">';
        /** @var table_header_item $column */
        foreach ($this->column as $key => $column) {
            echo '<th class="text-center th_' . $column->chave . '" style="' . $column->style_header . '">';
            if ($column->title == '') {
                echo "&nbsp;";
            } else {
                if (strpos($column->title, '[[[') === 0) {
                    echo get_string(substr($column->title, 3, -3));
                } elseif (strpos($column->title, '[[') === 0) {
                    echo get_string_kopere(substr($column->title, 2, -2));
                } else {
                    echo $column->title;
                }
            }
            echo '</th>';

            $this->columndata[] = (object)array("data" => $column->chave);

            if ($column->type == table_header_item::TYPE_INT) {
                $this->columndefs[] = (object)array("type" => "numeric-comma", "render" => "centerRenderer", "targets" => $key);
            } else if ($column->type == table_header_item::TYPE_CURRENCY) {
                $this->columndefs[] = (object)array("type" => "currency", "render" => "currencyRenderer", "targets" => $key);
            } else if ($column->type == table_header_item::TYPE_DATE) {
                $this->columndefs[] = (object)array("type" => "date-uk", "targets" => $key);
            } else if ($column->type == table_header_item::TYPE_BYTES) {
                $this->columndefs[] = (object)array("type" => "file-size", "render" => "rendererFilesize", "targets" => $key);
            } else if ($column->type == table_header_item::RENDERER_DATE) {
                $this->columndefs[] = (object)array("type" => "date-uk", "render" => "dataDatetimeRenderer", "targets" => $key);
            } else if ($column->type == table_header_item::RENDERER_VISIBLE) {
                $this->columndefs[] = (object)array("render" => "dataVisibleRenderer", "targets" => $key);
            } else if ($column->type == table_header_item::RENDERER_STATUS) {
                $this->columndefs[] = (object)array("render" => "dataStatusRenderer", "targets" => $key);
            } else if ($column->type == table_header_item::RENDERER_TRUEFALSE) {
                $this->columndefs[] = (object)array("render" => "dataTrueFalseRenderer", "targets" => $key);
            }
        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";

        if ($this->clickredirect != null && $printbody) {
            echo '<tbody class="hover-pointer"></tbody>';
        }
    }

    /**
     * @param        $linhas
     * @param string $class
     */
    public function set_row($linhas, $class = '') {
        if ($this->clickredirect != null) {
            echo '<tbody class="hover-pointer">';
        } else {
            echo '<tbody>';
        }

        foreach ($linhas as $linha) {
            echo '<tr>';
            foreach ($this->column as $column) {

                $thisclass = $class;
                if ($column->type == table_header_item::TYPE_INT) {
                    $thisclass .= ' text-center';
                } else if ($column->type == table_header_item::TYPE_ACTION) {
                    $thisclass .= ' button-actions text-nowrap width-30';
                }

                $thisclass .= ' ' . $column->style_col;
                if ($column->funcao != null) {
                    $funcao = $column->funcao;
                    $html = $funcao($linha, $column->chave);
                } else {
                    if (is_array($linha)) {
                        $html = $linha[$column->chave];
                    } else {
                        $chave = $column->chave;
                        $html = $linha->$chave;
                    }
                }
                $this->print_row($html, $thisclass);
            }
            echo '</tr>';
        }
        echo '</tbody>';
    }

    /**
     * @param        $html
     * @param string $class
     */
    public function print_row($html, $class = '') {
        if ($class == '' || $class == ' ') {
            echo '<td>';
        } else {
            echo "<td class=\"{$class}\">";
        }
        echo $html;
        echo '</td>';
    }

    /**
     * @param bool $processserver
     * @param string $order
     * @param string $extras
     *
     * @return string
     */
    public function close($processserver = false, $extras = null) {
        global $PAGE;

        echo '</table>';

        export::close();

        $init_params = array(
            "autoWidth" => false,
            "columns" => $this->columndata,
            "columnDefs" => $this->columndefs
        );
        if ($extras) {
            $init_params = array_merge($init_params, $extras);
        }

        if ($processserver) {
            $init_params['processing'] = true;
            $init_params['serverSide'] = true;
        }

        if ($this->ajaxurl) {
            $init_params['ajax'] = (object)[
                "url" => "load-ajax.php{$this->ajaxurl}",
                "type" => "POST"
            ];
        }

        $PAGE->requires->js_call_amd('local_kopere_dashboard/dataTables_init', 'init', array($this->tableid, $init_params));

        if ($this->clickredirect) {
            $this->on_clickreditect();
        }

        return $this->tableid;
    }

    /**
     *
     */
    private function on_clickreditect() {
        global $PAGE;

        $clickurl = $this->clickredirect['url'];
        $clickchave = $this->clickredirect['chave'];

        if (is_string($clickchave)) {
            $clickchave = [$clickchave];
        }

        $PAGE->requires->js_call_amd('local_kopere_dashboard/dataTables_init', 'click', array($this->tableid, $clickchave, $clickurl));
    }
}