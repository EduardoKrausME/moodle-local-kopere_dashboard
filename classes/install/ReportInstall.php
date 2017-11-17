<?php
/**
 * User: Eduardo Kraus
 * Date: 17/07/17
 * Time: 21:28
 */

namespace local_kopere_dashboard\install;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\TableHeaderItem;
use local_kopere_dashboard\vo\kopere_dashboard_reportcat;
use local_kopere_dashboard\vo\kopere_dashboard_reports;

/**
 * Class ReportInstall
 *
 * @package local_kopere_dashboard\install
 */
class ReportInstall {
    /**
     *
     */
    public static function createCategores() {
        global $CFG;

        $CFG->debugdisplay = false;

        $reportcat = kopere_dashboard_reportcat::createNew();
        $reportcat->title = get_string('reports_reportcat_badge', 'local_kopere_dashboard');
        $reportcat->type = 'badge';
        $reportcat->image = 'assets/reports/badge.svg';
        $reportcat->enable = 1;
        $reportcat->enablesql = "SELECT id as status FROM {badge} LIMIT 1";
        self::reportCatInsert($reportcat);

        $reportcat = kopere_dashboard_reportcat::createNew();
        $reportcat->title = get_string('reports_reportcat_courses', 'local_kopere_dashboard');
        $reportcat->type = 'courses';
        $reportcat->image = 'assets/reports/courses.svg';
        $reportcat->enable = 1;
        self::reportCatInsert($reportcat);

        $reportcat = kopere_dashboard_reportcat::createNew();
        $reportcat->title = get_string('reports_reportcat_enrol_cohort', 'local_kopere_dashboard');
        $reportcat->type = 'enrol_cohort';
        $reportcat->image = 'assets/reports/enrol_cohort.svg';
        $reportcat->enable = 1;
        if ($CFG->dbtype == 'pgsql') {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE \"name\" LIKE 'enrol_plugins_enabled' AND \"value\" LIKE '%cohort%' LIMIT 1";
        } else {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE `name` LIKE 'enrol_plugins_enabled' AND `value` LIKE '%cohort%' LIMIT 1";
        }
        self::reportCatInsert($reportcat);

        $reportcat = kopere_dashboard_reportcat::createNew();
        $reportcat->title = get_string('reports_reportcat_enrol_guest', 'local_kopere_dashboard');
        $reportcat->type = 'enrol_guest';
        $reportcat->image = 'assets/reports/enrol_guest.svg';
        $reportcat->enable = 1;
        if ($CFG->dbtype == 'pgsql') {
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE \"name\" LIKE 'enrol_plugins_enabled' AND \"value\" LIKE '%guest%' LIMIT 1";
        }else{
            $reportcat->enablesql = "SELECT id as status FROM {config} WHERE `name` LIKE 'enrol_plugins_enabled' AND `value` LIKE '%guest%' LIMIT 1";
        }
        self::reportCatInsert($reportcat);

        $reportcat = kopere_dashboard_reportcat::createNew();
        $reportcat->title = get_string('reports_reportcat_server', 'local_kopere_dashboard');
        $reportcat->type = 'server';
        $reportcat->image = 'assets/reports/server.svg';
        $reportcat->enable = 1;
        self::reportCatInsert($reportcat);

        $reportcat = kopere_dashboard_reportcat::createNew();
        $reportcat->title = get_string('reports_reportcat_user', 'local_kopere_dashboard');
        $reportcat->type = 'user';
        $reportcat->image = 'assets/reports/user.svg';
        $reportcat->enable = 1;
        self::reportCatInsert($reportcat);
    }

