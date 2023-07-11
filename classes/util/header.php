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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

/**
 * Class header
 *
 * @package local_kopere_dashboard\util
 */
class header {
    /**
     * @param      $url
     * @param bool $isdie
     */
    public static function location($url, $isdie = true) {
        ob_clean();
        header("Location: {$url}");

        if ($isdie) {
            end_util::end_script_show("Redirecionando para {$url}");
        }
    }

    /**
     * @param bool $isdie
     * @throws \coding_exception
     */
    public static function reload($isdie = true) {
        ob_clean();

        header('Location: ' . url_util::querystring());
        if ($isdie) {
            end_util::end_script_show('Redirecionando para ' . url_util::querystring());
        }
    }

    /**
     * @param \stdClass $testparam
     * @param string $printtext
     * @throws \dml_exception
     */
    public static function notfound_null($testparam, $printtext) {
        if ($testparam == null) {
            self::notfound($printtext);
        }
    }

    /**
     * @param string $printtext
     * @throws \dml_exception
     */
    public static function notfound($printtext) {
        global $PAGE, $OUTPUT;

        if (!AJAX_SCRIPT) {
            header('HTTP/1.0 404 Not Found');
        }

        if (!$PAGE->requires->is_head_done()) {
            $PAGE->set_context(\context_system::instance());
            $PAGE->set_pagetype('admin-setting');
            $PAGE->set_pagelayout('standard');
            $PAGE->set_title('Erro');
            $PAGE->set_heading('Erro');

            echo $OUTPUT->header();
        }

        dashboard_util::start_page();

        echo "<div class='element-box text-center page404'>
                  <h2>OOPS!</h2>
                  <div class='text404 text-danger'>{$printtext}</div>
                  <p>
                      <a href='#' onclick='window.history.back();return false;'
                         class='btn btn-primary'>Voltar</a>
                  </p>
              </div>";

        dashboard_util::end_page();

        echo $OUTPUT->footer();
        end_util::end_script_show();
    }
}
