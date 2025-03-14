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
 * userenrolment file
 *
 * introduced 15/05/17 14:26
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_checkbox_select;
use local_kopere_dashboard\html\inputs\input_date_range;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\url_util;

/**
 * Class userenrolment
 *
 * @package local_kopere_dashboard
 */
class userenrolment {

    /**
     * Function mathedit
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function mathedit() {
        global $DB, $PAGE;

        $ueid = optional_param("ueid", 0, PARAM_INT);
        $enrolment = $DB->get_record("user_enrolments", ["id" => $ueid], "*");

        header::notfound_null($enrolment, get_string("userenrolment_notfound", "local_kopere_dashboard"));

        ob_clean();
        dashboard_util::add_breadcrumb(get_string("userenrolment_detail", "local_kopere_dashboard"));
        dashboard_util::start_page();

        $form = new form(url_util::makeurl("userenrolment", "mathedit_save"));
        $form->create_hidden_input("ueid", $ueid);

        $statusvalues = [
            ["key" => 0, "value" => get_string("userenrolment_status_active", "local_kopere_dashboard")],
            ["key" => 1, "value" => get_string("userenrolment_status_inactive", "local_kopere_dashboard")],
        ];
        $form->add_input(
            input_select::new_instance()
                ->set_title(get_string("userenrolment_status", "local_kopere_dashboard"))
                ->set_name("status")
                ->set_values($statusvalues)
                ->set_value($enrolment->status));

        echo '<div class="area-inscricao-times">';

        $form->add_input(
            input_date_range::new_instance()
                ->set_title(get_string("userenrolment_timestart", "local_kopere_dashboard"))
                ->set_name("timestart")
                ->set_value(userdate($enrolment->timestart, get_string("datetime", "local_kopere_dashboard")))
                ->set_datetimerange()
                ->set_required());

        $form->print_spacer(10);

        $form->add_input(
            input_checkbox_select::new_instance()
                ->set_title(get_string("userenrolment_timeendstatus", "local_kopere_dashboard"))
                ->set_name("timeend-status")
                ->set_checked($enrolment->timeend));

        echo '<div class="area_timeend">';

        $form->add_input(
            input_date_range::new_instance()
                ->set_title(get_string("userenrolment_timeend", "local_kopere_dashboard"))
                ->set_name("timeend")
                ->set_value(userdate($enrolment->timeend ? $enrolment->timeend : time(),
                    get_string("datetime", "local_kopere_dashboard")))
                ->set_datetimerange()
                ->set_required());

        echo "</div>";

        $form->print_spacer(10);
        $form->print_row(get_string("userenrolment_created", "local_kopere_dashboard"),
            userdate($enrolment->timecreated, get_string("dateformat", "local_kopere_dashboard")));
        $form->print_row(get_string("userenrolment_updated", "local_kopere_dashboard"),
            userdate($enrolment->timemodified, get_string("dateformat", "local_kopere_dashboard")));

        $form->create_submit_input(get_string("savechanges"));

        $form->close();

        $PAGE->requires->js_call_amd("local_kopere_dashboard/userenrolment", "userenrolment_status");

        dashboard_util::end_page();
    }

    /**
     * Function mathedit_save
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public function mathedit_save() {
        global $DB;

        require_sesskey();

        $ueid = optional_param("ueid", 0, PARAM_INT);
        $status = optional_param("status", 0, PARAM_INT);
        $timestart = optional_param("timestart", "", PARAM_TEXT);
        $timeendstatus = optional_param("timeend-status", 0, PARAM_INT);
        $timeend = optional_param("timeend", "", PARAM_TEXT);

        $enrolment = $DB->get_record("user_enrolments", ["id" => $ueid], "*");

        $enrolment->status = $status;
        $enrolment->timestart = \DateTime::createFromFormat(get_string("php_datetime", "local_kopere_dashboard"),
            $timestart)->format("U");
        if ($timeendstatus) {
            $enrolment->timeend = \DateTime::createFromFormat(get_string("php_datetime", "local_kopere_dashboard"),
                $timeend)->format("U");
        } else {
            $enrolment->timeend = 0;
        }

        $DB->update_record("user_enrolments", $enrolment);

        message::schedule_message_success(get_string("userenrolment_updatesuccess", "local_kopere_dashboard"));
        header::reload();
    }
}
