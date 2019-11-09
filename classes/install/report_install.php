<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * User: Eduardo Kraus
 * Date: 17/07/17
 * Time: 21:28
 */

namespace local_kopere_dashboard\install;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\vo\kopere_dashboard_reportcat;
use local_kopere_dashboard\vo\kopere_dashboard_reports;

/**
 * Class report_install
 *
 * @package local_kopere_dashboard\install
 */
class report_install {

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function create_categores() {
        global $CFG;

        $reportcat = kopere_dashboard_reportcat::create_by_default();
        $reportcat->title = '[[reports_reportcat_badge]]';
        $reportcat->type = 'badge';
        $reportcat->image = 'assets/reports/badge.svg';
        $reportcat->enable = 1;
        $reportcat->enablesql = "SELECT id AS status FROM {badge} LIMIT 1";
        self::report_cat_insert($reportcat);

        $reportcat = kopere_dashboard_reportcat::create_by_default();
        $reportcat->title = '[[reports_reportcat_courses]]';
        $reportcat->type = 'courses';
        $reportcat->image = 'assets/reports/courses.svg';
        $reportcat->enable = 1;
        self::report_cat_insert($reportcat);

        $reportcat = kopere_dashboard_reportcat::create_by_default();
        $reportcat->title = '[[reports_reportcat_enrol_cohort]]';
        $reportcat->type = 'enrol_cohort';
        $reportcat->image = 'assets/reports/enrol_cohort.svg';
        $reportcat->enable = 1;
        if ($CFG->dbtype == 'pgsql') {
            $reportcat->enablesql = "SELECT id AS status FROM {config}
                                      WHERE \"name\" LIKE 'enrol_plugins_enabled' AND \"value\" LIKE '%cohort%' LIMIT 1";
        } else {
            $reportcat->enablesql = "SELECT id AS status FROM {config}
                                      WHERE `name` LIKE 'enrol_plugins_enabled' AND `value` LIKE '%cohort%' LIMIT 1";
        }
        self::report_cat_insert($reportcat);

        $reportcat = kopere_dashboard_reportcat::create_by_default();
        $reportcat->title = '[[reports_reportcat_enrol_guest]]';
        $reportcat->type = 'enrol_guest';
        $reportcat->image = 'assets/reports/enrol_guest.svg';
        $reportcat->enable = 1;
        if ($CFG->dbtype == 'pgsql') {
            $reportcat->enablesql = "SELECT id AS status FROM {config}
                                      WHERE \"name\" LIKE 'enrol_plugins_enabled' AND \"value\" LIKE '%guest%' LIMIT 1";
        } else {
            $reportcat->enablesql = "SELECT id AS status FROM {config}
                                      WHERE `name` LIKE 'enrol_plugins_enabled' AND `value` LIKE '%guest%' LIMIT 1";
        }
        self::report_cat_insert($reportcat);

        $reportcat = kopere_dashboard_reportcat::create_by_default();
        $reportcat->title = '[[reports_reportcat_server]]';
        $reportcat->type = 'server';
        $reportcat->image = 'assets/reports/server.svg';
        $reportcat->enable = 1;
        self::report_cat_insert($reportcat);

        $reportcat = kopere_dashboard_reportcat::create_by_default();
        $reportcat->title = '[[reports_reportcat_user]]';
        $reportcat->type = 'user';
        $reportcat->image = 'assets/reports/user.svg';
        $reportcat->enable = 1;
        self::report_cat_insert($reportcat);
    }

