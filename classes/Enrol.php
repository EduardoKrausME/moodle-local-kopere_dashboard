<?php
/**
 * User: Eduardo Kraus
 * Date: 16/05/17
 * Time: 04:10
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\util\Json;

class Enrol
{
    public function lastEnrol ()
    {
        global $DB;

        $sql
            = "SELECT ue.id, ra.roleid, e.courseid, c.fullname, ue.userid, ue.timemodified, ue.timeend, ue.status, e.enrol
                 FROM {user_enrolments} ue
                 JOIN {role_assignments} ra ON ue.userid = ra.userid
                 JOIN {enrol} e             ON e.id = ue.enrolid
                 JOIN {context} ctx         ON ctx.instanceid = e.courseid
                 JOIN {course} c            ON c.id = e.courseid
                WHERE ra.contextid = ctx.id
             GROUP BY e.courseid, ue.userid
             ORDER BY ue.timemodified DESC
                LIMIT 0, 10";

        return $DB->get_records_sql ( $sql );
    }

    public function ajaxdashboard ()
    {
        global $DB;

        $courseid = optional_param ( 'courseid', 0, PARAM_INT );

        $sql
            = "SELECT ue.userid AS id, concat(firstname, ' ', lastname) as nome, u.email, ue.status
		         FROM {user_enrolments} as ue
                    LEFT JOIN {user} as u ON u.id = ue.userid
                    LEFT JOIN {enrol} as e ON e.id = ue.enrolid
                    LEFT JOIN {course} as c ON c.id = e.courseid
                    LEFT JOIN {course_completions} as cc ON cc.timecompleted > 0 AND cc.course = e.courseid and cc.userid = ue.userid
		        WHERE c.id = :id AND u.id IS NOT NULL
		     GROUP BY ue.userid, e.courseid";

        $result = $DB->get_records_sql ( $sql, array( 'id' => $courseid ) );

        Json::encodeAndReturn ( $result );
    }


}