<?php

namespace local_kopere_dashboard\report\custom\enrol_cohort\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class CohortsByUser implements ReportInterface
{

    public $reportName = 'Coortes e os usuários';

    /**
     * @return string
     */
    public function name ()
    {
        return $this->reportName;
    }
    /**
     * @return boolean
     */
    public function isEnable(){
        return true;
    }

    /**
     * @return void
     */
    public function generate ( ){
        $reportSql
            = 'SELECT u.firstname, u.lastname, h.idnumber, h.name
                            FROM {cohort} AS h
                            JOIN {cohort_members} AS hm ON h.id = hm.cohortid
                            JOIN {user} AS u ON hm.userid = u.id
                            ORDER BY u.firstname';
    }

    /**
     * @return void
     */
    public function listData ( ){

    }
}