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
 * @created    31/01/17 06:35
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;

define('BENCHFAIL_SLOWSERVER', 'slowserver');
define('BENCHFAIL_SLOWPROCESSOR', 'slowprocessor');
define('BENCHFAIL_SLOWHARDDRIVE', 'slowharddrive');
define('BENCHFAIL_SLOWDATABASE', 'slowdatabase');
define('BENCHFAIL_SLOWWEB', 'slowweb');

class report_benchmark_test extends report_benchmark {

    /**
     * Moodle loading time
     *
     * @return array Contains the test results
     */
    public static function cload() {

        return [
            'limit' => .5,
            'over' => .8,
            'start' => BENCHSTART,
            'stop' => BENCHSTOP,
            'fail' => BENCHFAIL_SLOWSERVER,
        ];

    }

    /**
     * Function called many times
     *
     * @return array Contains the test results
     */
    public static function processor() {

        $pass = 10000000;
        for ($i = 0; $i < $pass; ++$i) {
            $a = 1;
            // Loooop muito long para teste.
        }
        $i = 0;
        while ($i < $pass) {
            ++$i;
        }

        return ['limit' => .5, 'over' => .8, 'fail' => BENCHFAIL_SLOWPROCESSOR];

    }

    /**
     * Reading files in the Moodle's temporary folder
     *
     * @return array Contains the test results
     */
    public static function fileread() {
        global $CFG;

        file_put_contents("{$CFG->tempdir}/benchmark.temp", 'benchmark');
        $i = 0;
        $pass = 2000;
        while ($i < $pass) {
            ++$i;
            file_get_contents("{$CFG->tempdir}/benchmark.temp");
        }
        unlink("{$CFG->tempdir}/benchmark.temp");

        return ['limit' => .5, 'over' => .8, 'fail' => BENCHFAIL_SLOWHARDDRIVE];

    }

    /**
     * Creating files in the Moodle's temporary folder
     *
     * @return array Contains the test results
     */
    public static function filewrite() {
        global $CFG;

        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lacus felis,
dignissim quis nisl sit amet, blandit suscipit lacus. Duis maximus, urna sed fringilla consequat, tellus
ex sollicitudin ante, vitae posuere neque purus nec justo. Donec porta ipsum sed urna tempus, sit amet
dictum lorem euismod. Phasellus vel erat a libero aliquet venenatis. Phasellus condimentum venenatis
risus ut egestas. Morbi sit amet posuere orci, id tempor dui. Vestibulum eget sapien eget mauris
eleifend ullamcorper. In finibus mauris id augue fermentum porta. Fusce dictum vestibulum justo
eget malesuada. Nullam at tincidunt urna, nec ultrices velit. Nunc eget augue velit. Mauris sed
rhoncus purus. Etiam aliquam urna ac nisl tristique, vitae tristique urna tincidunt. Vestibulum
luctus nulla magna, non tristique risus rhoncus nec. Vestibulum vestibulum, nulla scelerisque
congue molestie, dolor risus hendrerit velit, non malesuada nisi orci eget eros. Aenean interdum
ut lectus quis semper. Curabitur viverra vitae augue id.';
        $loremipsum = str_repeat($lorem, 16);
        $i = 0;
        $pass = 2000;
        while ($i < $pass) {
            ++$i;
            file_put_contents("{$CFG->tempdir}/benchmark.temp", $loremipsum);
        }
        unlink("{$CFG->tempdir}/benchmark.temp");

        return ['limit' => 1, 'over' => 1.25, 'fail' => BENCHFAIL_SLOWHARDDRIVE];

    }

    /**
     * Reading course
     *
     * @return array Contains the test results
     * @throws \dml_exception
     */
    public static function courseread() {
        global $DB;

        $i = 0;
        $pass = 500;
        while ($i < $pass) {
            ++$i;
            $DB->get_record('course', ['id' => SITEID]);
        }

        return ['limit' => .75, 'over' => 1, 'fail' => BENCHFAIL_SLOWDATABASE];

    }

    /**
     * Writing course
     *
     * @return array Contains the test results
     * @throws \dml_exception
     */
    public static function coursewrite() {
        global $DB;

        $uniq = md5(uniqid(rand(), true));
        $newrecord = new \stdClass();
        $newrecord->shortname = "!!!BENCH-{$uniq}";
        $newrecord->fullname = "!!!BENCH-{$uniq}";
        $newrecord->format = 'site';
        $newrecord->visible = 0;
        $newrecord->sortorder = 0;

        $i = 0;
        $pass = 25;
        while ($i < $pass) {
            ++$i;
            $DB->insert_record('course', $newrecord);
        }
        $DB->delete_records('course', ['shortname' => $newrecord->shortname]);
        unset($newrecord);

        return ['limit' => 1, 'over' => 1.25, 'fail' => BENCHFAIL_SLOWDATABASE];

    }

