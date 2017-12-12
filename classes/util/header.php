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

defined('MOODLE_INTERNAL') || die();

/**
 * Class header
 *
 * @package local_kopere_dashboard\util
 */
class header {
    /**
     * @param      $url
     * @param bool $is_die
     */
    public static function location( $url, $is_die = true) {
        ob_clean();
        header('Location: ?' . $url);

        if ($is_die) {
            end_util::end_script_show ('Redirecionando para ?' . $url);
        }
    }

    /**
     * @param bool $is_die
     */
    public static function reload( $is_die = true) {
        ob_clean();
        $url = $_SERVER['QUERY_STRING'];

        header('Location: ?' . $url);
        if ($is_die) {
            end_util::end_script_show ('Redirecionando para ?' . $url);
        }
    }

    /**
     * @param      $param
     * @param bool $print_text
     */
    public static function notfound_null( $param, $print_text = false) {
        if ($param == null) {
            self::notfound($print_text);
        }
    }

    /**
     * @param bool $print_text
     */
    public static function notfound( $print_text = false) {
        global $CFG;

        if (!AJAX_SCRIPT) {
            header('HTTP/1.0 404 Not Found');
        }

        dashboard_util::start_page('Erro');

        echo '<div class="element-box text-center page404">
                  <img width="200" height="200" src="' . $CFG->wwwroot . '/local/kopere_dashboard/assets/dashboard/img/404.svg">
                  <h2>OOPS!</h2>
                  <div class="text404 text-danger">' . $print_text . '</div>
                  <p>
                      <a href="#" onclick="window.history.back();return false;"
                         class="btn btn-primary">Voltar</a>
                  </p>
              </div>';

        dashboard_util::end_page();
        end_util::end_script_show();
    }
}