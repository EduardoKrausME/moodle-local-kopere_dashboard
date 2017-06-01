<?php
/**
 * User: Eduardo Kraus
 * Date: 13/05/17
 * Time: 13:29
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\report\custom\InfoInterface;
use local_kopere_dashboard\report\custom\ReportInterface;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Json;

class Reports
{

    /**
     * @return array
     */
    public static function globalMenus ()
    {
        global $CFG;

        $types = glob ( $CFG->dirroot . '/local/kopere_dashboard/classes/report/custom/*/Info.php' );

        $menus = array();

        if ( strpos ( $_SERVER[ 'QUERY_STRING' ], 'Reports::' ) === 0 ) {

            foreach ( $types as $typePath ) {
                $dirname = pathinfo ( $typePath, PATHINFO_DIRNAME );

                preg_match ( "/classes\/report\/custom\/(.*?)\/Info\.php/", $typePath, $typeInfo );
                $icon = str_replace ( $CFG->dirroot, '', $dirname );

                $className = "local_kopere_dashboard\\report\\custom\\{$typeInfo[1]}\\Info";
                /** @var InfoInterface $class */
                $class = new $className();

                if ( !$class->isEnable () )
                    continue;

                $menus[] = array( 'Reports::dashboard&type=' . $typeInfo[ 1 ],
                                  $class->getTypeName (),
                                  "{$CFG->wwwroot}{$icon}/icon.svg"
                );
            }
        }

        return $menus;
    }

    public function dashboard ()
    {
        global $CFG;

        DashboardUtil::startPage ( 'Relatórios' );

        echo '<div class="element-box">';
        echo '<h2>Relatórios</h2>';

        $type = optional_param ( 'type', '*', PARAM_TEXT );

        $types = glob ( "{$CFG->dirroot}/local/kopere_dashboard/classes/report/custom/{$type}/Info.php" );

        foreach ( $types as $type ) {
            $dirname = pathinfo ( $type, PATHINFO_DIRNAME );

            preg_match ( "/classes\/report\/custom\/(.*?)\/Info\.php/", $type, $typeInfo );
            $icon = str_replace ( $CFG->dirroot, '', $dirname );

            $className = "local_kopere_dashboard\\report\\custom\\{$typeInfo[1]}\\Info";
            /** @var InfoInterface $class */
            $class = new $className();

            if ( !$class->isEnable () )
                continue;

            echo "<h3><img src='{$CFG->wwwroot}{$icon}/icon.svg' alt='Icon' height='23' width='23' > " .
                $class->getTypeName () . "</h3>";

            $reports = glob ( $dirname . '/reports/*.php' );

            foreach ( $reports as $report ) {
                preg_match ( "/classes\/report\/custom\/(.*?)\/reports\/(.*?)\.php/", $report, $reportInfo );

                $className = "local_kopere_dashboard\\report\\custom\\{$reportInfo[1]}\\reports\\{$reportInfo[2]}";
                /** @var ReportInterface $class */
                $class = new $className();

                if( $class->isEnable() )
                    echo "<h4 style='padding-left: 31px;'><a href='?Reports::loadReport&type={$reportInfo[1]}&report={$reportInfo[2]}'>{$class->reportName}</a></h4>";
            }
        }


        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function loadReport ()
    {
        global $CFG;

        $type   = optional_param ( 'type', '', PARAM_TEXT );
        $report = optional_param ( 'report', '', PARAM_TEXT );

        $reportFile = $CFG->dirroot . "/local/kopere_dashboard/classes/report/custom/$type/reports/$report.php";

        if ( !file_exists ( $reportFile ) )
            Header::notfound ( 'Relatório não localizado!' );

        $className = "local_kopere_dashboard\\report\\custom\\$type\\reports\\$report";
        /** @var ReportInterface $class */
        $class = new $className();


        DashboardUtil::startPage ( array(
            array( '?Reports::dashboard', 'Relatórios' ),
            $class->name ()
        ) );
        echo '<div class="element-box">';

        $class->generate ();


        echo '</div>';
        DashboardUtil::endPage ();
    }

    public function listData ()
    {
        global $CFG;

        $type   = optional_param ( 'type', '', PARAM_TEXT );
        $report = optional_param ( 'report', '', PARAM_TEXT );

        $reportFile = $CFG->dirroot . "/local/kopere_dashboard/classes/report/custom/$type/reports/$report.php";

        if ( !file_exists ( $reportFile ) )
            Header::notfound ( 'Relatório não localizado!' );

        $className = "local_kopere_dashboard\\report\\custom\\$type\\reports\\$report";
        /** @var ReportInterface $class */
        $class = new $className();

        $class->listData ();
    }


    public function statusAll ()
    {
        global $DB;

        $sql
            = "SELECT ue.id,
                        ue.timecreated AS enrolled,
                        ue.timeend,
                        ul.timeaccess,
                        ROUND(((g.finalgrade/g.rawgrademax)*100), 0) AS grade,
                        c.enablecompletion,
                        cc.timecompleted AS complete,
                        u.id AS uid,
                        u.email,
                        u.phone1,
                        u.phone2,
                        u.institution,
                        u.department,
                        u.address,
                        u.city,
                        u.country,
                        u.firstname,
                        u.lastname,
                        e.enrol AS enrols,
                        c.id AS cid,
                        c.fullname AS course,
                        c.timemodified AS start_date, 
                        l.timespend, 
                        l.visits
                FROM {user_enrolments} ue
                        LEFT JOIN {enrol} e ON e.id = ue.enrolid
                        LEFT JOIN {user} u ON u.id = ue.userid
                        LEFT JOIN {course} c ON c.id = e.courseid
                        LEFT JOIN {user_lastaccess} ul ON ul.courseid = c.id AND ul.userid = u.id
                        LEFT JOIN {course_completions} cc ON cc.course = e.courseid AND cc.userid = ue.userid
                        LEFT JOIN {grade_items} gi ON gi.itemtype = 'course' AND gi.courseid = e.courseid
                LEFT JOIN {grade_grades} g ON g.userid = u.id AND g.itemid = gi.id AND g.finalgrade IS NOT NULL
                         LEFT JOIN (SELECT t.userid,t.courseid, SUM(t.timespend) AS timespend, SUM(t.visits) AS visits FROM
                                                        {local_intelliboard_tracking} t GROUP BY t.courseid, t.userid) l ON l.courseid = c.id AND l.userid = u.id
                WHERE ue.id > 0  AND ue.timecreated AND u.deleted = 0 AND u.suspended = 0 AND u.username <> 'guest' AND c.visible = 1 AND ue.status = 0 AND e.status = 0";

        $result = $DB->get_records_sql ( $sql );

        Json::encodeAndReturn ( $result );
    }

    public function learnerStatusSummary ()
    {
        global $DB;
        $sql
            = "SELECT u.id,
                                u.firstname,
                                u.lastname,
                                u.email,
                                u.lastaccess,
                                u.timecreated as registered,
                                round(AVG((g.finalgrade/g.rawgrademax)*100), 2) as grade,
                                COUNT(DISTINCT ctx.instanceid) as courses,
                                COUNT(DISTINCT cc.id) as completed_courses,
                                (SELECT COUNT(id) FROM {course_modules_completion} WHERE completionstate = 1 AND userid = u.id) AS completed_activities
                                , lit.timespend, lit.visits
                        FROM {user} u
                                LEFT JOIN {role_assignments} ra ON ra.userid = u.id
                                LEFT JOIN {context} ctx ON ctx.id = ra.contextid AND ctx.contextlevel=50
                                LEFT JOIN {course_completions} cc ON cc.course = ctx.instanceid AND cc.userid = u.id AND cc.timecompleted > 0
                                LEFT JOIN {grade_items} gi ON gi.courseid = ctx.instanceid AND gi.itemtype = 'course'
                                LEFT JOIN {grade_grades} g ON g.itemid = gi.id AND g.userid = u.id AND g.finalgrade IS NOT NULL
                                 LEFT JOIN (SELECT userid, SUM(timespend) as timespend, SUM(visits) as visits FROM
                                                        {local_intelliboard_tracking}
                                                WHERE courseid > 0 GROUP BY userid) as lit ON lit.userid = u.id
                        WHERE u.id > 0  AND u.deleted = 0 AND u.suspended = 0 AND u.username <> 'guest' GROUP BY u.id , lit.timespend, lit.visits";

        $result = $DB->get_records_sql ( $sql );

        Json::encodeAndReturn ( $result );
    }

    public function loginStatistics ()
    {
        global $DB;
        $sql
            = "SELECT u.id,
                                u.firstname,
                                u.lastname,
                                u.email,
                                u.phone1,
                                u.phone2,
                                u.institution,
                                u.department,
                                u.address,
                                u.city,
                                u.country,
                                u.timecreated,
                                u.firstaccess,
                                u.lastaccess,
                                lit1.timespend_site,
                                lit2.timespend_courses,
                                lit3.timespend_activities

                        FROM {user} u
                                LEFT JOIN (SELECT userid, SUM(timespend) as timespend_site FROM {local_intelliboard_tracking} GROUP BY userid) as lit1 ON lit1.userid = u.id
                                LEFT JOIN (SELECT userid, SUM(timespend) as timespend_courses FROM {local_intelliboard_tracking} WHERE courseid > 0 GROUP BY userid) as lit2 ON lit2.userid = u.id
                                LEFT JOIN (SELECT userid, SUM(timespend) as timespend_activities FROM {local_intelliboard_tracking} WHERE page='module' GROUP BY userid) as lit3 ON lit3.userid = u.id

                        WHERE u.id > 0  AND u.deleted = 0 AND u.suspended = 0 AND u.username <> 'guest'
                        GROUP BY u.id";

        $result = $DB->get_records_sql ( $sql );

        Json::encodeAndReturn ( $result );
    }

    public function overdueLearners ()
    {
        //global $DB;
        //$sql = "";
        //
        //$result = $DB->get_records_sql ( $sql );
        //
        //Json::encodeAndReturn ( $result );
    }

    public function loginsLogouts ()
    {
        global $DB;
        $sql
            = "SELECT l1.id,
               u.firstname,
               u.lastname,
               u.timecreated AS registered,
               l1.userid,
               l1.timecreated as loggedin,
               l2.timecreated as loggedout

                        FROM {logstore_standard_log} l1
                LEFT JOIN {user} u ON u.id = l1.userid
                LEFT JOIN {logstore_standard_log} l2 ON l2.id = (
                    SELECT l3.id
                    FROM {logstore_standard_log} l3
                    WHERE l3.action = 'loggedout' AND l3.id > l1.id AND l3.userid = l1.userid
                    ORDER BY l3.id ASC
                    LIMIT 1)
                        WHERE l1.action = 'loggedin'  AND u.deleted = 0 AND u.suspended = 0 AND u.username <> 'guest'
                        GROUP BY l1.id
                        
                        LIMIT 500";

        $result = $DB->get_records_sql ( $sql );

        Json::encodeAndReturn ( $result );
    }

    public function courseContentLogs ()
    {
        global $DB;
        $sql
            = "SELECT l.id,
                    l.courseid,
                    l.userid,
                    l.contextinstanceid AS cmid,
                    l.objecttable,
                    l.origin,
                    l.ip,
                    c.fullname AS course,
                    u.email,
                    CONCAT(u.firstname, ' ', u.lastname) AS user,
                    l.timecreated
                    , CASE  WHEN m.name='assign' THEN (SELECT name FROM {assign} WHERE id = cm.instance) WHEN m.name='book' THEN (SELECT name FROM {book} WHERE id = cm.instance) WHEN m.name='chat' THEN (SELECT name FROM {chat} WHERE id = cm.instance) WHEN m.name='choice' THEN (SELECT name FROM {choice} WHERE id = cm.instance) WHEN m.name='data' THEN (SELECT name FROM {data} WHERE id = cm.instance) WHEN m.name='feedback' THEN (SELECT name FROM {feedback} WHERE id = cm.instance) WHEN m.name='folder' THEN (SELECT name FROM {folder} WHERE id = cm.instance) WHEN m.name='forum' THEN (SELECT name FROM {forum} WHERE id = cm.instance) WHEN m.name='glossary' THEN (SELECT name FROM {glossary} WHERE id = cm.instance) WHEN m.name='imscp' THEN (SELECT name FROM {imscp} WHERE id = cm.instance) WHEN m.name='label' THEN (SELECT name FROM {label} WHERE id = cm.instance) WHEN m.name='lesson' THEN (SELECT name FROM {lesson} WHERE id = cm.instance) WHEN m.name='lti' THEN (SELECT name FROM {lti} !
 WHERE id = cm.instance) WHEN m.name='page' THEN (SELECT name FROM {page} WHERE id = cm.instance) WHEN m.name='quiz' THEN (SELECT name FROM {quiz} WHERE id = cm.instance) WHEN m.name='resource' THEN (SELECT name FROM {resource} WHERE id = cm.instance) WHEN m.name='scorm' THEN (SELECT name FROM {scorm} WHERE id = cm.instance) WHEN m.name='survey' THEN (SELECT name FROM {survey} WHERE id = cm.instance) WHEN m.name='url' THEN (SELECT name FROM {url} WHERE id = cm.instance) WHEN m.name='wiki' THEN (SELECT name FROM {wiki} WHERE id = cm.instance) WHEN m.name='workshop' THEN (SELECT name FROM {workshop} WHERE id = cm.instance) WHEN m.name='avapecexterno' THEN (SELECT name FROM {avapecexterno} WHERE id = cm.instance) ELSE 'NONE' END AS activity
                                FROM {logstore_standard_log} l
                    LEFT JOIN {course} c ON c.id = l.courseid
                    LEFT JOIN {user} u ON u.id = l.userid
                    LEFT JOIN {modules} m ON m.name = l.objecttable
                    LEFT JOIN {course_modules} cm ON cm.id = l.contextinstanceid

                                WHERE l.component LIKE '%mod_%'  AND l.courseid = :lcourseid11  AND u.deleted = 0 AND u.suspended = 0 AND u.username <> 'guest' AND c.visible = 1 AND cm.visible = 1
                                
                                LIMIT 500";

        $result = $DB->get_records_sql ( $sql );

        Json::encodeAndReturn ( $result );
    }
}