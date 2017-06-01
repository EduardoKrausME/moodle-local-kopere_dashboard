<?php
/**
 * User: Eduardo Kraus
 * Date: 16/05/17
 * Time: 00:09
 */

namespace local_kopere_dashboard;


class Modules
{
    public  function countAll ()
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT count(*) as num FROM {course_modules}' );

        return $count->num;
    }
}