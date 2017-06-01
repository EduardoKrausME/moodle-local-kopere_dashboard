<?php

namespace local_kopere_dashboard\report\custom\badge;

use local_kopere_dashboard\report\custom\InfoInterface;

class Info implements InfoInterface
{
    private $typename = 'Relatório de Emblemas';

    public function getTypeName ()
    {
        return $this->typename;
    }

    public function isEnable ()
    {
        global $DB;

        if ( $DB->get_records ( 'badge' ) )
            return true;

        return false;
    }
}

