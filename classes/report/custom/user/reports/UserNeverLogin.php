<?php

namespace local_kopere_dashboard\report\custom\user\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class UserNeverLogin implements ReportInterface
{

    public $reportName = 'Usuários que nunca logaram';

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
        return false;
    }

    /**
     * @return void
     */
    public function generate ( ){
        global $DB;
        $reportSql
            = 'SELECT id, username, firstname, lastname, idnumber
                         FROM {user} AS u
                        WHERE u.deleted    = 0
                          AND u.lastlogin  = 0 
                          AND u.lastaccess = 0';

        $report = $DB->get_records_sql($reportSql);

        echo '<pre>';
        print_r ($report);
        echo '</pre>';
    }

    /**
     * @return void
     */
    public function listData ( ){

    }
}