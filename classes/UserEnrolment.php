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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputCheckbox;
use local_kopere_dashboard\html\inputs\InputDateRange;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;

/**
 * Class UserEnrolment
 * @package local_kopere_dashboard
 */
class UserEnrolment {
    /**
     *
     */
    public function mathedit() {
        global $DB;

        $ueid = optional_param('ueid', 0, PARAM_INT);
        $enrolment = $DB->get_record('user_enrolments', array('id' => $ueid), '*');

        Header::notfoundNull($enrolment, get_string_kopere('userenrolment_notfound'));

        ob_clean();
        DashboardUtil::startPopup(get_string_kopere('userenrolment_edit'), 'UserEnrolment::matheditSave');

        $form = new Form();
        $form->createHiddenInput('ueid', $ueid);

        $statusValues = array(
            array('key' => 0, 'value' => get_string_kopere('userenrolment_status_active')),
            array('key' => 1, 'value' => get_string_kopere('userenrolment_status_inactive'))
        );
        $form->addInput(
            InputSelect::newInstance()->setTitle(get_string_kopere('userenrolment_status'))
                ->setName('status')
                ->setValues($statusValues)
                ->setValue($enrolment->status));

        echo '<div class="area-inscricao-times">';

        $form->addInput(
            InputDateRange::newInstance()
                ->setTitle(get_string_kopere('userenrolment_timestart'))
                ->setName('timestart')
                ->setValue(userdate($enrolment->timestart, get_string_kopere('datetime')))
                ->setDatetimeRange()
                ->setRequired());

        $form->printSpacer(10);

        $form->addInput(
            InputCheckbox::newInstance()->setTitle(get_string_kopere('userenrolment_timeendstatus'))
                ->setName('timeend-status')
                ->setChecked($enrolment->timeend));

        echo '<div class="area_timeend">';

        $form->addInput(
            InputDateRange::newInstance()
                ->setTitle(get_string_kopere('userenrolment_timeend'))
                ->setName('timeend')
                ->setValue(userdate($enrolment->timeend ? $enrolment->timeend : time(), get_string_kopere('datetime')))
                ->setDatetimeRange()
                ->setRequired());

        echo '</div>';

        $form->printSpacer(10);
        $form->printRow(get_string_kopere('userenrolment_created'), userdate($enrolment->timecreated, get_string_kopere('dateformat')));
        $form->printRow(get_string_kopere('userenrolment_updated'), userdate($enrolment->timemodified, get_string_kopere('dateformat')));

        $form->close();

        ?>
        <script>
            $('#timeend-status').click(timeendStatusClick);
            $('#status').change(statusChange);

            function timeendStatusClick(delay) {
                if (delay != 0) {
                    delay = 400;
                }

                if ($('#timeend-status').is(":checked")) {
                    $('.area_timeend').show(delay);
                }
                else {
                    $('.area_timeend').hide(delay);
                }
            }
            function statusChange(delay) {
                if (delay != 0) {
                    delay = 400;
                }

                if ($('#status').val() == 0) {
                    $('.area-inscricao-times').show(delay);
                }
                else {
                    $('.area-inscricao-times').hide(delay);
                }
            }

            timeendStatusClick(0);
            statusChange(0);
        </script>
        <?php
        DashboardUtil::endPopup();
    }

    /**
     *
     */
    public function matheditSave() {
        global $DB;

        $ueid = optional_param('ueid', 0, PARAM_INT);
        $status = optional_param('status', 0, PARAM_INT);
        $timestart = optional_param('timestart', '', PARAM_TEXT);
        $timeendStatus = optional_param('timeend-status', 0, PARAM_INT);
        $timeend = optional_param('timeend', '', PARAM_TEXT);

        $enrolment = $DB->get_record('user_enrolments', array('id' => $ueid), '*');

        $enrolment->status = $status;
        $enrolment->timestart = \DateTime::createFromFormat(get_string_kopere('php_datetime'), $timestart)->format('U');
        if ($timeendStatus) {
            $enrolment->timeend = \DateTime::createFromFormat(get_string_kopere('php_datetime'), $timeend)->format('U');
        } else {
            $enrolment->timeend = 0;
        }

        $DB->update_record('user_enrolments', $enrolment);

        Mensagem::agendaMensagemSuccess(get_string_kopere('userenrolment_updatesuccess'));
        Header::reload();
    }
}