    /**
     * @throws \dml_exception
     * @throws \coding_exception
     */
    public static function create_reports() {
        global $DB, $CFG;

        $table = new report_install();

        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'badge'));
        $report->reportkey = 'badge-1';
        $report->title = '[[reports_report_badge-1]]';
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = ' SELECT b.id, b."name", b.description, b.type, b.status,
                                      (SELECT COUNT(*) FROM {badge_issued} d WHERE d.badgeid = b.id )AS students
                                 FROM {badge} b';
        } else {
            $report->reportsql = ' SELECT b.id, b.name, b.description, b.type, b.status,
                                      (SELECT COUNT(*) FROM {badge_issued} d WHERE d.badgeid = b.id )AS students
                                 FROM {badge} b';
        }
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'name'),
            $table->add_header('[[reports_context]]', 'context'),
            $table->add_header('[[userenrolment_status_active]]',
                'statustext', table_header_item::RENDERER_STATUS),
            $table->add_header('[[courses_enrol]]',
                'students', table_header_item::TYPE_INT)
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::badge_status_text';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'badge'));
        $report->reportkey = 'badge-2';
        $report->title = '[[reports_report_badge-2]]';
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = ' SELECT d.id, u.id AS userid, ' . get_all_user_name_fields(true, 'u') .
                ', u.lastname, b."name" AS badgename,
                                      t.criteriatype, t.method, d.dateissued,
                                      (SELECT c.shortname FROM {course} c WHERE c.id = b.courseid) as course
                                 FROM {badge_issued}   d
                                 JOIN {badge}          b ON d.badgeid = b.id
                                 JOIN {user}           u ON d.userid  = u.id
                                 JOIN {badge_criteria} t ON b.id      = t.badgeid
                                WHERE t.criteriatype != 0
                              ORDER BY u.username';
        } else {
            $report->reportsql = ' SELECT d.id, u.id AS userid, ' . get_all_user_name_fields(true, 'u') .
                ', u.lastname, b.name AS badgename,
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
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'name'),
            $table->add_header('[[reports_badgename]]', 'badgename'),
            $table->add_header('[[reports_criteriatype]]', 'criteriatype'),
            $table->add_header('[[courses_name]]', 'course'),
            $table->add_header('[[reports_dateissued]]',
                'dateissued', table_header_item::RENDERER_DATE)
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::badge_criteria_type';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-1';
        $report->title = '[[reports_report_courses-1]]';
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
                                                  SELECT CONCAT(IFNULL(ROUND((activities_completed)/(activities_assigned)*100,0),
                                                        \'0\'),\'% complete\')))), \'n/a\')
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
            $table->add_header('#', 'userid', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[courses_student_email]]', 'email'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[reports_coursecreated]]',
                'timecreated', table_header_item::RENDERER_DATE),
            $table->add_header('[[reports_activitiescomplete]]',
                'activities_completed', table_header_item::TYPE_INT),
            $table->add_header('[[reports_activitiesassigned]]',
                'activities_assigned', table_header_item::TYPE_INT),
            $table->add_header('[[reports_coursecompleted]]', 'course_completed')
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array(
            'columns' => $report->columns,
            'header' => array(
                $table->add_info_header('[[reports_datastudents]]', 3),
                $table->add_info_header('[[reports_datacourses]]', 5)
            )
        ));
        if ($CFG->dbtype != 'pgsql') {
            self::report_insert($report);
        }


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-2';
        $report->title = '[[reports_report_courses-2]]';
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = ' SELECT concat(c.id,g.id), c.id, c.fullname, c.shortname, g."name", c.visible, c.groupmode
                                 FROM {course} c
                                 JOIN {groups} g ON c.id = g.courseid
                                WHERE c.groupmode > 0';
        } else {
            $report->reportsql = ' SELECT concat(c.id,g.id), c.id, c.fullname, c.shortname, g.name, c.visible, c.groupmode
                                 FROM {course} c
                                 JOIN {groups} g ON c.id = g.courseid
                                WHERE c.groupmode > 0';
        }
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[courses_visible]]',
                'visible', table_header_item::RENDERER_VISIBLE),
            $table->add_header('[[reports_groupnode]]', 'groupname'),
            $table->add_header('[[reports_groupname]]', 'name')
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::courses_group_mode';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-3';
        $report->title = '[[reports_report_courses-3]]';
        $report->prerequisit = 'listCourses';
        $report->reportsql = 'local_kopere_dashboard\\report\\custom\\course_access';
        $report->columns = '';
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-4';
        $report->title = '[[reports_report_courses-4]]';
        $report->prerequisit = 'listCourses';
        $report->reportsql = 'local_kopere_dashboard\\report\\custom\\course_access_grade';
        $report->columns = '';
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'courses'));
        $report->reportkey = 'courses-5';
        $report->title = '[[reports_report_courses-5]]';
        $report->prerequisit = 'listCourses';
        $report->reportsql = 'local_kopere_dashboard\\report\\custom\\course_last_access';
        $report->columns = '';
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'enrol_cohort'));
        $report->reportkey = 'enrol_cohort-1';
        $report->title = '[[reports_report_enrol_cohort-1]]';
        $report->reportsql = ' SELECT u.id, ' . get_all_user_name_fields(true, 'u') . ', h.idnumber, h.name
                                 FROM {cohort} h
                                 JOIN {cohort_members} hm ON h.id = hm.cohortid
                                 JOIN {user} u ON hm.userid = u.id
                               ORDER BY u.firstname';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[reports_cohort]]', 'name')
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'enrol_guest'));
        $report->reportkey = 'enrol_guest-1';
        $report->title = '[[reports_report_enrol_guest-1]]';
        $report->reportsql = " SELECT u.id, " . get_all_user_name_fields(true, 'u') . ", u.id AS userid, lsl.timecreated, lsl.ip
                                 FROM {logstore_standard_log} lsl
                                 JOIN {user}                  u   ON u.id = lsl.userid
                                WHERE lsl.action LIKE 'loggedin'
                                  AND u.id = 1
                                  AND lsl.target LIKE 'user'";
        $report->columns = array(
            $table->add_header('#', 'userid', table_header_item::TYPE_INT),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[reports_lastlogin]]',
                'timecreated', table_header_item::RENDERER_DATE),
            $table->add_header('IP', 'ip')
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'server'));
        $report->reportkey = 'server-1';
        $report->title = '[[reports_report_server-1]]';
        $report->reportsql = ' SELECT c.id, c.fullname, c.shortname, c.visible, c.timecreated,
                                      (SELECT SUM( f.filesize )
                                         FROM {files} f, {context} ctx
                                        WHERE ctx.id           = f.contextid
                                          AND ctx.contextlevel = 50
                                          AND ctx.instanceid   = c.id
                                      GROUP BY ctx.instanceid) AS coursesize,
                                      (SELECT SUM( f.filesize ) AS modulessize
                                         FROM {course_modules} cm, {files} f, {context} ctx
                                        WHERE ctx.id = f.contextid
                                          AND ctx.instanceid   = cm.id
                                          AND ctx.contextlevel = 70
                                          AND cm.course        = c.id
                                     GROUP BY cm.course) AS modulessize
                                 FROM {course} c
                                WHERE c.id > 1';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[courses_shortname]]', 'shortname'),
            $table->add_header('[[courses_visible]]',
                'visible', table_header_item::RENDERER_VISIBLE),
            $table->add_header('[[reports_coursesize]]',
                'coursesize', table_header_item::TYPE_BYTES),
            $table->add_header('[[reports_modulessize]]',
                'modulessize', table_header_item::TYPE_BYTES)
        );
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-1';
        $report->title = '[[reports_report_user-1]]';
        $report->reportsql = ' SELECT DISTINCT c.id, c.fullname, c.shortname, ctx.id AS contextid,
                                       (SELECT COUNT(userid) FROM {role_assignments}
                                         WHERE contextid = asg.contextid GROUP BY contextid) AS alunos
                                 FROM {role_assignments} asg
                                 JOIN {context}          ctx ON asg.contextid = ctx.id
                                 JOIN {course}           c   ON ctx.instanceid = c.id
                                WHERE asg.roleid       = 5
                                  AND ctx.contextlevel = 50 ';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[courses_shortname]]', 'shortname'),
            $table->add_header('[[courses_enrol]]', 'alunos')
        );
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-2';
        $report->title = '[[reports_report_user-2]]';
        $report->reportsql = ' SELECT concat(u.id, p.id), u.id, ' . get_all_user_name_fields(true, 'u') .
            ', c.fullname, c.shortname,
                                      t.timecompleted, p.module, p.moduleinstance
                                 FROM {course_completion_crit_compl} t
                                 JOIN {user}    u ON t.userid = u.id
                                 JOIN {course}  c ON t.course = c.id
                                 JOIN {course_completion_criteria} p ON t.criteriaid = p.id';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[courses_shortname]]', 'shortname')
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-3';
        $report->title = '[[reports_report_user-3]]';
        $report->reportsql = '';
        $report->columns = array();
        $report->columns = json_encode(array('columns' => $report->columns));
        // Self::report_insert( $report).


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-4';
        $report->title = '[[reports_report_user-4]]';
        $report->reportsql = " SELECT lsl.id, u.id AS userid, " . get_all_user_name_fields(true, 'u') .
            ", u.email, u.city, lsl.timecreated
                                 FROM {logstore_standard_log}   lsl
                                 JOIN {user}                    u    ON u.id = lsl.userid
                                WHERE lsl.action LIKE 'loggedin'
                                  AND lsl.target LIKE 'user'";
        $report->columns = array(
            $table->add_header('#', 'userid', table_header_item::TYPE_INT),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[courses_student_email]]', 'email'),
            $table->add_header('[[user_table_city]]', 'city'),
            $table->add_header('[[reports_timecreated]]',
                'timecreated', table_header_item::RENDERER_DATE)
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-5';
        $report->title = '[[reports_report_user-5]]';
        $report->reportsql = ' SELECT u.id, ' . get_all_user_name_fields(true, 'u') .
            ', u.email, u.city, u.timecreated
                                 FROM {user} u
                                WHERE u.deleted    = 0
                                  AND u.lastlogin  = 0
                                  AND u.id         > 1
                                  AND u.lastaccess = 0';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[courses_student_email]]', 'email'),
            $table->add_header('[[user_table_city]]', 'city'),
            $table->add_header('[[reports_timecreated]]',
                'timecreated', table_header_item::RENDERER_DATE)
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-6';
        $report->title = '[[reports_report_user-6]]';
        $report->reportsql = ' SELECT concat(u.id, p.id), u.id, ' . get_all_user_name_fields(true, 'u') . ',
                                      c.fullname, c.shortname, p.timecompleted
                                 FROM {course_completions} p
                                 JOIN {course} c ON p.course = c.id
                                 JOIN {user}   u ON p.userid = u.id
                                WHERE c.enablecompletion = 1
                             ORDER BY u.username';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[courses_shortname]]', 'shortname')
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-7';
        $report->title = '[[reports_report_user-7]]';
        if ($CFG->dbtype == 'pgsql') {
            $report->reportsql = 'SELECT ue.id, u.id AS userid, ul.timeaccess, ' . get_all_user_name_fields(true, 'u') .
                ', u.email, u.city, u.lastaccess,
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
        } else {
            $report->reportsql = 'SELECT ue.id, u.id AS userid, ul.timeaccess, ' . get_all_user_name_fields(true, 'u') .
                ', u.email, u.city, u.lastaccess,
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
            $table->add_header('#', 'userid', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[courses_name]]', 'fullname'),
            $table->add_header('[[courses_shortname]]', 'shortname'),
            $table->add_header('rolename', 'rolename'),
        );
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);


        $report = kopere_dashboard_reports::create_by_default();
        $report->reportcatid = $DB->get_field('kopere_dashboard_reportcat', 'id', array('type' => 'user'));
        $report->reportkey = 'user-8';
        $report->title = '[[reports_report_user-8]]';
        $report->reportsql = 'SELECT id, confirmed, username, firstname, lastname, email, phone1, phone2, city, country FROM {user} WHERE deleted = 0';
        $report->foreach = 'local_kopere_dashboard\report\report_foreach::userfullname';
        $report->columns = array(
            $table->add_header('#', 'id', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[[confirmed]]]', 'confirmed', table_header_item::TYPE_INT, null, 'width:20px'),
            $table->add_header('[[courses_student_name]]', 'userfullname'),
            $table->add_header('[[[lastname]]]', 'lastname'),
            $table->add_header('[[[email]]]', 'email'),
            $table->add_header('[[[phone1]]]', 'phone1'),
            $table->add_header('[[[phone2]]]', 'phone2'),
            $table->add_header('[[[city]]]', 'city'),
            $table->add_header('[[[country]]]', 'country')
        );
        $report->columns = json_encode(array('columns' => $report->columns));
        self::report_insert($report);
    }

    /**
     * @param $title
     * @param $cols
     *
     * @return table_header_item
     */
    private function add_info_header($title, $cols) {
        $column = new table_header_item();
        $column->title = $title;
        $column->cols = $cols;

        return $column;
    }

    /**
     * @param        $title
     * @param null $chave
     * @param string $type
     * @param null $funcao
     * @param null $styleheader
     * @param null $stylecol
     *
     * @return \stdClass
     */
    private function add_header($title, $chave = null, $type = table_header_item::TYPE_TEXT, $funcao = null,
                                $styleheader = null, $stylecol = null) {
        $column = new \stdClass();
        $column->chave = $chave;
        $column->type = $type;
        $column->title = $title;
        $column->funcao = $funcao;
        $column->style_header = $styleheader;
        $column->style_col = $stylecol;

        return $column;
    }

    /**
     * @param $reportcat
     * @throws \dml_exception
     */
    private static function report_cat_insert($reportcat) {
        global $DB;

        $koperereportcat = $DB->get_record('kopere_dashboard_reportcat', array('type' => $reportcat->type));
        if (!$koperereportcat) {
            $DB->insert_record('kopere_dashboard_reportcat', $reportcat);
        }
    }

    /**
     * @param $report
     * @throws \dml_exception
     */
    private static function report_insert($report) {
        global $DB;

        $koperereports = $DB->get_record('kopere_dashboard_reports', array('reportkey' => $report->reportkey));
        if (!$koperereports) {
            $DB->insert_record('kopere_dashboard_reports', $report);
        }
    }
}