<?php

namespace local_kopere_dashboard\report\custom\course;

use local_kopere_dashboard\report\custom\InfoInterface;

class Info implements InfoInterface
{
    private $typename = 'Relatório de cursos';

    public function getTypeName ()
    {
        return $this->typename;
    }

    public function isEnable ()
    {
        return true;
    }

}