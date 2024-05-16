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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html;

/**
 * Class table
 *
 * @package local_kopere_dashboard\html
 */
class table {
    /** @var string */
    public $tableid;

    /**
     * table constructor.
     *
     * @param string $adicional
     */
    public function __construct($adicional = '') {
        $this->tableid = 'table_' . uniqid();
        echo "<table id='{$this->tableid}' class='table table-hover' width='100%' {$adicional} \>";
    }

    /** @var array */
    private $colunas = [];
    /** @var null */
    private $click = null;
    /** @var null */
    private $id = null;
    /** @var bool */
    private $isprint = false;

    /**
     * @param $exec
     * @param $chave
     */
    public function set_click($exec, $chave) {
        $this->click = [];
        $this->click['exec'] = $exec;
        $this->click['chave'] = $chave;
    }

    /**
     * @param $url
     * @param $chave
     */
    public function set_click_redirect($url, $chave) {
        $this->click = [];
        $this->click['chave'] = $chave;
        $this->click['exec'] = "document.location.href='{$url}'";
    }

    /**
     * @param $url
     * @param $chave
     */
    public function set_click_open($url, $chave) {
        $this->click = [];
        $this->click['chave'] = $chave;
        $this->click['exec'] = "window.open( '{$url}' )";
    }

    /**
     * @param $id
     */
    public function set_id($id) {
        $this->id = $id;
    }

    /**
     * @param $linha
     *
     * @return mixed|string
     */
    protected function get_click($linha) {
        if ($this->click == null) {
            return '';
        }

        $chaves = $this->click['chave'];

        if (!is_array($chaves)) {
            $chaves = [$chaves];
        }

        $exec = $this->click['exec'];
        foreach ($chaves as $chave) {

            if (is_array($linha)) {
                $valor = $linha[$chave];
            } else {
                $valor = $linha->$chave;
            }

            $exec = str_replace("{{$chave}}", $valor, $exec);
        }

        return $exec;
    }

    /**
     * @param      $title
     * @param null $chave
     * @param null $funcao
     * @param null $styleheader
     * @param null $stylecol
     */
    public function add_header($title, $chave = null, $funcao = null, $styleheader = null, $stylecol = null) {
        $coluna = new table_header_item();
        $coluna->chave = $chave;
        $coluna->title = $title;
        $coluna->funcao = $funcao;
        $coluna->style_header = $styleheader;
        $coluna->style_col = $stylecol;

        $this->colunas[] = $coluna;
    }

    /**
     * @param        $header
     * @param string $class
     */
    public function print_header($header, $class = '') {
        $this->colunas = [];
        echo '<thead>';
        echo "<tr class='{$class}'>";
        foreach ($header as $value) {
            echo "<th class='text-center' style='{$value->style_header}'>";
            if ($value->title == '') {
                echo "&nbsp;";
            } else {
                echo $value->title;
            }
            $this->colunas[] = $value;
            echo '</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo "\n";
        $this->isprint = true;
    }

    /**
     * @param        $linhas
     * @param string $class
     */
    public function set_row($linhas, $class = '') {
        if (!$this->isprint && count($this->colunas)) {
            $this->print_header($this->colunas);
        }

        if ($this->click != null) {
            echo '<tbody class="hover-pointer">';
        } else {
            echo '<tbody>';
        }
        foreach ($linhas as $linha) {

            $textid = "";
            if ($this->id) {
                $chaveid = $this->id;
                if (is_array($linha)) {
                    $valorid = $linha[$chaveid];
                } else {
                    $valorid = $linha->$chaveid;
                }
                $textid = "id='{$valorid}'";
            }

            if ($this->click != null) {
                echo "<tr {$textid} onClick='{$this->get_click($linha)}'>";
            } else {
                echo '<tr>';
            }
            foreach ($this->colunas as $col) {
                $class = $class . " {$col->style_col}";
                if ($col->funcao != null) {
                    $funcao = $col->funcao;
                    if (is_array($linha)) {
                        $html = $funcao($linha, $col->chave);
                    } else {
                        $html = $funcao($linha, $col->chave);
                    }

                    $this->print_row($html, $class);
                } else {
                    if (is_array($linha)) {
                        $this->print_row($linha[$col->chave], $class);
                    } else {
                        $chave = $col->chave;
                        $this->print_row($linha->$chave, $class);
                    }
                }

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
        echo "<td class='{$class}'>";
        echo $html;
        echo '</td>';
    }

    /**
     * @param bool $datatable
     */
    public function close($datatable = false, $extras = null) {
        global $PAGE;

        echo '</table>';
        if ($datatable) {

            $initparams = [
                "autoWidth" => false,
            ];
            if ($extras) {
                $initparams = array_merge($initparams, $extras);
            }

            $PAGE->requires->js_call_amd('local_kopere_dashboard/dataTables_init', 'init', [$this->tableid, $initparams]);
        }
    }
}
