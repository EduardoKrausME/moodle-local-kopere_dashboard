<?php
/**
 * User: Eduardo Kraus
 * Date: 18/05/17
 * Time: 02:51
 */

namespace local_kopere_dashboard\report\custom\server\reports;

use local_kopere_dashboard\Benchmark;
use local_kopere_dashboard\report\custom\ReportInterface;

class ReportsPerformance implements ReportInterface
{
    public $reportName = 'Relatório de performance do Moodle';

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

    public function generate ()
    {
        $benchmark = new Benchmark();
        $benchmark->performance ();
    }

    public function listData ()
    {
    }
}