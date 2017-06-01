<?php
/**
 * User: Eduardo Kraus
 * Date: 14/05/17
 * Time: 22:54
 */

namespace local_kopere_dashboard\html;


class DataTable
{
    function __construct ( $adicional = '' )
    {
        $this->tableId = 'datatable_' . uniqid ();
        echo '<table id="' . $this->tableId . '" class="table table-hover" ';
        echo $adicional;
        echo '>';
    }

    private $column        = array();
    private $columnData    = array();
    private $columnDefs    = array();
    private $ajaxUrl       = null;
    private $clickRedirect = null;
    private $clickModal = null;
    private $tableId       = '';

    public function setAjaxUrl ( $ajaxUrl )
    {
        $this->ajaxUrl = $ajaxUrl;
    }

    public function setClickRedirect ( $url, $chave )
    {
        $this->clickRedirect            = array();
        $this->clickRedirect[ 'chave' ] = $chave;
        $this->clickRedirect[ 'url' ]   = $url;
    }
    public function setClickModal ( $url, $chave )
    {
        $this->clickModal            = array();
        $this->clickModal[ 'chave' ] = $chave;
        $this->clickModal[ 'url' ]   = $url;
    }

    public function addHeader ( $titulo, $chave = null, $type = TableHeaderItem::TYPE_TEXT, $funcao = null, $styleHeader = null, $styleCol = null )
    {
        $coluna              = new TableHeaderItem();
        $coluna->chave       = $chave;
        $coluna->type        = $type;
        $coluna->titulo      = $titulo;
        $coluna->funcao      = $funcao;
        $coluna->styleHeader = $styleHeader;
        $coluna->styleCol    = $styleCol;

        $this->column[] = $coluna;
    }

    public function printHeader ( $class = '', $printBody = true )
    {
        echo '<thead>';
        echo '<tr class="' . $class . '">';
        /** @var TableHeaderItem $coluna */
        foreach ( $this->column as $key => $coluna ) {
            echo '<th class="text-center th_' . $coluna->chave . '" style="' . $coluna->styleHeader . '">';
            if ( $coluna->titulo == '' )
                echo "&nbsp;";
            else
                echo $coluna->titulo;
            echo '</th>';

            $this->columnData[] = '{ "data": "' . $coluna->chave . '" }';

            if ( $coluna->type == TableHeaderItem::TYPE_INT )
                $this->columnDefs[] = '{ type: "numeric-comma", targets: ' . $key . ' }';
            elseif ( $coluna->type == TableHeaderItem::TYPE_CURRENCY )
                $this->columnDefs[] = '{ type: "currency", targets: ' . $key . ' }';
            elseif ( $coluna->type == TableHeaderItem::TYPE_DATE )
                $this->columnDefs[] = '{ type: "date-uk", targets: ' . $key . ' }';
            elseif ( $coluna->type == TableHeaderItem::TYPE_BYTES )
                $this->columnDefs[] = '{ type: "file-size", targets: ' . $key . ' }';

            elseif ( $coluna->type == TableHeaderItem::RENDERER_DATE )
                $this->columnDefs[] = '{ render: dataDatetimeRenderer, type: "date-uk", targets: ' . $key . ' }';
            elseif ( $coluna->type == TableHeaderItem::RENDERER_VISIBLE )
                $this->columnDefs[] = '{ render: dataVisibleRenderer, targets: ' . $key . ' }';
            elseif ( $coluna->type == TableHeaderItem::RENDERER_STATUS )
                $this->columnDefs[] = '{ render: dataStatusRenderer, targets: ' . $key . ' }';
            elseif ( $coluna->type == TableHeaderItem::RENDERER_TRUEFALSE )
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
            foreach ( $this->column as $col ) {
                $_class = $class . ' ' . $col->styleCol;
                if ( $col->funcao != null ) {
                    $funcao = $col->funcao;
                    if ( is_array ( $linha ) )
                        $html = $funcao( $linha, $col->chave );
                    else
                        $html = $funcao( $linha, $col->chave );

                    $this->printRow ( $html, $_class );
                } else {
                    if ( is_array ( $linha ) )
                        $this->printRow ( $linha[ $col->chave ], $_class );
                    else {
                        $chave = $col->chave;
                        $this->printRow ( $linha->$chave, $_class );
                    }
                }

            }
            echo '</tr>';
        }
        echo '</tbody>';
    }

    public function printRow ( $html, $class = '' )
    {
        echo '<td class=' . $class . '>';
        echo $html;
        echo '</td>';
    }

    public function close ( $processServer = false, $order = '' )
    {
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
        echo "</table>
              <script>
                  {$this->tableId} = null;
                  \$(document).ready( function() {
                      {$this->tableId} = \$( '#{$this->tableId}' ).DataTable({
                          {$ajaxConfig}
                          autoWidth   : false,
                          columns     : [ {$columnData} ],
                          columnDefs  : [ {$columnDefs} ]
                          {$processServerHtml}
                          {$order}
                      });
                  });
              </script>";

        if ( $this->clickRedirect )
            $this->onClickReditect ();
        elseif ( $this->clickModal)
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

    private function onClickModal(){

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