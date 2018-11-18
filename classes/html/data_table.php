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
     * @var string
     */
    private $clickredirect = null;

    /**
     * @var string
     */
    private $clickmodal = null;

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
        $this->clickredirect = array();
        $this->clickredirect['chave'] = $chave;
        $this->clickredirect['url'] = $url;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function set_click_modal($url, $chave) {
        $this->clickmodal = array();
        $this->clickmodal['chave'] = $chave;
        $this->clickmodal['url'] = $url;
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
     * @param bool   $printbody
     *
     * @throws \coding_exception
     */
    public function print_header($class = '', $printbody = true) {
        global $CFG;
        if ($this->isexport && $this->ajaxurl == null) {
            button::info(get_string_kopere('reports_export'), url_util::querystring(). "&export=xls");

            export::header(optional_param('export', '', PARAM_TEXT));
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
                }elseif (strpos($columninfo->title, '[[') === 0) {
                    echo get_string_kopere(substr($columninfo->title, 2, -2));
                } else {
                    echo $columninfo->title;
                }

                echo '</th>';
            }
            echo '</tr>';

            $this->columndefs[] = '{ visible : false, targets : -1 }';
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
                }elseif (strpos($column->title, '[[') === 0) {
                    echo get_string_kopere(substr($column->title, 2, -2));
                } else {
                    echo $column->title;
                }
            }
            echo '</th>';

            $this->columndata[] = '{ "data": "' . $column->chave . '" }';

            if ($column->type == table_header_item::TYPE_INT) {
                $this->columndefs[] = '{ type: "numeric-comma", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::TYPE_CURRENCY) {
                $this->columndefs[] = '{ type: "currency", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::TYPE_DATE) {
                $this->columndefs[] = '{ type: "date-uk", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::TYPE_BYTES) {
                $this->columndefs[] = '{ type: "file-size", render: rendererFilesize, targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_DATE) {
                $this->columndefs[] = '{ render: dataDatetimeRenderer, type: "date-uk", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_VISIBLE) {
                $this->columndefs[] = '{ render: dataVisibleRenderer, targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_STATUS) {
                $this->columndefs[] = '{ render: dataStatusRenderer, targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_TRUEFALSE) {
                $this->columndefs[] = '{ render: dataTrueFalseRenderer, targets: ' . $key . ' }';
            }
        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";

        if ($this->clickredirect != null && $printbody) {
            echo '<tbody class="hover-pointer"></tbody>';
        } else if ($this->clickmodal != null && $printbody) {
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
        } else if ($this->clickmodal != null) {
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
                    $thisclass .= ' button-actions text-nowrap';
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
    public function close($processserver = false, $order = '', $extras = '') {
        echo '</table>';

        export::close();

        $processserverhtml = '';
        if ($processserver) {
            $processserverhtml = ', "processing": true, "serverSide": true';
        }

        if (isset($order[1])) {
            $order = ',' . $order;
        }
        if (isset($extras[1])) {
            $extras = ',' . $extras;
        }

        $ajaxconfig = '';
        if ($this->ajaxurl) {
            $ajaxconfig = 'ajax : {url:"open-ajax-table.php' . $this->ajaxurl . '",type: "POST"},';
        }

        $columndata = implode(", ", $this->columndata);
        $columndefs = implode(", ", $this->columndefs);
        echo "<script>
                  {$this->tableid} = null;
                  \$(document).ready( function() {
                      {$this->tableid} = \$( '#{$this->tableid}' ).DataTable({
                          oLanguage   : dataTables_oLanguage,
                          {$ajaxconfig}
                          autoWidth  : false,
                          columns    : [ {$columndata} ],
                          columnDefs : [ {$columndefs} ]
                          {$processserverhtml}
                          {$extras}
                          {$order}
                      });
                  });

              </script>";

        if ($this->clickredirect) {
            $this->on_clickreditect();
        } else if ($this->clickmodal) {
            $this->on_clickmodal();
        }

        return $this->tableid;
    }

    /**
     *
     */
    private function on_clickreditect() {
        $clickchave = $this->clickredirect['chave'];
        $clickurl = $this->clickredirect['url'];

        echo "<script>
                  \$(document).ready( function() {
                      \$( '#{$this->tableid} tbody' ).on( 'click', 'tr', function () {
                          var data     = {$this->tableid}.row( this ).data ();
                          var clickUrl = '{$clickurl}';
                          newClickUrl  = clickUrl.replace( '{{$clickchave}}', data[ '{$clickchave}' ] );

                          location.href = newClickUrl;
                      } );
                  });
              </script>";
    }

    /**
     *
     */
    private function on_clickmodal() {

        $clickchave = $this->clickmodal['chave'];
        $clickurl = $this->clickmodal['url'];

        echo "<script>
                  \$(document).ready( function() {
                      \$( '#{$this->tableid} tbody' ).on( 'click', 'tr', function () {
                          var data = {$this->tableid}.row( this ).data ();
                          target = $( '#modal-edit' );

                          var clickUrl = '{$clickurl}';
                          clickUrl = clickUrl.replace( '{{$clickchave}}', data[ '{$clickchave}' ] )

                          var option = $.extend ( { remote : clickUrl }, target.data () );
                          target.modal( option );
                      } );
                  });
              </script> ";
    }
}