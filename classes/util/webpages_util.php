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
 * @created    15/12/2023 12:43
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\util;

use moodle_url;

class webpages_util {
    /**
     * @param $printtext
     * @throws \dml_exception
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function notfound($printtext) {
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

    /**
     * @throws \dml_exception
     */
    public static function analytics() {
        global $OUTPUT;

        $webpagesanalyticsid = get_config('local_kopere_dashboard', 'webpages_analytics_id');
        if (strlen($webpagesanalyticsid) > 5 && strlen($webpagesanalyticsid) < 15) {

            $data = [
                'webpagesanalyticsid' => $webpagesanalyticsid
            ];
            echo $OUTPUT->render_from_template('local_kopere_dashboard/google_analytics', $data);
        }
    }
}