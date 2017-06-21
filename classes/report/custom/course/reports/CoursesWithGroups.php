<?php

namespace local_kopere_dashboard\report\custom\course\reports;

use local_kopere_dashboard\chartsjs\Pie;
use local_kopere_dashboard\Courses;
use local_kopere_dashboard\html\DataTable;
use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\report\custom\ReportInterface;

class CoursesWithGroups implements ReportInterface
{

    public $reportName = 'Cursos que possuem grupos ativados';

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

        $reportSql
            = 'SELECT c.id, c.fullname, c.shortname, g.name, c.visible, c.groupmode
                 FROM {course} AS c
                 JOIN {groups} AS g ON c.id = g.courseid
                WHERE c.groupmode > 0';

        $reports = $DB->get_records_sql ( $reportSql );

        $report = array();
        foreach ($reports as $item){

            if($item->groupmode == 0 )
                $item->groupname = get_string('groupsnone', 'group');
            elseif($item->groupmode == 1 )
                $item->groupname = get_string('groupsseparate', 'group');
            elseif($item->groupmode == 2 )
                $item->groupname = get_string('groupsvisible', 'group');

            $report[] = $item;
        }

        $table = new DataTable();
        $table->addHeader ( '#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px' );
        $table->addHeader ( 'Nome do Curso', 'fullname' );
        $table->addHeader ( 'Nome curto', 'shortname' );
        $table->addHeader ( 'Visível', 'visible', TableHeaderItem::RENDERER_VISIBLE );
        $table->addHeader ( 'Group Mode', 'groupname' );

        $table->setIsExport ( true );
        $table->setClickRedirect ( 'Courses::details&courseid={id}', 'id' );
        $table->printHeader ( '', false );
        $table->setRow ( $report );
        $table->close ( false );

        $pieData   = array();
        $pieData[] = new Pie( "Cursos com Grupos", count ( $report ) );
        $pieData[] = new Pie( "Cursos sem Grupos", Courses::countAll () - count ( $report ) );

        Pie::createRegular ( $pieData );
    }

    /**
     * @return void
     */
    public function listData ()
    {

    }
}