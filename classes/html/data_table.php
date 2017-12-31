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
    private $column_info = array();
    /**
     * @var array
     */
    private $column_data = array();
    /**
     * @var array
     */
    private $column_defs = array();

    /**
     * @var string
     */
    private $ajax_url = null;

    /**
     * @var string
     */
    private $click_redirect = null;

    /**
     * @var string
     */
    private $click_modal = null;

    /**
     * @var string
     */
    private $table_id = '';

    /**
     * @var boolean
     */
    private $is_export = false;

    /**
     * data_table constructor.
     *
     * @param array $_column
     * @param array $_column_info
     */
    public function __construct($_column = array(), $_column_info = array()) {
        $this->table_id = 'datatable_' . uniqid();
        $this->column = $_column;
        $this->column_info = $_column_info;
    }

    /**
     * @param boolean $is_export
     */
    public function set_is_export($is_export) {
        $this->is_export = $is_export;
    }

    /**
     * @return string
     */
    public function get_table_id() {
        return $this->table_id;
    }

    /**
     * @param string $table_id
     */
    public function set_table_id($table_id) {
        $this->table_id = $table_id;
    }

    /**
     * @param $ajax_url
     */
    public function set_ajax_url($ajax_url) {
        $this->ajax_url = $ajax_url;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function set_click_redirect($url, $chave) {
        $this->click_redirect = array();
        $this->click_redirect['chave'] = $chave;
        $this->click_redirect['url'] = '?' . $url;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function set_click_modal($url, $chave) {
        $this->click_modal = array();
        $this->click_modal['chave'] = $chave;
        $this->click_modal['url'] = $url;
    }

    /**
     * @param $title
     * @param $cols
     */
    public function add_info_header($title, $cols) {
        $column = new table_header_item();
        $column->title = $title;
        $column->cols = $cols;

        $this->column_info[] = $column;
    }

    /**
     * @param        $title
     * @param null $chave
     * @param string $type
     * @param null $funcao
     * @param null $style_header
     * @param null $style_col
     */
    public function add_header($title, $chave = null, $type = table_header_item::TYPE_TEXT,
                               $funcao = null, $style_header = null, $style_col = null) {
        $column = new table_header_item();
        $column->chave = $chave;
        $column->type = $type;
        $column->title = $title;
        $column->funcao = $funcao;
        $column->style_header = $style_header;
        $column->style_col = $style_col;

        $this->column[] = $column;
    }

    /**
     * @param string $class
     * @param bool $print_body
     */
    public function print_header($class = '', $print_body = true) {
        if ($this->is_export && $this->ajax_url == null) {
            button::info(get_string_kopere('reports_export'), "{$_SERVER['QUERY_STRING']}&export=xls");

            export::header(optional_param('export', '', PARAM_TEXT));
        }

        echo '<table id="' . $this->table_id . '" class="table table-hover" >';
        echo '<thead>';

        if ($this->column_info) {
            echo '<tr class="' . $class . '">';
            /** @var table_header_item $column_info */
            foreach ($this->column_info as $key => $column_info) {
                echo "<th class=\"header-col text-center\" colspan=\"{$column_info->cols}\">";
                echo $column_info->title;
                echo '</th>';
            }
            echo '</tr>';

            $this->column_defs[] = '{ visible : false, targets : -1 }';
        }

        echo '<tr class="' . $class . '">';
        /** @var table_header_item $column */
        foreach ($this->column as $key => $column) {
            echo '<th class="text-center th_' . $column->chave . '" style="' . $column->style_header . '">';
            if ($column->title == '') {
                echo "&nbsp;";
            } else {
                echo $column->title;
            }
            echo '</th>';

            $this->column_data[] = '{ "data": "' . $column->chave . '" }';

            if ($column->type == table_header_item::TYPE_INT) {
                $this->column_defs[] = '{ type: "numeric-comma", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::TYPE_CURRENCY) {
                $this->column_defs[] = '{ type: "currency", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::TYPE_DATE) {
                $this->column_defs[] = '{ type: "date-uk", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::TYPE_BYTES) {
                $this->column_defs[] = '{ type: "file-size", render: rendererFilesize, targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_DATE) {
                $this->column_defs[] = '{ render: dataDatetimeRenderer, type: "date-uk", targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_VISIBLE) {
                $this->column_defs[] = '{ render: dataVisibleRenderer, targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_STATUS) {
                $this->column_defs[] = '{ render: dataStatusRenderer, targets: ' . $key . ' }';
            } else if ($column->type == table_header_item::RENDERER_TRUEFALSE) {
                $this->column_defs[] = '{ render: dataTrueFalseRenderer, targets: ' . $key . ' }';
            }

        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";

        if ($this->click_redirect != null && $print_body) {
            echo '<tbody class="hover-pointer"></tbody>';
        } else if ($this->click_modal != null && $print_body) {
            echo '<tbody class="hover-pointer"></tbody>';
        }
    }

    /**
     * @param        $linhas
     * @param string $class
     */
    public function set_row($linhas, $class = '') {
        if ($this->click_redirect != null) {
            echo '<tbody class="hover-pointer">';
        } else if ($this->click_modal != null) {
            echo '<tbody class="hover-pointer">';
        } else {
            echo '<tbody>';
        }

        foreach ($linhas as $linha) {
            echo '<tr>';
            foreach ($this->column as $column) {

                $_class = $class;
                if ($column->type == table_header_item::TYPE_INT) {
                    $_class .= ' text-center';
                } else if ($column->type == table_header_item::TYPE_ACTION) {
                    $_class .= ' button-actions text-nowrap';
                }

                $_class .= ' ' . $column->style_col;
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
                $this->print_row($html, $_class);
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
     * @param bool $process_server
     * @param string $order
     * @param string $extras
     *
     * @return string
     */
    public function close($process_server = false, $order = '', $extras = '') {
        echo '</table>';

        export::close();

        $process_server_html = '';
        if ($process_server) {
            $process_server_html = ', "processing": true, "serverSide": true';
        }

        if (isset($order[1])) {
            $order = ',' . $order;
        }
        if (isset($extras[1])) {
            $extras = ',' . $extras;
        }

        $ajax_config = '';
        if ($this->ajax_url) {
            $ajax_config = 'ajax : {url:"open-ajax-table.php?' . $this->ajax_url . '",type: "POST"},';
        }

        $column_data = implode(", ", $this->column_data);
        $column_defs = implode(", ", $this->column_defs);
        echo "<script>
                  {$this->table_id} = null;
                  \$(document).ready( function() {
                      {$this->table_id} = \$( '#{$this->table_id}' ).DataTable({
                          oLanguage   : dataTables_oLanguage,
                          {$ajax_config}
                          autoWidth  : false,
                          columns    : [ {$column_data} ],
                          columnDefs : [ {$column_defs} ]
                          {$process_server_html}
                          {$extras}
                          {$order}
                      });
                  });

              </script>";

        if ($this->click_redirect) {
            $this->on_click_reditect();
        } else if ($this->click_modal) {
            $this->on_click_modal();
        }

        return $this->table_id;
    }

    /**
     *
     */
    private function on_click_reditect() {
        $click_chave = $this->click_redirect['chave'];
        $click_url = $this->click_redirect['url'];

        echo "<script>
                  \$(document).ready( function() {
                      \$( '#{$this->table_id} tbody' ).on( 'click', 'tr', function () {
                          var data     = {$this->table_id}.row( this ).data ();
                          var clickUrl = '{$click_url}';
                          newClickUrl  = clickUrl.replace( '{{$click_chave}}', data[ '{$click_chave}' ] );

                          location.href = newClickUrl;
                      } );
                  });
              </script>";
    }

    /**
     *
     */
    private function on_click_modal() {

        $click_chave = $this->click_modal['chave'];
        $click_url = $this->click_modal['url'];

        echo "<script>
                  \$(document).ready( function() {
                      \$( '#{$this->table_id} tbody' ).on( 'click', 'tr', function () {
                          var data = {$this->table_id}.row( this ).data ();
                          target = $( '#modal-edit' );

                          var clickUrl = '{$click_url}';
                          clickUrl = clickUrl.replace( '{{$click_chave}}', data[ '{$click_chave}' ] )

                          var option = $.extend ( { remote : clickUrl }, target.data () );
                          target.modal( option );
                      } );
                  });
              </script> ";
    }
}