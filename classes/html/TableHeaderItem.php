<?php

namespace local_kopere_dashboard\html;

function criaBotaoExcluir ( $list, $coluna )
{
    return '<a href="#" class="botaoExcluir" rel="' . $list[ $coluna ] . '">Excluir</a>';
}

class TableHeaderItem
{
    const TYPE_DATE     = 'date';
    const TYPE_CURRENCY = 'numeric';
    const TYPE_INT      = 'numeric';
    const TYPE_TEXT     = 'text';
    const TYPE_BYTES    = 'bytes';
    const TYPE_ACTION      = 'action';

    const RENDERER_DATE      = 'rendererdate';
    const RENDERER_VISIBLE   = 'visible';
    const RENDERER_TRUEFALSE = 'truefalse';
    const RENDERER_STATUS    = 'status';

    const btExcluir = 'criaBotaoExcluir';

    public $funcao;
    public $title       = '';
    public $type        = '';
    public $chave       = '';
    public $class       = '';
    public $styleHeader = '';
    public $styleCol    = '';

    public $cols = 0;
}
