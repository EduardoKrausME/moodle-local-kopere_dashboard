<?php
/**
 * User: Eduardo Kraus
 * Date: 15/05/17
 * Time: 23:50
 */

namespace local_kopere_dashboard\report;


use local_kopere_dashboard\util\BytesUtil;

class Files
{
    public static function countAllSpace ()
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT SUM(filesize) as space FROM {files}' );

        return $count->space;
    }

    public static function countAllCourseSpace ()
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT SUM(filesize) as space FROM {files} WHERE filearea=\'content\'' );

        return $count->space;
    }

    public static function countAllUsersSpace ()
    {
        global $DB;

        $count = $DB->get_record_sql ( 'SELECT SUM(filesize) as space FROM {files} WHERE component=\'user\'' );

        return $count->space;
    }

    public static function listSizesCourses ()
    {
        global $DB;

        $courses = $DB->get_records_sql ( 'SELECT id, fullname, shortname, visible, timecreated FROM {course} WHERE id > 1' );

        foreach ( $courses as $course ) {

            $coursesize = $DB->get_record_sql ( "
                    SELECT SUM( f.filesize ) AS coursesize 
                      FROM {files} f, {context} ctx
                     WHERE ctx.id           = f.contextid 
                       AND ctx.contextlevel = :contextlevel
                       AND ctx.instanceid   = :instanceid  
                  GROUP BY ctx.instanceid",
                array( 'contextlevel' => CONTEXT_COURSE,
                       'instanceid'   => $course->id
                ) );

            $modulessize = $DB->get_record_sql ( "
                    SELECT SUM( f.filesize ) AS modulessize 
                      FROM {course_modules} cm, {files} f, {context} ctx 
                     WHERE ctx.id = f.contextid 
                       AND ctx.instanceid   = cm.id 
                       AND ctx.contextlevel = :contextlevel 
                       AND cm.course        = :course 
                  GROUP BY cm.course",
                array('contextlevel' => CONTEXT_MODULE,
                      'course' => $course->id ) );

            $coursesizeVal  = isset( $coursesize->coursesize ) ? $coursesize->coursesize : 0;
            $modulessizeVal = isset( $modulessize->modulessize ) ? $modulessize->modulessize : 0;

            $courses[ $course->id ]->coursesize  = BytesUtil::sizeToByte ( $coursesizeVal );
            $courses[ $course->id ]->modulessize = BytesUtil::sizeToByte ( $modulessizeVal );

            $courses[ $course->id ]->allsize = $coursesizeVal + $modulessizeVal;
        }

        return $courses;
    }
}