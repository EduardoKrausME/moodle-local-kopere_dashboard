<?php
/**
 * User: Eduardo Kraus
 * Date: 14/05/17
 * Time: 22:54
 */

namespace local_kopere_dashboard\html;


use local_kopere_dashboard\util\Export;

class DataTable
{
    function __construct ()
    {
        $this->tableId = 'datatable_' . uniqid ();
    }

    private $column        = array();
    private $columnInfo    = array();
    private $columnData    = array();
    private $columnDefs    = array();
    private $ajaxUrl       = null;
    private $clickRedirect = null;
    private $clickModal    = null;
    private $tableId       = '';
    private $isExport      = false;

    /**
     * @param boolean $isExport
     */
    public function setIsExport ( $isExport )
    {
        $this->isExport = $isExport;
    }

    /**
     * @return string
     */
    public function getTableId ()
    {
        return $this->tableId;
    }

    /**
     * @param string $tableId
     */
    public function setTableId ( $tableId )
    {
        $this->tableId = $tableId;
    }

    public function setAjaxUrl ( $ajaxUrl )
    {
        $this->ajaxUrl = $ajaxUrl;
    }

    public function setClickRedirect ( $url, $chave )
    {
        $this->clickRedirect            = array();
        $this->clickRedirect[ 'chave' ] = $chave;
        $this->clickRedirect[ 'url' ]   = '?' . $url;
    }

    public function setClickModal ( $url, $chave )
    {
        $this->clickModal            = array();
        $this->clickModal[ 'chave' ] = $chave;
        $this->clickModal[ 'url' ]   = $url;
    }

    public function addInfoHeader ( $title, $cols )
    {
        $column        = new TableHeaderItem();
        $column->title = $title;
        $column->cols  = $cols;

        $this->columnInfo[] = $column;
    }

    public function addHeader ( $title, $chave = null, $type = TableHeaderItem::TYPE_TEXT, $funcao = null, $styleHeader = null, $styleCol = null )
    {
        $column              = new TableHeaderItem();
        $column->chave       = $chave;
        $column->type        = $type;
        $column->title       = $title;
        $column->funcao      = $funcao;
        $column->styleHeader = $styleHeader;
        $column->styleCol    = $styleCol;

        $this->column[] = $column;
    }

