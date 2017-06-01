<?php

namespace local_kopere_dashboard\report\custom\user\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class UsuariosCouseComplete implements ReportInterface
{

    public $reportName = 'Usuários que concluíram curso';

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
            = 'SELECT u.username, 
                      c.shortname,  
                      p.timecompleted 
                 FROM {course_completions} AS p
                 JOIN {course} AS c ON p.course = c.id
                 JOIN {user}   AS u ON p.userid = u.id
                WHERE c.enablecompletion = 1
             ORDER BY u.username';

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