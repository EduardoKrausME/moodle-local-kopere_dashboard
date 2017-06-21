<?php

namespace local_kopere_dashboard\html;

class Table
{
    function __construct ( $adicional = '' )
    {
        $this->tableId = 'table_' . uniqid ();
        echo '<table id="' . $this->tableId . '" class="table table-hover" width="100%" ';
        echo $adicional;
        echo '>';
    }

    private $colunas  = array();
    private $click    = null;
    private $id       = null;
    private $_isPrint = false;
    private $tableId;

    public function setClick ( $exec, $chave )
    {
        $this->click            = array();
        $this->click[ 'exec' ]  = $exec;
        $this->click[ 'chave' ] = $chave;
    }

    public function setClickRedirect ( $url, $chave )
    {
        $this->click            = array();
        $this->click[ 'chave' ] = $chave;
        $this->click[ 'exec' ]  = "document.location.href='" . $url . "'";
    }

    public function setId ( $id )
    {
        $this->id = $id;
    }

    protected function getClick ( $linha )
    {
        if ( $this->click == null )
            return '';

        $chaves = $this->click[ 'chave' ];

        if ( !is_array ( $chaves ) )
            $chaves = array( $chaves );

        $exec = $this->click[ 'exec' ];
        foreach ( $chaves as $chave ) {

            if ( is_array ( $linha ) )
                $valor = $linha[ $chave ];
            else
                $valor = $linha->$chave;

            $exec = str_replace ( $chave, $valor, $exec );
        }

        return $exec;
    }

    public function addHeader ( $title, $chave = null, $funcao = null, $styleHeader = null, $styleCol = null )
    {
        $coluna              = new TableHeaderItem();
        $coluna->chave       = $chave;
        $coluna->title       = $title;
        $coluna->funcao      = $funcao;
        $coluna->styleHeader = $styleHeader;
        $coluna->styleCol    = $styleCol;

        $this->colunas[] = $coluna;
    }

    public function printHeader ( $header, $class = '' )
    {
        $this->colunas = array();
        echo '<thead>';
        echo '<tr class="' . $class . '">';
        foreach ( $header as $key => $value ) {
            echo '<th class="text-center" style="' . $value->styleHeader . '">';
            if ( $value->title == '' )
                echo "&nbsp;";
            else
                echo $value->title;
            $this->colunas[] = $value;
            echo '</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";
        $this->_isPrint = true;
    }

    public function setRow ( $linhas, $class = '' )
    {
        if ( !$this->_isPrint && count ( $this->colunas ) )
            $this->printHeader ( $this->colunas );

        if ( $this->click != null )
            echo '<tbody class="hover-pointer">';
        else
            echo '<tbody>';
        foreach ( $linhas as $linha ) {

            $textId = "";
            if ( $this->id ) {
                $chaveId = $this->id;
                if ( is_array ( $linha ) )
                    $valorId = $linha[ $chaveId ];
                else
                    $valorId = $linha->$chaveId;
                $textId = 'id="' . $valorId . '"';
            }

            if ( $this->click != null )
                echo '<tr ' . $textId . ' onClick="' . $this->getClick ( $linha ) . '">';
            else
                echo '<tr>';
            foreach ( $this->colunas as $col ) {
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

    public function close ( $datatable = false )
    {
        echo '</table>';
        if ( $datatable ) {
            echo '<script type="text/javascript">
                    $(document).ready(function(){
                        var table = $("#' . $this->tableId . '").DataTable();
                    });
                  </script>';
        }
    }
}