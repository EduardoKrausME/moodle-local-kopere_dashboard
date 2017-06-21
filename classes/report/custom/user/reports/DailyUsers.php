<?php
/**
 * User: Eduardo Kraus
 * Date: 27/05/17
 * Time: 10:37
 */

namespace local_kopere_dashboard\report\custom\user\reports;


use local_kopere_dashboard\chartsjs\Lines;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\report\custom\ReportInterface;

class DailyUsers implements ReportInterface
{
    public $reportName = 'Relatório diário de acessos dos usuários';

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

        $dias = optional_param ( 'dias', 10, PARAM_INT );

        $diasAr = array(
            array( 10, '10 dias' ),
            array( 20, '20 dias' ),
            array( 30, '30 dias' ),
            array( 60, '60 dias' ),
            array( 90, '90 dias' ),
        );


        $form = new Form( '#', 'inline' );
        $form->addInput (
            (new InputSelect())->setTitle ('Número de dias: ')
                ->setName ('dias')
                ->setValues ($diasAr, 0, 1)
                ->setValue ($dias));
        $form->closeAndAutoSubmitInput ( 'dias' );

        $graphicDataLogins = array();
        $graphicDataCourse = array();
        $graphicDataModule = array();

        for ( $i = $dias; $i > 0; $i-- ) {
            $d = date ( 'Y-m-d', strtotime ( "-$i day" ) );

            $timestampStart = strtotime ( "$d 00:00:00" );
            $timestampEnd   = strtotime ( "$d 23:59:59" );
            $x              = $d;

            $params = array(
                'timestamp_start' => $timestampStart,
                'timestamp_end'   => $timestampEnd
            );

            // Logins no dia
            $query
                    = "SELECT COUNT(DISTINCT userid) AS contagem 
                         FROM {logstore_standard_log} 
                        WHERE timecreated > :timestamp_start
                          AND timecreated < :timestamp_end 
                          AND target      = 'user' 
                          AND action      = 'loggedin'";
            $logins = $DB->get_record_sql ( $query, $params );

            // Acesso a cursos no dia
            $query
                    = "SELECT COUNT(DISTINCT id) AS contagem 
                         FROM {logstore_standard_log} 
                        WHERE timecreated > :timestamp_start
                          AND timecreated < :timestamp_end 
                          AND target      = 'course' 
                          AND action      = 'viewed'
                          AND userid      > 2 ";
            $course = $DB->get_record_sql ( $query, $params );

            // Acesso a módulos no dia
            $query
                    = "SELECT COUNT(DISTINCT id) AS contagem 
                         FROM {logstore_standard_log} 
                        WHERE timecreated > :timestamp_start
                          AND timecreated < :timestamp_end 
                          AND component   LIKE 'mod\\_%' 
                          AND action      = 'viewed'
                          AND userid      > 2 ";
            $module = $DB->get_record_sql ( $query, $params );

            $graphicDataLogins[] = array( 'x' => $x, 'y' => $logins->contagem );
            $graphicDataCourse[] = array( 'x' => $x, 'y' => $course->contagem );
            $graphicDataModule[] = array( 'x' => $x, 'y' => $module->contagem );
        }

        Lines::createMultiLines ( array(
            array( 'key' => 'Logins neste Moodle (unicos)', 'values' => $graphicDataLogins, 'color' => '#ff7f0e' ),
            array( 'key' => 'Visualização de Cursos      ', 'values' => $graphicDataCourse, 'color' => '#2ca02c' ),
            array( 'key' => 'Visualização de Módulos     ', 'values' => $graphicDataModule, 'color' => '#7777ff' )
        ), '', 'Acessos' );

    }

    /**
     * @return void
     */
    public function listData ()
    {

    }
}