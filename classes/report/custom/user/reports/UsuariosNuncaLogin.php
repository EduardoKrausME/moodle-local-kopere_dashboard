<?php

namespace local_kopere_dashboard\report\custom\user\reports;

use local_kopere_dashboard\report\custom\ReportInterface;

class UsuariosNuncaLogin implements ReportInterface
{

    public $reportName = 'Os usuários registrados, que não fizeram login no Curso';


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
            = 'SELECT u.id AS ID,
                      ul.timeaccess,
                      u.firstname AS Firstname,
                      u.lastname AS Lastname,
                      u.email AS Email,
                      u.city AS City,
                      u.idnumber AS IDNumber,
                      u.phone1 AS Phone,
                      u.institution AS Institution,
                      u.lastaccess,
                      (SELECT r.name 
                         FROM  {user_enrolments} AS ue2
                         JOIN {enrol} AS e ON e.id = ue2.enrolid
                         JOIN {role}  AS r ON e.id = r.id
                        WHERE ue2.userid = u.id 
                          AND e.courseid = c.id ) AS RoleName
                 
                 FROM {user_enrolments} AS ue
                 JOIN {enrol}  AS e ON e.id = ue.enrolid
                 JOIN {course} AS c ON c.id = e.courseid
                 JOIN {user}   AS u ON u.id = ue.userid
                 LEFT JOIN {user_lastaccess} AS ul ON ul.userid = u.id
                WHERE ul.timeaccess IS NULL';

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
