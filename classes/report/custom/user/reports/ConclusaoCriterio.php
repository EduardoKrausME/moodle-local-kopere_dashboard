<?php

namespace local_kopere_dashboard\report\custom\user\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class ConclusaoCriterio implements ReportInterface
{

    public $reportName = 'Conclusão do curso com Critério';

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
        return false;
    }

    /**
     * @return void
     */
    public function generate ()
    {
        global $DB;
        $reportSql
            = 'SELECT u.username AS USER, 
                c.shortname AS course,
                t.timecompleted,
                p.module,p.moduleinstance
 --                ,
 --                CASE 
 --                WHEN (SELECT a.method FROM {course_completion_aggr_methd} AS a  WHERE (a.course = c.id AND a.criteriatype IS NULL) = 1) THEN "Any"
 --                ELSE "All"
 --                END AS aggregation,
 --                CASE 
 --                WHEN p.criteriatype = 1 THEN "Self"
 --                WHEN p.criteriatype = 2 THEN "By Date"
 --                WHEN p.criteriatype = 3 THEN "Unenrol Status"
 --                WHEN p.criteriatype = 4 THEN "Activity"
 --                WHEN p.criteriatype = 5 THEN "Duration"
 --                WHEN p.criteriatype = 6 THEN "Course Grade"
 --                WHEN p.criteriatype = 7 THEN "Approve by Role"
 --                WHEN p.criteriatype = 8 THEN "Previous Course"
 --                END AS criteriatype,
 --                CASE 
 --                WHEN p.criteriatype = 1 THEN "*"
 --                WHEN p.criteriatype = 2 THEN DATE_FORMAT(FROM_UNIXTIME(p.timeend),\'%Y-%m-%d\')
 --                WHEN p.criteriatype = 3 THEN t.unenroled
 --                WHEN p.criteriatype = 4 THEN 
 --                WHEN p.criteriatype = 5 THEN p.enrolperiod
 --                WHEN p.criteriatype = 6 THEN p.gradepass,t.gradefinal
 --                WHEN p.criteriatype = 7 THEN p.ROLE
 --                WHEN p.criteriatype = 8 THEN (SELECT pc.shortname FROM {course} AS pc WHERE pc.id = p.courseinstance)
 --                END AS criteriadetail 
                FROM {course_completion_crit_compl} AS t
                JOIN {user} AS u ON t.userid = u.id
                JOIN {course} AS c ON t.course = c.id
                JOIN {course_completion_criteria} AS p ON t.criteriaid = p.id';

        $report = $DB->get_records_sql ( $reportSql );

        echo '<pre>';
        print_r ( $report );
        echo '</pre>';
    }

    /**
     * @return void
     */
    public function listData ()
    {

    }

}