    public function printHeader ( $class = '', $printBody = true )
    {
        if ( $this->isExport && $this->ajaxUrl == null ) {
            Botao::info ( 'Exportar para Excel', "?{$_SERVER['QUERY_STRING']}&export=xls" );

            Export::exportHeader ( optional_param ( 'export', '', PARAM_TEXT ) );
        }

        echo '<table id="' . $this->tableId . '" class="table table-hover" >';
        echo '<thead>';

        if ( $this->columnInfo ) {
            echo '<tr class="' . $class . '">';
            /** @var TableHeaderItem $columnInfo */
            foreach ( $this->columnInfo as $key => $columnInfo ) {
                echo "<th class=\"header-col text-center\" colspan=\"{$columnInfo->cols}\">";
                echo $columnInfo->title;
                echo '</th>';
            }
            echo '</tr>';

            $this->columnDefs[] = '{ visible : false, targets : -1 }';
        }

        echo '<tr class="' . $class . '">';
        /** @var TableHeaderItem $column */
        foreach ( $this->column as $key => $column ) {
            echo '<th class="text-center th_' . $column->chave . '" style="' . $column->styleHeader . '">';
            if ( $column->title == '' )
                echo "&nbsp;";
            else
                echo $column->title;
            echo '</th>';

            $this->columnData[] = '{ "data": "' . $column->chave . '" }';

            if ( $column->type == TableHeaderItem::TYPE_INT )
                $this->columnDefs[] = '{ type: "numeric-comma", targets: ' . $key . ' }';
            elseif ( $column->type == TableHeaderItem::TYPE_CURRENCY )
                $this->columnDefs[] = '{ type: "currency", targets: ' . $key . ' }';
            elseif ( $column->type == TableHeaderItem::TYPE_DATE )
                $this->columnDefs[] = '{ type: "date-uk", targets: ' . $key . ' }';
            elseif ( $column->type == TableHeaderItem::TYPE_BYTES )
                $this->columnDefs[] = '{ type: "file-size", targets: ' . $key . ' }';

            elseif ( $column->type == TableHeaderItem::RENDERER_DATE )
                $this->columnDefs[] = '{ render: dataDatetimeRenderer, type: "date-uk", targets: ' . $key . ' }';
            elseif ( $column->type == TableHeaderItem::RENDERER_VISIBLE )
                $this->columnDefs[] = '{ render: dataVisibleRenderer, targets: ' . $key . ' }';
            elseif ( $column->type == TableHeaderItem::RENDERER_STATUS )
                $this->columnDefs[] = '{ render: dataStatusRenderer, targets: ' . $key . ' }';
            elseif ( $column->type == TableHeaderItem::RENDERER_TRUEFALSE )
                $this->columnDefs[] = '{ render: dataTrueFalseRenderer, targets: ' . $key . ' }';

        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";

        if ( $this->clickRedirect != null && $printBody )
            echo '<tbody class="hover-pointer"></tbody>';
        elseif ( $this->clickModal != null && $printBody )
            echo '<tbody class="hover-pointer"></tbody>';
    }

    public function setRow ( $linhas, $class = '' )
    {
        if ( $this->clickRedirect != null )
            echo '<tbody class="hover-pointer">';
        elseif ( $this->clickModal != null )
            echo '<tbody class="hover-pointer">';
        else
            echo '<tbody>';

        foreach ( $linhas as $linha ) {
            echo '<tr>';
            foreach ( $this->column as $column ) {

                $_class = $class;
                if ( $column->type == TableHeaderItem::TYPE_INT )
                    $_class .= ' text-center';
                elseif ( $column->type == TableHeaderItem::TYPE_ACTION )
                    $_class .= ' button-actions text-nowrap';

                $_class .= ' ' . $column->styleCol;
                if ( $column->funcao != null ) {
                    $funcao = $column->funcao;
                    $html   = $funcao( $linha, $column->chave );
                } else {
                    if ( is_array ( $linha ) )
                        $html = $linha[ $column->chave ];
                    else {
                        $chave = $column->chave;
                        $html  = $linha->$chave;
                    }
                }
                $this->printRow ( $html, $_class );
            }
            echo '</tr>';
        }
        echo '</tbody>';
    }

    public function printRow ( $html, $class = '' )
    {
        if ( $class == '' || $class == ' ' )
            echo '<td>';
        else
            echo "<td class=\"{$class}\">";
        echo $html;
        echo '</td>';
    }

    public function close ( $processServer = false, $order = '' )
    {
        echo '</table>';

        Export::exportClose ();

        $processServerHtml = '';
        if ( $processServer )
            $processServerHtml = ', "processing": true, "serverSide": true';

        if ( isset( $order[ 1 ] ) )
            $order = ',' . $order;

        $ajaxConfig = '';
        if ( $this->ajaxUrl )
            $ajaxConfig = 'ajax : {url:"open-ajax-table.php?' . $this->ajaxUrl . '",type: "POST"},';

        $columnData = implode ( ", ", $this->columnData );
        $columnDefs = implode ( ", ", $this->columnDefs );
        echo "<script>
                  {$this->tableId} = null;
                  \$(document).ready( function() {
                      {$this->tableId} = \$( '#{$this->tableId}' ).DataTable({
                          {$ajaxConfig}
                          autoWidth  : false,
                          columns    : [ {$columnData} ],
                          columnDefs : [ {$columnDefs} ],
                          language   : { url: '//cdn.datatables.net/plug-ins/1.10.15/i18n/Portuguese-Brasil.json' }
                          {$processServerHtml}
                          {$order}
                      });
                  });
                  


              </script>";

        if ( $this->clickRedirect )
            $this->onClickReditect ();
        elseif ( $this->clickModal )
            $this->onClickModal ();

        return $this->tableId;
    }

    private function onClickReditect ()
    {
        $clickChave = $this->clickRedirect[ 'chave' ];
        $clickUrl   = $this->clickRedirect[ 'url' ];

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

    private function onClickModal ()
    {

        $clickChave = $this->clickModal[ 'chave' ];
        $clickUrl   = $this->clickModal[ 'url' ];

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