    /**
     *
     */
    public static function createReports() {
        global $DB, $CFG;

        $table = new ReportInstall();

        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'badge'));
        $report->reportkey = 'badge-1';
        $report->title = get_string('reports_report_badge-1', 'local_kopere_dashboard');
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = ' SELECT b.id, b."name", b.description, b.type, b.status,
                                      (SELECT COUNT(*) FROM {badge_issued} d WHERE d.badgeid = b.id )AS students
                                 FROM {badge} b';
        }else{
            $report->reportsql = ' SELECT b.id, b.name, b.description, b.type, b.status,
                                      (SELECT COUNT(*) FROM {badge_issued} d WHERE d.badgeid = b.id )AS students
                                 FROM {badge} b';
        }
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'name'),
            $table->addHeader(get_string('reports_context', 'local_kopere_dashboard'), 'context'),
            $table->addHeader(get_string('userenrolment_status_active', 'local_kopere_dashboard'), 'statustext', TableHeaderItem::RENDERER_STATUS),
            $table->addHeader(get_string('courses_enrol', 'local_kopere_dashboard'), 'students', TableHeaderItem::TYPE_INT)
        );
        $report->foreach = 'if ( $item->status == 0 || $item->status == 2 ) {
                                $item->statustext = false;
                            } else if ( $item->status == 1 || $item->status == 3 ) {
                                $item->statustext = false;
                            } else if ( $item->status == 4 ) {
                                $item->statustext = "-";
                            }
                            if ( $item->type == 1 ) {
                                $item->context = \'Sistema\';
                            }
                            if ( $item->type == 1 ) {
                                $item->context = \'Curso\';
                            }';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'badge'));
        $report->reportkey = 'badge-2';
        $report->title = get_string('reports_report_badge-2', 'local_kopere_dashboard');
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = ' SELECT d.id, u.id AS userid, ' . get_all_user_name_fields(true, 'u') . ', u.lastname, b."name" AS badgename, 
                                      t.criteriatype, t.method, d.dateissued,
                                      (SELECT c.shortname FROM {course} c WHERE c.id = b.courseid) as course
                                 FROM {badge_issued}   d
                                 JOIN {badge}          b ON d.badgeid = b.id
                                 JOIN {user}           u ON d.userid  = u.id
                                 JOIN {badge_criteria} t ON b.id      = t.badgeid
                                WHERE t.criteriatype != 0
                              ORDER BY u.username';
        }else{
            $report->reportsql = ' SELECT d.id, u.id AS userid, ' . get_all_user_name_fields(true, 'u') . ', u.lastname, b.name AS badgename, 
                                      t.criteriatype, t.method, d.dateissued,
                                      (SELECT c.shortname FROM {course} c WHERE c.id = b.courseid) as course
                                 FROM {badge_issued}   d
                                 JOIN {badge}          b ON d.badgeid = b.id
                                 JOIN {user}           u ON d.userid  = u.id
                                 JOIN {badge_criteria} t ON b.id      = t.badgeid
                                WHERE t.criteriatype != 0
                              ORDER BY u.username';
        }
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'name'),
            $table->addHeader(get_string('reports_badgename', 'local_kopere_dashboard'), 'badgename'),
            $table->addHeader(get_string('reports_criteriatype', 'local_kopere_dashboard'), 'criteriatype'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'course'),
            $table->addHeader(get_string('reports_dateissued', 'local_kopere_dashboard'), 'dateissued', TableHeaderItem::RENDERER_DATE)
        );
        $report->foreach = '$item->criteriatype = get_string(\'criteria_\' . $item->criteriatype, \'badges\');
                            $item->name = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);



        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-1';
        $report->title = get_string('reports_report_courses-1', 'local_kopere_dashboard');
        $report->prerequisit = 'listCourses';
        $report->reportsql = ' SELECT ue.id,
                                      u.id AS userid,' . get_all_user_name_fields(true, 'u') . ', u.email,
                
                                      c.id AS courseid,
                                      c.fullname,
                                      ue.timecreated,
                
                                      IFNULL((SELECT COUNT(gg.finalgrade)
                                        FROM {grade_grades}  gg
                                        JOIN {grade_items}  gi ON gg.itemid=gi.id
                                        WHERE gi.courseid=c.id
                                         AND gg.userid=u.id
                                         AND gi.itemtype=\'mod\'
                                         GROUP BY u.id,c.id),\'0\') AS \'activities_completed\',
                
                                      IFNULL((SELECT COUNT( gi.itemname )
                                        FROM {grade_items}  gi
                                        WHERE gi.courseid = c.id
                                         AND gi.itemtype=\'mod\'), \'0\') AS \'activities_assigned\',
                
                                      (
                                          SELECT IF(activities_assigned!=\'0\', (
                                              SELECT IF( activities_completed = activities_assigned,
                                              (
                                                  SELECT CONCAT(\'100% completo\',FROM_UNIXTIME(MAX(log.time),\'%m/%d/%Y\'))
                                                    FROM {log} log
                                                   WHERE log.course = c.id
                                                     AND log.userid = u.id
                                              ),
                                              (
                                                  SELECT CONCAT(IFNULL(ROUND((activities_completed)/(activities_assigned)*100,0), \'0\'),\'% complete\')))), \'n/a\')
                                      ) AS \'course_completed\'
                
                                    FROM {user} u
                                    JOIN {user_enrolments}  ue  ON ue.userid = u.id
                                    JOIN {enrol}            e   ON e.id = ue.enrolid
                                    JOIN {course}           c   ON c.id = e.courseid
                                    JOIN {context}          ctx ON ctx.instanceid = c.id AND ctx.instanceid = c.id
                                    JOIN {role_assignments} ra  ON ra.contextid = ctx.id AND ra.userid = u.id
                                    JOIN {role}             r   ON r.id = e.roleid
                
                                    WHERE ra.roleid = 5
                                      AND c.id      = :courseid
                                  GROUP BY u.id, c.id';
        $report->columns = array(
            $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('courses_student_email', 'local_kopere_dashboard'), 'email'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('reports_coursecreated', 'local_kopere_dashboard'), 'timecreated', TableHeaderItem::RENDERER_DATE),
            $table->addHeader(get_string('reports_activitiescomplete', 'local_kopere_dashboard'), 'activities_completed', TableHeaderItem::TYPE_INT),
            $table->addHeader(get_string('reports_activitiesassigned', 'local_kopere_dashboard'), 'activities_assigned', TableHeaderItem::TYPE_INT),
            $table->addHeader(get_string('reports_coursecompleted', 'local_kopere_dashboard'), 'course_completed')
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array(
            'columns' => $report->columns,
            'header' => array(
                $table->addInfoHeader(get_string('reports_datastudents', 'local_kopere_dashboard'), 3),
                $table->addInfoHeader(get_string('reports_datacourses', 'local_kopere_dashboard'), 5)
            )
        ));
        if ($CFG->dbtype != 'pgsql') {
            self::reportInsert($report);
        }


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-2';
        $report->title = get_string('reports_report_courses-2', 'local_kopere_dashboard');
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = ' SELECT concat(c.id,g.id), c.id, c.fullname, c.shortname, g."name", c.visible, c.groupmode
                                 FROM {course} c
                                 JOIN {groups} g ON c.id = g.courseid
                                WHERE c.groupmode > 0';
        }else{
            $report->reportsql = ' SELECT concat(c.id,g.id), c.id, c.fullname, c.shortname, g.name, c.visible, c.groupmode
                                 FROM {course} c
                                 JOIN {groups} g ON c.id = g.courseid
                                WHERE c.groupmode > 0';
        }
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('courses_visible', 'local_kopere_dashboard'), 'visible', TableHeaderItem::RENDERER_VISIBLE),
            $table->addHeader(get_string('reports_groupnode', 'local_kopere_dashboard'), 'groupname'),
            $table->addHeader(get_string('reports_groupname', 'local_kopere_dashboard'), 'name')
        );
        $report->foreach = 'if ($item->groupmode == 0) {
                                $item->groupname = get_string(\'groupsnone\', \'group\');
                            } else if ($item->groupmode == 1) {
                                $item->groupname = get_string(\'groupsseparate\', \'group\');
                            } else if ($item->groupmode == 2) {
                                $item->groupname = get_string(\'groupsvisible\', \'group\');
                            }';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-3';
        $report->title = get_string('reports_report_courses-3', 'local_kopere_dashboard');
        $report->prerequisit = 'listCourses';
        $report->reportsql = 'local_kopere_dashboard\\report\\custom\\ReportsCourseAccess';
        $report->columns = '';
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-4';
        $report->title = get_string('reports_report_courses-4', 'local_kopere_dashboard');
        $report->prerequisit = 'listCourses';
        $report->reportsql = 'local_kopere_dashboard\\report\\custom\\ReportsCourseAccessGrade';
        $report->columns = '';
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-5';
        $report->title = get_string('reports_report_courses-5', 'local_kopere_dashboard');
        $report->prerequisit = 'listCourses';
        $report->reportsql = 'local_kopere_dashboard\\report\\custom\\ReportsCourseLastAccess';
        $report->columns = '';
        self::reportInsert($report);






        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'enrol_cohort'));
        $report->reportkey = 'enrol_cohort-1';
        $report->title = get_string('reports_report_enrol_cohort-1', 'local_kopere_dashboard');
        $report->reportsql = ' SELECT u.id, ' . get_all_user_name_fields(true, 'u') . ', h.idnumber, h.name
                                 FROM {cohort} h
                                 JOIN {cohort_members} hm ON h.id = hm.cohortid
                                 JOIN {user} u ON hm.userid = u.id
                               ORDER BY u.firstname';
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('reports_cohort', 'local_kopere_dashboard'), 'name')
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'enrol_guest'));
        $report->reportkey = 'enrol_guest-1';
        $report->title = get_string('reports_report_enrol_guest-1', 'local_kopere_dashboard');
        $report->reportsql = " SELECT u.id, " . get_all_user_name_fields(true, 'u') . ", u.id AS userid, lsl.timecreated, lsl.ip
                                 FROM {logstore_standard_log} lsl
                                 JOIN {user}                  u   ON u.id = lsl.userid
                                WHERE lsl.action LIKE 'loggedin'
                                  AND u.id = 1
                                  AND lsl.target LIKE 'user'";
        $report->columns = array(
            $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('reports_lastlogin', 'local_kopere_dashboard'), 'timecreated', TableHeaderItem::RENDERER_DATE),
            $table->addHeader('IP', 'ip')
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'server'));
        $report->reportkey = 'server-1';
        $report->title = get_string('reports_report_server-1', 'local_kopere_dashboard');
        $report->reportsql = ' SELECT c.id, c.fullname, c.shortname, c.visible, c.timecreated,
                                      (SELECT SUM( f.filesize )
                                         FROM {files} f, {context} ctx
                                        WHERE ctx.id           = f.contextid
                                          AND ctx.contextlevel = 50
                                          AND ctx.instanceid   = c.id
                                      GROUP BY ctx.instanceid) as coursesize,
                                      (SELECT SUM( f.filesize ) AS modulessize
                                         FROM {course_modules} cm, {files} f, {context} ctx
                                        WHERE ctx.id = f.contextid
                                          AND ctx.instanceid   = cm.id
                                          AND ctx.contextlevel = 70
                                          AND cm.course        = c.id
                                     GROUP BY cm.course) as modulessize
                                 FROM {course} c
                                WHERE c.id > 1';
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('courses_shortname', 'local_kopere_dashboard'), 'shortname'),
            $table->addHeader(get_string('courses_visible', 'local_kopere_dashboard'), 'visible', TableHeaderItem::RENDERER_VISIBLE),
            $table->addHeader(get_string('reports_coursesize', 'local_kopere_dashboard'), 'coursesize', TableHeaderItem::TYPE_BYTES),
            $table->addHeader(get_string('reports_modulessize', 'local_kopere_dashboard'), 'modulessize', TableHeaderItem::TYPE_BYTES)
        );
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-1';
        $report->title = get_string('reports_report_user-1', 'local_kopere_dashboard');
        $report->reportsql = ' SELECT DISTINCT c.id, c.fullname, c.shortname, ctx.id AS contextid, 
                                       (SELECT COUNT(userid) FROM {role_assignments} WHERE contextid = asg.contextid GROUP BY contextid) AS alunos
                                 FROM {role_assignments} asg
                                 JOIN {context}          ctx ON asg.contextid = ctx.id
                                 JOIN {course}           c   ON ctx.instanceid = c.id
                                WHERE asg.roleid       = 5
                                  AND ctx.contextlevel = 50 ';
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('courses_shortname', 'local_kopere_dashboard'), 'shortname'),
            $table->addHeader(get_string('courses_enrol', 'local_kopere_dashboard'), 'alunos')
        );
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-2';
        $report->title = get_string('reports_report_user-2', 'local_kopere_dashboard');
        $report->reportsql = ' SELECT concat(u.id, p.id), u.id, ' . get_all_user_name_fields(true, 'u') . ', c.fullname, c.shortname,
                                      t.timecompleted, p.module, p.moduleinstance
                                 FROM {course_completion_crit_compl} t
                                 JOIN {user}    u ON t.userid = u.id
                                 JOIN {course}  c ON t.course = c.id
                                 JOIN {course_completion_criteria} p ON t.criteriaid = p.id';
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('courses_shortname', 'local_kopere_dashboard'), 'shortname')
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-3';
        $report->title = get_string('reports_report_user-3', 'local_kopere_dashboard');
        $report->reportsql = '';
        $report->columns = array();
        $report->columns = json_encode(array('columns' => $report->columns));
        // self::reportInsert( $report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-4';
        $report->title = get_string('reports_report_user-4', 'local_kopere_dashboard');
        $report->reportsql = " SELECT lsl.id, u.id AS userid, " . get_all_user_name_fields(true, 'u') . ", u.email, u.city, lsl.timecreated
                                 FROM {logstore_standard_log}   lsl
                                 JOIN {user}                    u    ON u.id = lsl.userid
                                WHERE lsl.action LIKE 'loggedin'
                                  AND lsl.target LIKE 'user'";
        $report->columns = array(
            $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('courses_student_email', 'local_kopere_dashboard'), 'email'),
            $table->addHeader(get_string('user_table_city', 'local_kopere_dashboard'), 'city'),
            $table->addHeader(get_string('reports_timecreated', 'local_kopere_dashboard'), 'timecreated', TableHeaderItem::RENDERER_DATE)
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-5';
        $report->title = get_string('reports_report_user-5', 'local_kopere_dashboard');
        $report->reportsql = ' SELECT u.id, ' . get_all_user_name_fields(true, 'u') . ', u.email, u.city, u.timecreated
                                 FROM {user} u
                                WHERE u.deleted    = 0
                                  AND u.lastlogin  = 0
                                  AND u.id         > 1
                                  AND u.lastaccess = 0';
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('courses_student_email', 'local_kopere_dashboard'), 'email'),
            $table->addHeader(get_string('user_table_city', 'local_kopere_dashboard'), 'city'),
            $table->addHeader(get_string('reports_timecreated', 'local_kopere_dashboard'), 'timecreated', TableHeaderItem::RENDERER_DATE)
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-6';
        $report->title = get_string('reports_report_user-6', 'local_kopere_dashboard');
        $report->reportsql = ' SELECT concat(u.id, p.id), u.id, ' . get_all_user_name_fields(true, 'u') . ',
                                      c.fullname, c.shortname, p.timecompleted
                                 FROM {course_completions} p
                                 JOIN {course} c ON p.course = c.id
                                 JOIN {user}   u ON p.userid = u.id
                                WHERE c.enablecompletion = 1
                             ORDER BY u.username';
        $report->columns = array(
            $table->addHeader('#', 'id', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('courses_shortname', 'local_kopere_dashboard'), 'shortname')
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);


        $report = kopere_dashboard_reports::createNew();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-7';
        $report->title = get_string('reports_report_user-7', 'local_kopere_dashboard');
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = 'SELECT ue.id, u.id AS userid, ul.timeaccess, ' . get_all_user_name_fields(true, 'u') . ', u.email, u.city, u.lastaccess,
                                      c.fullname, c.shortname,
                                      (SELECT r."name"
                                         FROM {user_enrolments} ue2
                                         JOIN {enrol} e ON e.id = ue2.enrolid
                                         JOIN {role}  r ON e.id = r.id
                                        WHERE ue2.userid = u.id
                                          AND e.courseid = c.id ) AS rolename
                                 FROM {user_enrolments} ue
                                 JOIN {enrol}  e ON e.id = ue.enrolid
                                 JOIN {course} c ON c.id = e.courseid
                                 JOIN {user}   u ON u.id = ue.userid
                                 LEFT JOIN {user_lastaccess} ul ON ul.userid = u.id
                                WHERE ul.timeaccess IS NULL';
        }else{
            $report->reportsql = 'SELECT ue.id, u.id AS userid, ul.timeaccess, ' . get_all_user_name_fields(true, 'u') . ', u.email, u.city, u.lastaccess,
                                      c.fullname, c.shortname,
                                      (SELECT r.name
                                         FROM {user_enrolments} ue2
                                         JOIN {enrol} e ON e.id = ue2.enrolid
                                         JOIN {role}  r ON e.id = r.id
                                        WHERE ue2.userid = u.id
                                          AND e.courseid = c.id ) AS rolename
                                 FROM {user_enrolments} ue
                                 JOIN {enrol}  e ON e.id = ue.enrolid
                                 JOIN {course} c ON c.id = e.courseid
                                 JOIN {user}   u ON u.id = ue.userid
                                 LEFT JOIN {user_lastaccess} ul ON ul.userid = u.id
                                WHERE ul.timeaccess IS NULL';
        }
        $report->columns = array(
            $table->addHeader('#', 'userid', TableHeaderItem::TYPE_INT, null, 'width: 20px'),
            $table->addHeader(get_string('courses_student_name', 'local_kopere_dashboard'), 'userfullname'),
            $table->addHeader(get_string('courses_name', 'local_kopere_dashboard'), 'fullname'),
            $table->addHeader(get_string('courses_shortname', 'local_kopere_dashboard'), 'shortname'),
            $table->addHeader('rolename', 'rolename'),
        );
        $report->foreach = '$item->userfullname = fullname($item);';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::reportInsert($report);
    }

    /**
     * @param $title
     * @param $cols
     *
     * @return TableHeaderItem
     */
    private function addInfoHeader( $title, $cols) {
        $column = new TableHeaderItem();
        $column->title = $title;
        $column->cols = $cols;

        return $column;
    }

    /**
     * @param        $title
     * @param null   $chave
     * @param string $type
     * @param null   $funcao
     * @param null   $styleHeader
     * @param null   $styleCol
     *
     * @return \stdClass
     */
    private function addHeader( $title, $chave = null, $type = TableHeaderItem::TYPE_TEXT, $funcao = null, $styleHeader = null, $styleCol = null) {
        $column = new \stdClass();
        $column->chave = $chave;
        $column->type = $type;
        $column->title = $title;
        $column->funcao = $funcao;
        $column->styleHeader = $styleHeader;
        $column->styleCol = $styleCol;

        return $column;
    }

    /**
     * @param $reportcat
     */
    private static function reportCatInsert( $reportcat) {
        global $DB;

        $kopere_reportcat = $DB->get_record('kopere_dashboard_reportcat', array('type' => $reportcat->type));
        if (!$kopere_reportcat) {
            $DB->insert_record('kopere_dashboard_reportcat', $reportcat);
        }
    }

    /**
     * @param $report
     */
    private static function reportInsert( $report) {
        global $DB;

        $kopere_reports = $DB->get_record('kopere_dashboard_reports', array('reportkey' => $report->reportkey));
        if (!$kopere_reports) {
            $DB->insert_record('kopere_dashboard_reports', $report);
        }
    }
}