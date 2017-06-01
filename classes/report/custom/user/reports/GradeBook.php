<?php
/**
 * User: Eduardo Kraus
 * Date: 27/05/17
 * Time: 10:18
 */

namespace local_kopere_dashboard\report\custom\user\reports;


use local_kopere_dashboard\report\custom\ReportInterface;

class GradeBook implements ReportInterface
{
    public $reportName = 'Notas dos alunos';

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
    public function isEnable ()
    {
        return true;
    }

    /**
     * @return void
     */
    public function generate ()
    {
        global $DB;



    }

    /**
     * @return void
     */
    public function listData ()
    {

    }
}