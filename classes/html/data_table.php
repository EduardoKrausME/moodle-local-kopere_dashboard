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

use local_kopere_dashboard\util\export;
use local_kopere_dashboard\util\number_util;
use local_kopere_dashboard\util\url_util;

/**
 * Class data_table
 *
 * @package local_kopere_dashboard\html
 */
class data_table {
    /** @var array */
    private $column = [];
    /** @var array */
    private $columninfo = [];
    /** @var array */
    private $columndata = [];
    /** @var array */
    private $columndefs = [];

    /** @var string */
    private $ajaxurl = null;

    /** @var array */
    private $clickredirect = null;

    /** @var string */
    private $tableid = '';

    /** @var boolean */
    private $isexport = false;

    /**
     * data_table constructor.
     *
     * @param array $column
     * @param array $columninfo
     */
    public function __construct($column = [], $columninfo = []) {
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
    public function get_tableid() {
        return $this->tableid;
    }

    /**
     * @param string $tableid
     */
    public function set_tableid($tableid) {
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
     * @param bool $returnhtml
     *
     * @return string
     * @throws \coding_exception
     */
    public function print_header($class = '', $printbody = true, $returnhtml = false) {

        $return = "";

        if ($this->isexport && $this->ajaxurl == null) {
            button::info(get_string_kopere('reports_export'), url_util::querystring() . "&export=xls");
        }

        $return .= "<table id='{$this->tableid}' class='table table-hover' >";
        $return .= '<thead>';

        if ($this->columninfo) {
            $return .= "<tr class='{$class}'>";
            /** @var table_header_item $columninfo */
            foreach ($this->columninfo as $columninfo) {
                $return .= "<th class='header-col text-center' colspan='{$columninfo->cols}'>";

                if (strpos($columninfo->title, '[[[') === 0) {
                    $return .= get_string(substr($columninfo->title, 3, -3));
                } else if (strpos($columninfo->title, '[[') === 0) {
                    $return .= get_string_kopere(substr($columninfo->title, 2, -2));
                } else {
                    $return .= $columninfo->title;
                }

                $return .= '</th>';
            }
            $return .= '</tr>';

            $this->columndefs[] = (object)["visible" => false, "targets" => -1];
        }

        $return .= "<tr class='{$class}'>";

        /** @var table_header_item $column */
        foreach ($this->column as $key => $column) {
            $return .= "<th class='text-center th_{$column->chave}' style='{$column->style_header}'>";
            if ($column->title == '') {
                $return .= "&nbsp;";
            } else {
                if (strpos($column->title, '[[[') === 0) {
                    $return .= get_string(substr($column->title, 3, -3));
                } else if (strpos($column->title, '[[') === 0) {
                    $return .= get_string_kopere(substr($column->title, 2, -2));
                } else {
                    $return .= $column->title;
                }
            }
            $return .= '</th>';

            $this->columndata[] = (object)["data" => $column->chave];

            if ($column->type == table_header_item::TYPE_INT) {
                $this->columndefs[] = (object)["type" => "numeric-comma", "render" => "centerRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::TYPE_CURRENCY) {
                $this->columndefs[] = (object)["type" => "currency", "render" => "currencyRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::TYPE_DATE) {
                $this->columndefs[] = (object)["type" => "date-uk", "targets" => $key];
            } else if ($column->type == table_header_item::TYPE_BYTES) {
                $this->columndefs[] = (object)["type" => "file-size", "render" => "rendererFilesize", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_DATE) {
                $this->columndefs[] = (object)["type" => "date-uk", "render" => "dataDateRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_DATETIME) {
                $this->columndefs[] = (object)["type" => "date-uk", "render" => "dataDatetimeRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_VISIBLE) {
                $this->columndefs[] = (object)["render" => "dataVisibleRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_STATUS) {
                $this->columndefs[] = (object)["render" => "dataStatusRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_TRUEFALSE) {
                $this->columndefs[] = (object)["render" => "dataTrueFalseRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_USERPHOTO) {
                $this->columndefs[] = (object)["render" => "dataUserphotoRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::RENDERER_SEGUNDOS) {
                $this->columndefs[] = (object)["render" => "segundosRenderer", "targets" => $key];
            } else if ($column->type == table_header_item::TYPE_ACTION) {
                $this->columndefs[] = (object)["orderable" => false, "targets" => $key];
            }
        }
        $return .= '</tr>';
        $return .= '</thead>';
        $return .= "\n";

        if ($this->clickredirect != null && $printbody) {
            $return .= '<tbody class="hover-pointer"></tbody>';
        }

        if ($returnhtml) {
            return $return;
        } else {
            echo $return;
        }
    }

    /**
     * @param        $linhas
     * @param string $class
     * @throws \coding_exception
     */
    public function set_row($linhas, $class = '') {
        global $CFG;

        if ($this->clickredirect != null) {
            echo '<tbody class="hover-pointer">';
        } else {
            echo '<tbody>';
        }

        foreach ($linhas as $linha) {
            echo '<tr>';
            /** @var table_header_item $column */
            foreach ($this->column as $column) {

                $thisclass = $class;
                if ($column->type == table_header_item::TYPE_INT) {
                    $thisclass .= ' text-center';
                } else if ($column->type == table_header_item::TYPE_ACTION) {
                    $thisclass .= ' button-actions text-nowrap width-100';
                }

                $thisclass .= " {$column->style_col}";
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

                if (export::is_export()) {
                    if ($column->type == table_header_item::TYPE_INT) {
                    } else if ($column->type == table_header_item::TYPE_CURRENCY) {
                        $html = "R$ {$html}";
                    } else if ($column->type == table_header_item::TYPE_DATE) {
                        // $this->columndefs[] = (object)["type" => "date-uk", "targets" => $key];
                    } else if ($column->type == table_header_item::TYPE_BYTES) {
                        $html = number_util::bytes($html);
                    } else if ($column->type == table_header_item::RENDERER_DATE) {
                        $html = date("Y-m-d", $html);
                    } else if ($column->type == table_header_item::RENDERER_DATETIME) {
                        $html = date("Y-m-d H:i:s", $html);
                    } else if ($column->type == table_header_item::RENDERER_VISIBLE) {
                        if ($html == 0) {
                            $html = get_string_kopere('courses_invisible');
                        } else {
                            $html = get_string_kopere('courses_visible');
                        }
                    } else if ($column->type == table_header_item::RENDERER_STATUS) {
                        if ($html == 1) {
                            $html = get_string_kopere('notification_status_inactive');
                        } else {
                            $html = get_string_kopere('notification_status_active');
                        }
                    } else if ($column->type == table_header_item::RENDERER_TRUEFALSE) {
                        if ($html == 0 || $html == false || $html == 'false') {
                            $html = get_string('no');
                        } else {
                            $html = get_string('yes');
                        }
                    } else if ($column->type == table_header_item::RENDERER_USERPHOTO) {
                        $html = '<img class="media-object" src="' . $CFG->wwwroot .
                            '/local/kopere_dashboard/profile-image.php?type=photo_user&id=' . $html . '" />';
                    } else if ($column->type == table_header_item::RENDERER_SEGUNDOS) {
                        // $this->columndefs[] = (object)["render" => "segundosRenderer", "targets" => $key];
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
            echo "<td class='{$class}'>";
        }

        echo $html;
        echo '</td>';
    }

    /**
     * @param bool $processserver
     * @param string $extras
     * @param bool $returnhtml
     * @param bool $title
     *
     * @return string
     */
    public function close($processserver = false, $extras = null, $returnhtml = false, $title = false) {
        global $PAGE, $CFG;

        $return = '</table>';

        $initparams = [
            "autoWidth" => false,
            "columns" => $this->columndata,
            "columnDefs" => $this->columndefs,
            "export_title" => $title
        ];
        if ($extras) {
            $initparams = array_merge($initparams, $extras);
        }

        if ($processserver) {
            $initparams['processing'] = true;
            $initparams['serverSide'] = true;
        }

        if ($this->ajaxurl) {
            $initparams['ajax'] = (object)[
                "url" => $this->ajaxurl = str_replace("view.php", "view-ajax.php", $this->ajaxurl),
                "type" => "POST"
            ];
        }

        $PAGE->requires->js_call_amd('local_kopere_dashboard/dataTables_init', 'init', [$this->tableid, $initparams]);

        if ($this->clickredirect) {
            $this->on_clickreditect();
        }

        if (!$returnhtml) {
            echo $return;
        }

        export::close();

        return $return;
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

        $PAGE->requires->js_call_amd('local_kopere_dashboard/dataTables_init', 'click',
            [$this->tableid, $clickchave, $clickurl]);
    }
}