    /**
     * Complex request (n°1)
     *
     * @return array Contains the test results
     * @throws \dml_exception
     */
    public static function querytype1() {
        global $DB;

        $i = 0;
        $sql
            = "SELECT bi.id,
                         bp.id AS blockpositionid,
                         bi.blockname,
                         bi.parentcontextid,
                         bi.showinsubcontexts,
                         bi.pagetypepattern,
                         bi.subpagepattern,
                         bi.defaultregion,
                         bi.defaultweight,
                         COALESCE (bp.visible, 1) AS visible,
                         COALESCE (bp.region, bi.defaultregion) AS region,
                         COALESCE (bp.weight, bi.defaultweight) AS weight,
                         bi.configdata,
                         ctx.id AS ctxid,
                         ctx.PATH AS ctxpath,
                         ctx.DEPTH AS ctxdepth,
                         ctx.contextlevel AS ctxlevel,
                         ctx.instanceid AS ctxinstance
                    FROM {block_instances} bi
                         JOIN {block} b ON bi.blockname = b.name
                         LEFT JOIN
                         {block_positions} bp
                            ON     bp.blockinstanceid = bi.id
                               AND bp.contextid = '26'
                               AND bp.pagetype = 'mod-forum-discuss'
                               AND bp.subpage = ''
                         LEFT JOIN {context} ctx
                            ON (ctx.instanceid = bi.id AND ctx.contextlevel = '80')
                   WHERE     (   bi.parentcontextid = '26'
                              OR (    bi.showinsubcontexts = 1
                                  AND bi.parentcontextid IN ('16', '3', '1')))
                         AND bi.pagetypepattern IN
                                ('mod-forum-discuss',
                                 'mod-forum-discuss-*',
                                 'mod-forum-*',
                                 'mod-*',
                                 '*')
                         AND (bi.subpagepattern IS NULL OR bi.subpagepattern = '')
                         AND (bp.visible = 1 OR bp.visible IS NULL)
                         AND b.visible = 1
                ORDER BY COALESCE (bp.region, bi.defaultregion),
                         COALESCE (bp.weight, bi.defaultweight),
                         bi.id";
        $pass = 100;
        while ($i < $pass) {
            ++$i;
            $DB->get_records_sql($sql);
        }

        return ['limit' => .5, 'over' => .7, 'fail' => BENCHFAIL_SLOWDATABASE];

    }

    /**
     * Complex request (n°2)
     *
     * @return array Contains the test results
     * @throws \dml_exception
     */
    public static function querytype2() {
        global $DB;

        $i = 0;
        $sql
            = "SELECT parent_states.filter,
                       CASE WHEN fa.active IS NULL THEN 0 ELSE fa.active END AS localstate,
                       parent_states.inheritedstate
                  FROM (SELECT f.filter,
                               MAX(f.sortorder) AS sortorder,
                               CASE WHEN MAX(f.active * ctx.DEPTH) > -MIN(f.active * ctx.DEPTH)
                               THEN 1 ELSE - 1 END  AS inheritedstate
                          FROM {filter_active} f
                          JOIN {context} ctx ON f.contextid = ctx.id
                         WHERE ctx.id IN (1, 3, 16)
                      GROUP BY f.filter
                        HAVING MIN(f.active) > -9999) parent_states
             LEFT JOIN {filter_active} fa
                    ON fa.filter = parent_states.filter AND fa.contextid = 26
              ORDER BY parent_states.sortorder";

        $pass = 250;
        while ($i < $pass) {
            ++$i;
            $DB->get_records_sql($sql);
        }

        return ['limit' => .3, 'over' => .5, 'fail' => BENCHFAIL_SLOWDATABASE];

    }

    /**
     * Time to connect with the guest account
     *
     * @return array Contains the test results
     */
    public static function loginguest() {
        global $CFG;

        $fakeuser = ['username' => 'guest', 'password' => 'guest'];
        download_file_content("{$CFG->wwwroot}/login/index.php", null, $fakeuser, true);

        return ['limit' => .3, 'over' => .8, 'fail' => BENCHFAIL_SLOWWEB];

    }

