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

use local_kopere_dashboard\util\Export;

/**
 * Class DataTable
 *
 * @package local_kopere_dashboard\html
 */
class DataTable {
    /**
     * @var array
     */
    private $column = array();
    /**
     * @var array
     */
    private $columnInfo = array();
    /**
     * @var array
     */
    private $columnData = array();
    /**
     * @var array
     */
    private $columnDefs = array();

    /**
     * @var string
     */
    private $ajaxUrl = null;

    /**
     * @var string
     */
    private $clickRedirect = null;

    /**
     * @var string
     */
    private $clickModal = null;

    /**
     * @var string
     */
    private $tableId = '';

    /**
     * @var boolean
     */
    private $isExport = false;

    /**
     * DataTable constructor.
     *
     * @param array $_column
     * @param array $_columnInfo
     */
    public function __construct( $_column = array(), $_columnInfo = array()) {
        $this->tableId = 'datatable_' . uniqid();
        $this->column = $_column;
        $this->columnInfo = $_columnInfo;
    }

    /**
     * @param boolean $isExport
     */
    public function setIsExport($isExport) {
        $this->isExport = $isExport;
    }

    /**
     * @return string
     */
    public function getTableId() {
        return $this->tableId;
    }

    /**
     * @param string $tableId
     */
    public function setTableId($tableId) {
        $this->tableId = $tableId;
    }

