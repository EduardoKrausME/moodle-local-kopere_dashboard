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
 * @created    23/05/17 17:59
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http:// eduardokraus.com}
 * @license    http:// www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

ob_start();
require('../../config.php');
require('autoload.php');
global $DB, $PAGE, $OUTPUT;

$menu = optional_param('menu', 0, PARAM_TEXT);
$page = optional_param('p', 0, PARAM_TEXT);

//if (!isloggedin()) {
//    $filecache = \local_kopere_dashboard\util\html::link( $menu . '-' . $page );
//    $cachefilename = \local_kopere_dashboard\webpages::get_cache_dir() . $filecache . '.html';
//
//    if (file_exists($cachefilename)) {
//        echo file_get_contents($cachefilename);
//        die();
//    }
//}

$pagehtml = '';

$PAGE->set_context(context_system::instance());

if ($menu) {

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
    $menu = $DB->get_record('kopere_dashboard_menu', array('link' => $menu));

    $PAGE->set_url(new moodle_url("/local/kopere_dashboard/?menu=" . $menu->link));
    $PAGE->set_pagelayout(get_config('local_kopere_dashboard', 'webpages_theme'));
    $PAGE->set_title($menu->title);

    $PAGE->requires->css('/local/kopere_dashboard/assets/statics-pages.css');

    $pagehtml .= $OUTPUT->header();

    $sql =  'SELECT * FROM {kopere_dashboard_webpages} WHERE visible = 1 AND menuid = :menuid ORDER BY pageorder ASC';
    $webpagess = $DB->get_records_sql($sql, array('menuid' => $menu->id));

    if( count($webpagess) == 1 ){
        ob_clean();
        foreach ($webpagess as $webpages) {
            header("Location: {$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}");
            die();
        }
    }

    $pagehtml .= '<div class="statics-pages frontpage-course-list-all">';
    $pagehtml .= "<h1>{$menu->title}</h1>";

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpages */
    foreach ($webpagess as $webpages) {
        $pagehtml
            .= "<div class=\"coursebox clearfix odd first\" >
                    <div class=\"info\">
                        <h3 class=\"coursename\">
                            <a href=\"{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}\">{$webpages->title}</a>
                        </h3>
                    </div>
                    <div class=\"content\">
                        <div class=\"summary\" >
                            <div class=\"no-overflow\">" . \local_kopere_dashboard\util\html::truncate_text(strip_tags($webpages->text), 300) . "</div>
                        </div>
                    </div>
                </div>";
    }

    $pagehtml .= '</div>';

} else if ($page) {

    $sql = "SELECT * FROM {kopere_dashboard_webpages} WHERE link LIKE :link";

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpages */
    $webpages = $DB->get_record_sql($sql, array('link' => $page), MUST_EXIST);

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
    $menu = $DB->get_record('kopere_dashboard_menu', array('id' => $webpages->menuid));

    $PAGE->set_url(new moodle_url("/local/kopere_dashboard/?p=" . $page));
    $PAGE->set_title($webpages->title);
    $PAGE->set_pagelayout($webpages->theme);

    $PAGE->navbar->add($webpages->title, new moodle_url('/local/kopere_dashboard/?p=' . $webpages->link));

    $pagehtml .= $OUTPUT->header();
    $pagehtml .= "<h1>{$webpages->title}</h1>";
    $pagehtml .= $webpages->text;

    // if ( $webpages->courseid ) {
    // $pagehtml .= \local_kopere_dashboard\html\button::edit ( 'Matrícular neste curso', '#', \local_kopere_dashboard\html\button::BTN_GRANDE, true, true );
    // }

} else {

    $PAGE->set_url(new moodle_url("/local/kopere_dashboard/"));
    $PAGE->set_title(get_string_kopere('webpages_allpages'));
    $PAGE->set_pagelayout(get_config('local_kopere_dashboard', 'webpages_theme'));

    $pagehtml .= $OUTPUT->header();

    $sql = "SELECT * FROM {kopere_dashboard_webpages} WHERE visible = 1 ORDER BY pageorder ASC";
    $webpagess = $DB->get_records_sql($sql);

    $pagehtml .= '<div class="courses frontpage-course-list-all">';
    $pagehtml .= \local_kopere_dashboard\util\title_util::print_h1('webpages_allpages');

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpages */
    foreach ($webpagess as $webpages) {
        $pagehtml
            .= "<div class=\"coursebox clearfix odd first\" >
                    <div class=\"info\">
                        <h3 class=\"coursename-\">
                            <a href=\"{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}\">{$webpages->title}</a>
                        </h3>
                    </div>
                    <div class=\"content\">
                        <div class=\"summary\">
                            <div class=\"no-overflow\">" . \local_kopere_dashboard\util\html::truncate_text(strip_tags($webpages->text), 300) . "</div>
                        </div>
                    </div>
                </div>";
    }
    $pagehtml .= '</div>';
}

$webpagesanalyticsid = get_config('local_kopere_dashboard', 'webpages_analytics_id');
if (strlen($webpagesanalyticsid) > 10) {
    $pagehtml
        .= '<script>
                     (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
                     (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                     m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                     })(window,document,\'script\',\'https:// www.google-analytics.com/analytics.js\',\'ga\');

                     ga(\'create\', \'' . $webpagesanalyticsid . '\', \'auto\');
                     ga(\'send\', \'pageview\');

                 </script>';
}

$webpagesanalyticsid = get_config('local_kopere_dashboard', 'webpages_analytics_id');

if (strlen($webpagesanalyticsid) > 5 && strlen($webpagesanalyticsid) < 15) {
    $pagehtml
        .= "<script>
                (function(i,s,o,g,r,a,m){
                    i['GoogleAnalyticsObject']=r;
                    i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)
                    },i[r].l=1*new Date();
                    a=s.createElement(o), m=s.getElementsByTagName(o)[0];
                    a.async=1;
                    a.src=g;
                    m.parentNode.insertBefore(a,m)
                })(window,document,'script','https:// www.google-analytics.com/analytics.js','ga');
                ga('create', '$webpagesanalyticsid', 'auto');
                ga('send', 'pageview');
            </script>";
}

$pagehtml .= $OUTPUT->footer();

echo $pagehtml;

//if (isloggedin()) {
//    echo $pagehtml;
//} else {
//    ob_clean();
//    $pagehtml = str_replace('// <![CDATA[', '', $pagehtml);
//    $pagehtml = str_replace('// ]]>', '', $pagehtml);
//
//    $pagehtml = preg_replace("/\/\/ .*?\n/", "", $pagehtml);
//    $pagehtml = preg_replace('/\s+/', ' ', $pagehtml);
//
//    // preg_match_all ( "/<link rel=\"stylesheet\" type=\"text\/css\" href=\"(http.*?)\" \/>/", $pagehtml, $csss );
//    //
//    // foreach ( $csss[ 0 ] as $key => $css ) {
//    // $link    = $csss[ 1 ][ $key ];
//    // $cssfile = file_get_contents ( $link );
//    //
//    // $cssfile = preg_replace ( '/\s+/', ' ', $cssfile );
//    // $cssfile = preg_replace('!/\*.*?\*/!s', ' ', $cssfile);
//    //
//    // $pagehtml = str_replace ( $csss[ 0 ][ $key ], '<style>' . $cssfile . '</style>', $pagehtml );
//    // }
//
//    echo $pagehtml;
//
//    file_put_contents($cachefilename, $pagehtml . "\n<!-- Cached on " . str_replace('T', ' ', date("c")) . " -->");
//}