    /**
     * Time to connect with the user account
     *
     * @return array Contains the test results
     * @throws \dml_exception
     */
    public static function loginuser() {
        global $CFG, $DB;

        // Create a fake user.
        $user = new \stdClass();
        $user->auth = 'manual';
        $user->confirmed = 1;
        $user->mnethostid = 1;
        $user->email = 'benchtest@benchtest.com';
        $user->username = 'benchtest';
        $user->password = md5('benchtest');
        $user->lastname = 'benchtest';
        $user->firstname = 'benchtest';
        $user->id = $DB->insert_record('user', $user);

        // Download login page.
        $fakeuser = ['username' => $user->username, 'password' => 'benchtest'];
        download_file_content("{$CFG->wwwroot}/login/index.php", null, $fakeuser, true);

        // Delete fake user.
        $DB->delete_records('user', ['id' => $user->id]);
        unset($user);

        return ['limit' => .3, 'over' => .8, 'fail' => BENCHFAIL_SLOWWEB];

    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function themedesignermode() {
        $config = get_config('moodle', 'themedesignermode');
        if (!$config) {
            $class = 'bg-success';
            $resposta = get_string('disabled', 'report_performance');
        } else {
            $class = 'bg-danger';
            $resposta = get_string('enabled', 'report_performance');
        }

        return [
            'title' => get_string('themedesignermode', 'admin'),
            'class' => $class,
            'resposta' => $resposta,
            'description' => get_string('check_themedesignermode_comment_disable', 'report_performance'),
            'url' => 'search.php?query=themedesignermode'
        ];
    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function cachejs() {
        $config = get_config('moodle', 'cachejs');
        if (!$config) {
            $class = 'bg-danger';
            $resposta = get_string('disabled', 'report_performance');
        } else {
            $class = 'bg-success';
            $resposta = get_string('enabled', 'report_performance');
        }

        return [
            'title' => get_string('cachejs', 'admin'),
            'class' => $class,
            'resposta' => $resposta,
            'description' => get_string('check_cachejs_comment_enable', 'report_performance'),
            'url' => 'search.php?query=cachejs',
        ];
    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function debug() {
        $config = get_config('moodle', 'debug');
        if ($config == 0) {
            $class = 'bg-success';
        } else {
            $class = 'bg-warning';
        }

        $resposta = '';
        if ($config == 0) {
            $resposta = get_string('debugnone', 'admin');
        } else if ($config <= 5) {
            $resposta = get_string('debugminimal', 'admin');
        } else if ($config <= 15) {
            $resposta = get_string('debugnormal', 'admin');
        } else if ($config <= 30719) {
            $resposta = get_string('debugall', 'admin');
            $class = 'bg-danger';
        } else if ($config <= 32767) {
            $resposta = get_string('debugdeveloper', 'admin');
            $class = 'bg-danger';
        }

        return [
            'title' => get_string('debug', 'admin'),
            'class' => $class,
            'resposta' => $resposta,
            'description' => get_string('check_debugmsg_comment_nodeveloper', 'report_performance'),
            'url' => 'settings.php?section=debugging',
        ];
    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function backup_auto_active() {
        $config = get_config('backup', 'backup_auto_active');

        $class = $resposta = '';
        if ($config == 0) {
            $class = 'bg-success';
            $resposta = get_string('autoactivedisabled', 'backup');
        } else if ($config == 1) {
            $class = 'bg-danger';
            $resposta = get_string('autoactiveenabled', 'backup');
        } else if ($config == 2) {
            $class = 'bg-warning';
            $resposta = get_string('autoactivemanual', 'backup');
        }

        return [
            'title' => get_string('check_backup', 'report_performance'),
            'class' => $class,
            'resposta' => $resposta,
            'description' => get_string('check_backup_comment_disable', 'report_performance'),
            'url' => 'search.php?query=backup_auto_active',
        ];
    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function enablestats() {
        $config = get_config('backup', 'enablestats');
        if (!$config == 1) {
            $class = 'bg-success';
            $resposta = get_string('disabled', 'report_performance');
        } else {
            $class = 'bg-danger';
            $resposta = get_string('enabled', 'report_performance');
        }

        return [
            'title' => get_string('enablestats', 'admin'),
            'class' => $class,
            'resposta' => $resposta,
            'description' => get_string('check_enablestats_comment_disable', 'report_performance'),
            'url' => 'search.php?query=enablestats',
        ];
    }
}
