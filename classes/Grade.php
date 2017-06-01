<?php
/**
 * User: Eduardo Kraus
 * Date: 16/05/17
 * Time: 00:45
 */

namespace local_kopere_dashboard;


class Grade
{
    public function getLastGrades ()
    {
        global $DB;
        $data = $DB->get_records_sql ( "SELECT gg.id, u.id as userid, c.id as courseid, c.fullname AS 'coursename', gi.timemodified, 
                                               gi.itemtype, gi.itemname, gg.finalgrade, gg.rawgrademax
                                          FROM {course} AS c 
                                          JOIN {context} AS ctx ON c.id = ctx.instanceid 
                                          JOIN {role_assignments} AS ra ON ra.contextid = ctx.id 
                                          JOIN {user} AS u ON u.id = ra.userid 
                                          JOIN {grade_grades} AS gg ON gg.userid = u.id 
                                          JOIN {grade_items} AS gi ON gi.id = gg.itemid 
                                          JOIN {course_categories} AS cc ON cc.id = c.category 
                                         WHERE  gi.courseid = c.id AND gi.itemname != 'Attendance'
                                           AND gg.finalgrade IS NOT NULL
                                        ORDER BY gi.timemodified DESC
                                        LIMIT 0, 10" );

        return $data;
    }
}