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

define('OPEN_INTERNAL', true);

ob_start();
require_once('../../config.php');
require('autoload.php');
global $DB, $PAGE, $OUTPUT;

$menulink = optional_param('menu', 0, PARAM_TEXT);
$pagelink = optional_param('p', 0, PARAM_TEXT);

$PAGE->set_context(context_system::instance());
$PAGE->requires->css('/local/kopere_dashboard/assets/statics-pages.css');
$PAGE->set_pagetype('my-index');

if ($pagelink) {
    $sql = "SELECT * FROM {kopere_dashboard_webpages} WHERE link LIKE :link";

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpages */
    $webpages = $DB->get_record_sql($sql, array('link' => $pagelink));

    if ($webpages == null) {
        $PAGE->set_url(new moodle_url("/local/kopere_dashboard/"));
        notfound("webpages_error_page");
    }

    $PAGE->set_url(new moodle_url("/local/kopere_dashboard/?p={$pagelink}"));
    $PAGE->set_pagelayout($webpages->theme);
    $PAGE->set_title($webpages->title);
    $PAGE->set_heading($webpages->title);

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
    $menu = $DB->get_record('kopere_dashboard_menu', array('id' => $webpages->menuid));
    $PAGE->navbar->add(get_string_kopere('webpages_allpages'), new moodle_url("/local/kopere_dashboard/"));
    $PAGE->navbar->add($menu->title, new moodle_url("/local/kopere_dashboard/?menu={$menu->link}"));
    $PAGE->navbar->add($webpages->title);

    echo $OUTPUT->header();

    preg_match_all('/\[\[(kopere_\w+)::(\w+)(->|-&gt;)(\w+)\((.*?)\)]]/', $webpages->text, $classes);

    foreach ($classes[0] as $key => $replace) {
        $classname = $classes[1][$key];
        $function = $classes[2][$key];
        $metodo = $classes[4][$key];
        $parametro = $classes[5][$key];
        $class = "\\local_{$classname}\\{$function}";

        if (class_exists($class)) {
            if (method_exists($class, $metodo)) {
                $newreplace = $class::$metodo($parametro);

                $webpages->text = str_replace($replace, $newreplace, $webpages->text);
            }
        }
    }
    echo '<div class="container">';
    echo $webpages->text;
    echo '</div>';

    analytics();
    echo $OUTPUT->footer();
} else {

    if ($menulink) {
        /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
        $menu = $DB->get_record('kopere_dashboard_menu', array('link' => $menulink));
        if ($menu == null) {
            notfound("webpages_error_menu");
        }

        $PAGE->set_url(new moodle_url("/local/kopere_dashboard/?menu={$menu->link}"));
        $PAGE->set_pagelayout(get_config('local_kopere_dashboard', 'webpages_theme'));
        $PAGE->set_title($menu->title);
        $PAGE->set_heading($menu->title);

        $PAGE->navbar->add(get_string_kopere('webpages_allpages'), new moodle_url("/local/kopere_dashboard/"));
        $PAGE->navbar->add($menu->title);

        $menus = [$menu];
    } else {
        $PAGE->set_url(new moodle_url("/local/kopere_dashboard/"));
        $PAGE->set_pagelayout(get_config('local_kopere_dashboard', 'webpages_theme'));
        $PAGE->set_title(get_string_kopere('webpages_allpages'));
        $PAGE->set_heading(get_string_kopere('webpages_allpages'));

        $PAGE->navbar->add(get_string_kopere('webpages_allpages'), new moodle_url("/local/kopere_dashboard/"));

        $menus = $DB->get_records('kopere_dashboard_menu');
    }
    echo $OUTPUT->header();

    echo '<div class="courses frontpage-course-list-all">';
    echo '<div class="container">';

    /** @var \local_kopere_dashboard\vo\kopere_dashboard_menu $menu */
    foreach ($menus as $menu) {

        if (!$menulink) {
            echo "<h3><a href='{$CFG->wwwroot}/local/kopere_dashboard/?menu={$menu->link}'>{$menu->title}</a></h1>";
        }
        echo '<div class="row">';

        $sql = "SELECT * FROM {kopere_dashboard_webpages} WHERE visible = 1 ORDER BY pageorder ASC";
        $webpagess = $DB->get_records_sql($sql);

        /** @var \local_kopere_dashboard\vo\kopere_dashboard_webpages $webpages */
        foreach ($webpagess as $webpages) {

            $valor = "";
            if (file_exists(__DIR__ . "/../kopere_pay/lib.php") && $webpages->courseid) {
                $kopere_pay_detalhe = $DB->get_record('kopere_pay_detalhe', array('course' => $webpages->courseid));
                $number = str_replace(".", "", $kopere_pay_detalhe->preco);
                $number = str_replace(",", ".", $number);
                $number = floatval("0{$number}");

                if (!$number) {
                    $valor = "<div class='preco-curso preco-curso-free'>Gratis</div>";
                } else {
                    $valor = "<div class='preco-curso'>R$ {$kopere_pay_detalhe->preco}</div>";
                }
            }

            $imagem = "";
            $fs = get_file_storage();
            $file = $fs->get_file(context_system::instance()->id, 'local_kopere_dashboard', 'webpage_image', $webpages->id, '/', 'webpage_image.img');
            if ($file && isset($file->get_filename()[3])) {
                $imagem = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), "/", $file->get_filename());
            }

            echo "
                <div class='col-lg-4 col-md-6 col-sm-12'>
                    <div class='box-page'>
                        <div class='thumbnail'>
                            <a href='{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}'>
                                <span style='background-image:url({$imagem})'></span>
                            </a>
                            {$valor}
                        </div>
                        <div class='content'>
                            <h5 class='title'>
                                <a href='{$CFG->wwwroot}/local/kopere_dashboard/?p={$webpages->link}'>{$webpages->title}</a>
                            </h5>
                            <p>" . \local_kopere_dashboard\util\html::truncate_text(strip_tags($webpages->text), 300) . "</p>
                        </div>
                    </div>
                </div>";
        }
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';

    analytics();
    echo $OUTPUT->footer();
}

function analytics() {
    $webpagesanalyticsid = get_config('local_kopere_dashboard', 'webpages_analytics_id');
    if (strlen($webpagesanalyticsid) > 5 && strlen($webpagesanalyticsid) < 15) {
        echo "
            <script>
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
                ga('create', '{$webpagesanalyticsid}', 'auto');
                ga('send', 'pageview');
            </script>";
    }
}

/**
 * @param $printtext
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function notfound($printtext) {
    global $PAGE, $OUTPUT, $CFG;

    header('HTTP/1.0 404 Not Found');
    $PAGE->set_context(\context_system::instance());
    $PAGE->set_pagelayout(get_config('local_kopere_dashboard', 'webpages_theme'));
    $PAGE->set_title(get_string('error'));
    $PAGE->set_heading(get_string('error'));

    $PAGE->navbar->add(get_string_kopere('webpages_allpages'), new moodle_url("/local/kopere_dashboard/"));
    $PAGE->navbar->add(get_string('error'));

    echo $OUTPUT->header();

    echo "<div class='element-box text-center page404'>
                  <h2>" . get_string('error') . "</h2>
                  <div>" . get_string_kopere($printtext) . "</div>
                  <p><a href='{$CFG->wwwroot}/local/kopere_dashboard/'>" . get_string_kopere('webpages_allpages') . "</a></p>
              </div>";

    echo $OUTPUT->footer();
    die();
}
