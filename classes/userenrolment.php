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
 * @created    15/05/17 14:26
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_checkbox_select;
use local_kopere_dashboard\html\inputs\input_date_range;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\mensagem;

/**
 * Class userenrolment
 * @package local_kopere_dashboard
 */
class userenrolment {

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function mathedit() {
        global $DB, $PAGE;

        $ueid = optional_param('ueid', 0, PARAM_INT);
        $enrolment = $DB->get_record('user_enrolments', ['id' => $ueid], '*');

        header::notfound_null($enrolment, get_string_kopere('userenrolment_notfound'));

        ob_clean();
        dashboard_util::add_breadcrumb(get_string_kopere('userenrolment_detail'));
        dashboard_util::start_page();

        $form = new form(local_kopere_dashboard_makeurl("userenrolment", "mathedit_save"));
        $form->create_hidden_input('ueid', $ueid);

        $statusvalues = [
            ['key' => 0, 'value' => get_string_kopere('userenrolment_status_active')],
            ['key' => 1, 'value' => get_string_kopere('userenrolment_status_inactive')],
        ];
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string_kopere('userenrolment_status'))
                ->set_name('status')
                ->set_values($statusvalues)
                ->set_value($enrolment->status));

        echo '<div class="area-inscricao-times">';

        $form->add_input(
            input_date_range::new_instance()
                ->set_title(get_string_kopere('userenrolment_timestart'))
                ->set_name('timestart')
                ->set_value(userdate($enrolment->timestart, get_string_kopere('datetime')))
                ->set_datetimerange()
                ->set_required());

        $form->print_spacer(10);

        $form->add_input(
            input_checkbox_select::new_instance()
                ->set_title(get_string_kopere('userenrolment_timeendstatus'))
                ->set_name('timeend-status')
                ->set_checked($enrolment->timeend));

        echo '<div class="area_timeend">';

        $form->add_input(
            input_date_range::new_instance()
                ->set_title(get_string_kopere('userenrolment_timeend'))
                ->set_name('timeend')
                ->set_value(userdate($enrolment->timeend ? $enrolment->timeend : time(), get_string_kopere('datetime')))
                ->set_datetimerange()
                ->set_required());

        echo '</div>';

        $form->print_spacer(10);
        $form->print_row(get_string_kopere('userenrolment_created'),
            userdate($enrolment->timecreated, get_string_kopere('dateformat')));
        $form->print_row(get_string_kopere('userenrolment_updated'),
            userdate($enrolment->timemodified, get_string_kopere('dateformat')));

        $form->create_submit_input(get_string('savechanges'));

        $form->close();

        $PAGE->requires->js_call_amd('local_kopere_dashboard/userenrolment', 'userenrolment_status');
        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function mathedit_save() {
        global $DB;

        $ueid = optional_param('ueid', 0, PARAM_INT);
        $status = optional_param('status', 0, PARAM_INT);
        $timestart = optional_param('timestart', '', PARAM_TEXT);
        $timeendstatus = optional_param('timeend-status', 0, PARAM_INT);
        $timeend = optional_param('timeend', '', PARAM_TEXT);

        $enrolment = $DB->get_record('user_enrolments', ['id' => $ueid], '*');

        $enrolment->status = $status;
        $enrolment->timestart = \DateTime::createFromFormat(get_string_kopere('php_datetime'), $timestart)->format('U');
        if ($timeendstatus) {
            $enrolment->timeend = \DateTime::createFromFormat(get_string_kopere('php_datetime'), $timeend)->format('U');
        } else {
            $enrolment->timeend = 0;
        }

        $DB->update_record('user_enrolments', $enrolment);

        mensagem::agenda_mensagem_success(get_string_kopere('userenrolment_updatesuccess'));
        header::reload();
    }
}