    /**
     * @param $ajaxUrl
     */
    public function setAjaxUrl( $ajaxUrl) {
        $this->ajaxUrl = $ajaxUrl;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function setClickRedirect( $url, $chave) {
        $this->clickRedirect = array();
        $this->clickRedirect['chave'] = $chave;
        $this->clickRedirect['url'] = '?' . $url;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function setClickModal( $url, $chave) {
        $this->clickModal = array();
        $this->clickModal['chave'] = $chave;
        $this->clickModal['url'] = $url;
    }

    /**
     * @param $title
     * @param $cols
     */
    public function addInfoHeader( $title, $cols) {
        $column = new TableHeaderItem();
        $column->title = $title;
        $column->cols = $cols;

        $this->columnInfo[] = $column;
    }

    /**
     * @param        $title
     * @param null   $chave
     * @param string $type
     * @param null   $funcao
     * @param null   $styleHeader
     * @param null   $styleCol
     */
    public function addHeader( $title, $chave = null, $type = TableHeaderItem::TYPE_TEXT, $funcao = null, $styleHeader = null, $styleCol = null) {
        $column = new TableHeaderItem();
        $column->chave = $chave;
        $column->type = $type;
        $column->title = $title;
        $column->funcao = $funcao;
        $column->styleHeader = $styleHeader;
        $column->styleCol = $styleCol;

        $this->column[] = $column;
    }

    /**
     * @param string $class
     * @param bool   $printBody
     */
    public function printHeader( $class = '', $printBody = true) {
        if ($this->isExport && $this->ajaxUrl == null) {
            Button::info(get_string_kopere('reports_export'), "{$_SERVER['QUERY_STRING']}&export=xls");

            Export::exportHeader(optional_param('export', '', PARAM_TEXT));
        }

        echo '<table id="' . $this->tableId . '" class="table table-hover" >';
        echo '<thead>';

        if ($this->columnInfo) {
            echo '<tr class="' . $class . '">';
            /** @var TableHeaderItem $columnInfo */
            foreach ($this->columnInfo as $key => $columnInfo) {
                echo "<th class=\"header-col text-center\" colspan=\"{$columnInfo->cols}\">";
                echo $columnInfo->title;
                echo '</th>';
            }
            echo '</tr>';

            $this->columnDefs[] = '{ visible : false, targets : -1 }';
        }

        echo '<tr class="' . $class . '">';
        /** @var TableHeaderItem $column */
        foreach ($this->column as $key => $column) {
            echo '<th class="text-center th_' . $column->chave . '" style="' . $column->styleHeader . '">';
            if ($column->title == '') {
                echo "&nbsp;";
            } else {
                echo $column->title;
            }
            echo '</th>';

            $this->columnData[] = '{ "data": "' . $column->chave . '" }';

            if ($column->type == TableHeaderItem::TYPE_INT) {
                $this->columnDefs[] = '{ type: "numeric-comma", targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::TYPE_CURRENCY) {
                $this->columnDefs[] = '{ type: "currency", targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::TYPE_DATE) {
                $this->columnDefs[] = '{ type: "date-uk", targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::TYPE_BYTES) {
                $this->columnDefs[] = '{ type: "file-size", render: rendererFilesize, targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::RENDERER_DATE) {
                $this->columnDefs[] = '{ render: dataDatetimeRenderer, type: "date-uk", targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::RENDERER_VISIBLE) {
                $this->columnDefs[] = '{ render: dataVisibleRenderer, targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::RENDERER_STATUS) {
                $this->columnDefs[] = '{ render: dataStatusRenderer, targets: ' . $key . ' }';
            } else if ($column->type == TableHeaderItem::RENDERER_TRUEFALSE) {
                $this->columnDefs[] = '{ render: dataTrueFalseRenderer, targets: ' . $key . ' }';
            }

        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";

        if ($this->clickRedirect != null && $printBody) {
            echo '<tbody class="hover-pointer"></tbody>';
        } else if ($this->clickModal != null && $printBody) {
            echo '<tbody class="hover-pointer"></tbody>';
        }
    }

    /**
     * @param        $linhas
     * @param string $class
     */
    public function setRow( $linhas, $class = '') {
        if ($this->clickRedirect != null) {
            echo '<tbody class="hover-pointer">';
        } else if ($this->clickModal != null) {
            echo '<tbody class="hover-pointer">';
        } else {
            echo '<tbody>';
        }

        foreach ($linhas as $linha) {
            echo '<tr>';
            foreach ($this->column as $column) {

                $_class = $class;
                if ($column->type == TableHeaderItem::TYPE_INT) {
                    $_class .= ' text-center';
                } else if ($column->type == TableHeaderItem::TYPE_ACTION) {
                    $_class .= ' button-actions text-nowrap';
                }

                $_class .= ' ' . $column->styleCol;
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
                $this->printRow($html, $_class);
            }
            echo '</tr>';
        }
        echo '</tbody>';
    }

    /**
     * @param        $html
     * @param string $class
     */
    public function printRow( $html, $class = '') {
        if ($class == '' || $class == ' ') {
            echo '<td>';
        } else {
            echo "<td class=\"{$class}\">";
        }
        echo $html;
        echo '</td>';
    }

    /**
     * @param bool   $processServer
     * @param string $order
     * @param string $extras
     *
     * @return string
     */
    public function close( $processServer = false, $order = '', $extras='') {
        echo '</table>';

        Export::exportClose();

        $processServerHtml = '';
        if ($processServer) {
            $processServerHtml = ', "processing": true, "serverSide": true';
        }

        if (isset($order[1])) {
            $order = ',' . $order;
        }
        if (isset($extras[1])) {
            $extras = ','.$extras;
        }

        $ajaxConfig = '';
        if ($this->ajaxUrl) {
            $ajaxConfig = 'ajax : {url:"open-ajax-table.php?' . $this->ajaxUrl . '",type: "POST"},';
        }

        $columnData = implode(", ", $this->columnData);
        $columnDefs = implode(", ", $this->columnDefs);
        echo "<script>
                  {$this->tableId} = null;
                  \$(document).ready( function() {
                      {$this->tableId} = \$( '#{$this->tableId}' ).DataTable({
                          oLanguage   : dataTables_oLanguage,
                          {$ajaxConfig}
                          autoWidth  : false,
                          columns    : [ {$columnData} ],
                          columnDefs : [ {$columnDefs} ]
                          {$processServerHtml}
                          {$extras}
                          {$order}
                      });
                  });

              </script>";

        if ($this->clickRedirect) {
            $this->onClickReditect();
        } else if ($this->clickModal) {
            $this->onClickModal();
        }

        return $this->tableId;
    }

    /**
     *
     */
    private function onClickReditect() {
        $clickChave = $this->clickRedirect['chave'];
        $clickUrl = $this->clickRedirect['url'];

        echo "<script>
                  \$(document).ready( function() {
                      \$( '#{$this->tableId} tbody' ).on( 'click', 'tr', function () {
                          var data     = {$this->tableId}.row( this ).data ();
                          var clickUrl = '{$clickUrl}';
                          newClickUrl  = clickUrl.replace( '{{$clickChave}}', data[ '{$clickChave}' ] );

                          location.href = newClickUrl;
                      } );
                  });
              </script>";
    }

    /**
     *
     */
    private function onClickModal() {

        $clickChave = $this->clickModal['chave'];
        $clickUrl = $this->clickModal['url'];

        echo "<script>
                  \$(document).ready( function() {
                      \$( '#{$this->tableId} tbody' ).on( 'click', 'tr', function () {
                          var data = {$this->tableId}.row( this ).data ();
                          target = $( '#modal-edit' );

                          var clickUrl = '{$clickUrl}';
                          clickUrl = clickUrl.replace( '{{$clickChave}}', data[ '{$clickChave}' ] )

                          var option = $.extend ( { remote : clickUrl }, target.data () );
                          target.modal( option );
                      } );
                  });
              </script> ";
    }
}