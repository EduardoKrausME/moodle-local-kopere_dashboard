<?php

namespace local_kopere_dashboard\report\custom\enrol_cohort;

use local_kopere_dashboard\report\custom\InfoInterface;

class Info implements InfoInterface
{
    private $typename = 'Relatório de Coortes';

    public function getTypeName ()
    {
        return $this->typename;
    }

    public function isEnable ()
    {
        global $CFG;

        //if ( in_array ( "cohort", explode ( ',', $CFG->enrol_plugins_enabled ) ) )
        //    return true;
        //else
            return false;
    }
}