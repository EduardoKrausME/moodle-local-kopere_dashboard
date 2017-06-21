<?php

namespace local_kopere_dashboard\report\custom\user\reports;

use local_kopere_dashboard\chartsjs\Pie;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\ReportInterface;

class AlunosPorCurso implements ReportInterface
{
    public $reportName = 'Contagem de alunos em cada curso';

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
        global $DB;
        $reportSql
            = 'SELECT c.id, c.fullname, c.shortname, context.id AS contextid, COUNT(c.id) AS alunos
                 FROM {role_assignments} AS asg
                 JOIN {context} AS context ON asg.contextid = context.id AND context.contextlevel = 50
                 JOIN {user} AS u ON u.id = asg.userid
                 JOIN {course} AS c ON context.instanceid = c.id
                WHERE asg.roleid = 5 
             GROUP BY c.id';
        $report = $DB->get_records_sql($reportSql);

        $table = new DataTable();
        $table->addHeader ( '#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px' );
        $table->addHeader ( 'Nome do Curso', 'fullname' );
        $table->addHeader ( 'Nome curto', 'shortname' );
        $table->addHeader ( 'Alunos', 'alunos' );

        $table->setIsExport ( true );
        $table->setClickRedirect ( 'Courses::details&courseid={id}', 'id' );
        $table->printHeader ( '', false );
        $table->setRow ( $report );
        $table->close ( false );


        $pieData = array();
        foreach ( $report as $item )
            $pieData[] = new Pie(  $item->fullname, $item->alunos );

        Pie::createRegular ( $pieData );

    }

    /**
     * @return void
     */
    public function listData ( ){

    }
}

