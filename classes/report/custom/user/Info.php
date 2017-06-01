<?php

namespace local_kopere_dashboard\report\custom\user;

use local_kopere_dashboard\report\custom\InfoInterface;

class Info implements InfoInterface
{
    /**
     * @var string
     */
    private $typename = 'Relatório de usuários';

    /**
     * @return string
     */
    public function getTypeName ()
    {
        return $this->typename;
    }

    /**
     * @return bool
     */
    public function isEnable ()
    {
        return true;
    }
}