<?php

namespace local_kopere_dashboard\report\custom\server;

use local_kopere_dashboard\report\custom\InfoInterface;

class Info implements InfoInterface
{
    private $typename = 'Relatório do sistema';

    public function getTypeName ()
    {
        return $this->typename;
    }

    public function isEnable ()
    {
        return true;
    }

}