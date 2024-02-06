<?php
/**
 * User: Eduardo Kraus
 * Date: 15/12/2023
 * Time: 12:43
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