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
 * @created    21/07/17 01:14
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\event\import_course_enrol;
use local_kopere_dashboard\event\import_user_created;
use local_kopere_dashboard\event\import_user_created_and_enrol;
use local_kopere_dashboard\html\Form;
use local_kopere_dashboard\html\inputs\InputSelect;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\DateUtil;
use local_kopere_dashboard\util\EndUtil;
use local_kopere_dashboard\util\EnrolUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\util\StringUtil;
use local_kopere_dashboard\util\TitleUtil;
use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class UsersImport
 * @package local_kopere_dashboard
 */
class UsersImport {
    /**
     *
     */
    public function dashboard() {
        global $CFG;

        DashboardUtil::startPage(get_string_kopere('userimport_title'), get_string_kopere('userimport_title'));

        echo '<div class="element-box table-responsive">';

        echo '<form action="?UsersImport::upload" id="dropzone" class="dropzone needsclick dz-clickable">
                  <div class="dz-message needsclick">
                      ' . get_string_kopere('userimport_upload') . '
                  </div>                
              </form>
              <br/><br/><br/><br/>
              
              
              <script src="' . $CFG->wwwroot . '/local/kopere_dashboard/assets/dropzone/js/dropzone.js"></script>
              <script>
                  new Dropzone("#dropzone", { 
                      maxFiles : 1,
                      acceptedFiles: ".csv",
                      success: function(file, response){
                          console.log(response);
                          location.href = "?UsersImport::uploadSuccess"+response;
                      }
                  });
              </script>';

        DashboardUtil::endPage();
    }

    /**
     *
     */
    public function upload() {
        global $CFG;

        @mkdir($CFG->dataroot . '/kopere');
        @mkdir($CFG->dataroot . '/kopere/dashboard');
        @mkdir($CFG->dataroot . '/kopere/dashboard/tmp');

        $file = time() . '.csv';
        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            ob_clean();
            EndUtil::endScriptShow('&file=' . $file . '&name=' . urlencode($_FILES['file']['name']));
        } else {
            ob_clean();
            header('HTTP/1.0 404 Not Found');
            EndUtil::endScriptShow(get_string_kopere('userimport_moveuploadedfile_error'));
        }
    }

    /**
     *
     */
    public function uploadSuccess() {
        global $CFG;

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);

        DashboardUtil::startPage(array(
            array('UsersImport::dashboard', get_string_kopere('userimport_title')),
            $name
        ), get_string_kopere('userimport_title_proccess', $name));

        echo '<div class="element-box table-responsive">';

        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (!file_exists($target_file))
            Header::notfound(get_string_kopere('userimport_filenotfound', $name));

        $csvContent = file_get_contents($target_file, false, null, 0, 2000);

        if (count(explode(";", $csvContent)) > count(explode(",", $csvContent))) {
            $separator = ";";
        } else {
            $separator = ",";
        }

        if (count(explode($separator, $csvContent)) < 5) {
            Mensagem::printDanger(get_string_kopere('userimport_separator_error'));
            DashboardUtil::endPage();
            return;
        }

        TitleUtil::printH3('userimport_first10');

        $countRow = 0;
        $countCols = 0;
        $cols = array();
        echo '<table class="table table-bordered">';
        $handle = fopen($target_file, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {

            echo '<tr>';
            if ($countCols == 0) {
                foreach ($data as $col) {
                    echo '<th>' . $col . '</th>';
                    $cols[] = array($countCols, $col);
                    $countCols++;
                }
            } else {
                for ($i = 0; $i < $countCols; $i++) {
                    if (strlen($data[$i]) == 0)
                        echo '<td style="background: #FF9800;">(vazio)</td>';
                    else
                        echo '<td>' . $data[$i] . '</td>';
                }
            }
            echo '</tr>';

            $countRow++;
            if ($countRow == (10 + 1))
                break;
        }
        fclose($handle);
        echo '</table>';

        echo '<p><a target="_blank" href="?UsersImport::printAllLines&file=' . $file . '&name=' . $name . '&separator=' . $separator . '">' . get_string_kopere('userimport_linkall') . '</a></p>';

        $this->getEvents();
        $this->createFormCollums($cols, $separator);

        DashboardUtil::endPage();
    }

    /**
     *
     */
    private function getEvents() {
        global $DB;

        TitleUtil::printH2('userimport_messages');

        /** @var kopere_dashboard_events $import_course_enrol */
        $import_course_enrol = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_course_enrol',
        ), '*', IGNORE_MULTIPLE);

        TitleUtil::printH3('userimport_import_course_enrol_name');
        if ($import_course_enrol) {
            if ($import_course_enrol->status == 1) {
                $link = '<a target="_blank" href="?Notifications::addSegundaEtapa&id=' . $import_course_enrol->id . '">' . $import_course_enrol->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?Notifications::addSegundaEtapa&id=' . $import_course_enrol->id . '">' . $import_course_enrol->subject . '</a>';
                Mensagem::printInfo(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            Mensagem::printWarning(get_string_kopere('userimport_notreceivemessage'));
        }

        /** @var kopere_dashboard_events $import_user_created */
        $import_user_created = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_user_created'
        ), '*', IGNORE_MULTIPLE);

        TitleUtil::printH3('userimport_import_user_created_name');
        if ($import_user_created) {
            if ($import_user_created->status == 1) {
                $link = '<a target="_blank" href="?Notifications::addSegundaEtapa&id=' . $import_user_created->id . '">' . $import_user_created->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?Notifications::addSegundaEtapa&id=' . $import_user_created->id . '">' . $import_user_created->subject . '</a>';
                Mensagem::printInfo(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            Mensagem::printWarning(get_string_kopere('userimport_notreceivemessage'));
        }

        /** @var kopere_dashboard_events $import_user_created_and_enrol */
        $import_user_created_and_enrol = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_user_created_and_enrol'
        ), '*', IGNORE_MULTIPLE);

        TitleUtil::printH3('userimport_import_user_created_and_enrol_name');
        if ($import_user_created_and_enrol) {
            if ($import_user_created_and_enrol->status == 1) {
                $link = '<a target="_blank" href="?Notifications::addSegundaEtapa&id=' . $import_user_created_and_enrol->id . '">' . $import_user_created_and_enrol->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?Notifications::addSegundaEtapa&id=' . $import_user_created_and_enrol->id . '">' . $import_user_created_and_enrol->subject . '</a>';
                Mensagem::printInfo(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            Mensagem::printWarning(get_string_kopere('userimport_notreceivemessage'));
        }
    }

    /**
     * @param $cols
     * @param $separator
     */
    private function createFormCollums($cols, $separator) {
        global $DB;

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);

        TitleUtil::printH2('userimport_referencedata');

        $form = new Form('UsersImport::proccess');
        $form->createHiddenInput('name', $name);
        $form->createHiddenInput('file', $file);
        $form->createHiddenInput('separator', $separator);

        $values = array(array(
            'key' => '',
            'value' => get_string_kopere('userimport_colselect')
        ));
        $collNumber = 'A';
        foreach ($cols as $col) {
            $values[] = array(
                'key' => 'col_' . $col[0],
                'value' => get_string_kopere('userimport_colname', "$collNumber ({$col[1]})")
            );
            $collNumber++;
        }

        // Campos do usuário
        $fields = array(
            'password', 'idnumber', 'firstname', 'lastname', 'email', 'username', 'phone1', 'phone2', 'address', 'city', 'country'
        );
        echo '<div class="panel panel-default">
                  <div class="panel-heading">' . get_string_kopere('userimport_userdata') . '</div>
                  <div class="panel-body">';
        foreach ($fields as $field) {

            $input = InputSelect::newInstance()
                ->setName($field)
                ->setValues($values)
                ->setValue(optional_param($field, '', PARAM_RAW))
                ->setTitle(get_string($field) . ' (' . $field . ')');


            if ($field == 'email')
                $input->setRequired();
            elseif ($field == 'firstname')
                $input->setRequired()
                    ->setTitle(get_string_kopere('userimport_firstname') . ' (firstname or fullname)')
                    ->setDescription(get_string_kopere('userimport_firstname_desc'));

            $form->addInput($input);
        }
        echo '</div></div>';

        // Campos extras
        $fields = $DB->get_records('user_info_field', null, 'sortorder ASC');
        if ($fields) {
            echo '<div class="panel panel-default">
                  <div class="panel-heading">' . get_string_kopere('userimport_userfields') . '</div>
                  <div class="panel-body">';

            $fieldItem = StringUtil::clearParamsAll ('field', [], PARAM_TEXT);

            foreach ($fields as $field) {

                $input = InputSelect::newInstance()
                    ->setName('field[' . $field->id . ']')
                    ->setValues($values)
                    ->setDescription($field->name)
                    ->setTitle($field->name);
                if (isset($fieldItem[$field->id]))
                    $input->setValue($fieldItem[$field->id]);
                $form->addInput($input);
            }
            echo '</div></div>';
        }


        // Matrícula
        echo '<div class="panel panel-default">
                  <div class="panel-heading">' . get_string_kopere('userimport_courseenrol') . '</div>
                  <div class="panel-body">';

        echo get_string_kopere('userimport_courseenrol_desc');

        $form->addInput(InputSelect::newInstance()
            ->setName('shortnamecourse')
            ->setValues($values)
            ->setValue(optional_param('shortnamecourse', '', PARAM_RAW))
            ->setTitle(get_string('shortnamecourse') . ' (shortname or fullname)'));

        $form->addInput(InputSelect::newInstance()
            ->setName('idnumbercourse')
            ->setValues($values)
            ->setValue(optional_param('idnumbercourse', '', PARAM_RAW))
            ->setTitle(get_string('idnumbercourse') . ' (idnumber)'));

        $form->addInput(InputSelect::newInstance()
            ->setName('groupmembers')
            ->setValues($values)
            ->setValue(optional_param('groupmembers', '', PARAM_RAW))
            ->setDescription(get_string_kopere('userimport_group_desc'))
            ->setTitle(get_string('groupmembers', 'group')));

        echo get_string_kopere('userimport_date_desc');
        $form->addInput(InputSelect::newInstance()
            ->setName('enroltimestart')
            ->setValues($values)
            ->setValue(optional_param('enroltimestart', '', PARAM_RAW))
            ->setTitle(get_string('enroltimestart', 'enrol')));

        $form->addInput(InputSelect::newInstance()
            ->setName('enroltimeend')
            ->setValues($values)
            ->setValue(optional_param('enroltimeend', '', PARAM_RAW))
            ->setTitle(get_string('enroltimeend', 'enrol')));

        echo '</div></div>';


        $form->createSubmitInput(get_string_kopere('userimport_next'));
        $form->close();

    }

    /**
     *
     */
    public function proccess() {
        global $CFG, $DB, $USER;
        $CFG->debug = 0;

        require_once '../../user/lib.php';
        require_once '../../lib/classes/user.php';

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);
        $separator = optional_param('separator', '', PARAM_TEXT);
        $inserir = optional_param('inserir', 0, PARAM_INT);

        $col_username = $this->getColParam('username');
        $col_password = $this->getColParam('password');
        $col_idnumber = $this->getColParam('idnumber');
        $col_firstname = $this->getColParam('firstname');
        $col_lastname = $this->getColParam('lastname');
        $col_email = $this->getColParam('email');
        $col_phone1 = $this->getColParam('phone1');
        $col_phone2 = $this->getColParam('phone2');
        $col_address = $this->getColParam('address');
        $col_city = $this->getColParam('city');
        $col_country = $this->getColParam('country');
        $col_shortnamecourse = $this->getColParam('shortnamecourse');
        $col_idnumbercourse = $this->getColParam('idnumbercourse');
        $col_groupmembers = $this->getColParam('groupmembers');
        $col_enroltimestart = $this->getColParam('enroltimestart');
        $col_enroltimeend = $this->getColParam('enroltimeend');

        if (!$inserir) {
            DashboardUtil::startPage(array(
                array('UsersImport::dashboard', get_string_kopere('userimport_title')),
                $name
            ), get_string_kopere('userimport_title_proccess', $name));
            $this->createFormProccessCollums();
            echo '<table class="table table-bordered"><tr>';
        } else {
            ob_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"$name\"");
        }

        $this->addCol('username', !$inserir, true);
        $this->addCol('password', !$inserir, true);
        if ($col_idnumber)
            $this->addCol('idnumber', !$inserir, true);
        $this->addCol('firstname', !$inserir, true);
        $this->addCol('lastname', !$inserir, true);
        $this->addCol('email', !$inserir, true);
        if ($col_phone1)
            $this->addCol('phone1', !$inserir, true);
        if ($col_phone2)
            $this->addCol('phone2', !$inserir, true);
        if ($col_address)
            $this->addCol('address', !$inserir, true);
        if ($col_city)
            $this->addCol('city', !$inserir, true);
        $this->addCol('country', !$inserir, true);
        $this->addCol('userstatus', !$inserir, true);
        if ($col_shortnamecourse || $col_idnumbercourse) {
            $this->addCol('course', !$inserir, true);
            $this->addCol('enroltimestart', !$inserir, true);
            $this->addCol('enroltimeend', !$inserir, true);
            if ($col_groupmembers)
                $this->addCol('groupmembers', !$inserir, true);
        }

        if (!$inserir) {
            echo '</tr>';
        } else {
            echo "\n";
        }

        $isFirst = true;
        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (!file_exists($target_file))
            Header::notfound(get_string_kopere('userimport_filenotfound', $name));

        $handle = fopen($target_file, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
            if ($isFirst) {
                $isFirst = false;
                continue;
            }

            $username = $this->getColValue($col_username, $data);
            $password = $this->getColValue($col_password, $data);
            $idnumber = $this->getColValue($col_idnumber, $data);
            $firstname = $this->getColValue($col_firstname, $data);
            $lastname = $this->getColValue($col_lastname, $data);
            $email = $this->getColValue($col_email, $data);
            $phone1 = $this->getColValue($col_phone1, $data);
            $phone2 = $this->getColValue($col_phone2, $data);
            $address = $this->getColValue($col_address, $data);
            $city = $this->getColValue($col_city, $data, '.');
            $country = $this->getColValue($col_country, $data, $USER->country);
            $shortnamecourse = $this->getColValue($col_shortnamecourse, $data);
            $idnumbercourse = $this->getColValue($col_idnumbercourse, $data);
            $groupmembers = $this->getColValue($col_groupmembers, $data);
            $enroltimestart = $this->getColValue($col_enroltimestart, $data, 0);
            $enroltimeend = $this->getColValue($col_enroltimeend, $data, 0);

            if ($enroltimestart) {
                $enroltimestart = DateUtil::convertToTime($enroltimestart);
            }
            if (!$enroltimestart) {
                $enroltimestart = time();
            }
            if ($enroltimeend) {
                $enroltimeend = DateUtil::convertToTime($enroltimeend);
            }
            if (!$enroltimeend) {
                $enroltimeend = 0;
            }

            if (!$inserir) {
                echo '<tr>';
            }

            $user = $this->getUser($username, $email, $idnumber);
            $sendEventUserCreate = 0;
            if (!$user) {
                // Not search! create user.

                if ($lastname == null) {
                    $nomes = explode(' ', $firstname);
                    $firstname = $nomes[0];
                    array_shift($nomes);
                    $lastname = implode(' ', $nomes);
                }
                if ($username == null) {
                    $username = $email;
                }

                $user = null;
                $newUser = new \stdClass();

                $newUser->idnumber = $idnumber;
                $newUser->firstname = $firstname;
                $newUser->lastname = $lastname;
                $newUser->username = strtolower($username);
                $newUser->email = strtolower($email);
                $newUser->address = $address;
                $newUser->city = $city;
                $newUser->country = $country;
                $newUser->phone1 = $phone1;
                $newUser->phone2 = $phone2;

                $newUser->auth = 'manual';
                $newUser->confirmed = 1;
                $newUser->mnethostid = $CFG->mnet_localhost_id;

                if ($inserir) {
                    if (strlen($password) < 5) {
                        $password = '#' . random_string();
                    }
                    $newUser->password = $password;
                } else if (strlen($password) < 5) {
                    $password = get_string_kopere('userimport_passcreate');
                }

                $this->addCol($username, !$inserir);
                $this->addCol($password, !$inserir);
                if ($col_idnumber)
                    $this->addCol($idnumber, !$inserir);
                $this->addCol($firstname, !$inserir);
                $this->addCol($lastname, !$inserir);
                $this->addCol($email, !$inserir);
                if ($col_phone1)
                    $this->addCol($phone1, !$inserir);
                if ($col_phone2)
                    $this->addCol($phone2, !$inserir);
                if ($col_address)
                    $this->addCol($address, !$inserir);
                if ($col_city)
                    $this->addCol($city, !$inserir);
                $this->addCol($country, !$inserir);

                $errors = "";
                if (!empty($newUser->password)) {
                    $errmsg = '';
                    if (!check_password_policy($newUser->password, $errmsg)) {
                        $errors .= $errmsg;
                    }
                }
                if (empty($newUser->username)) {
                    $errors .= get_string('required');
                } else if (!$user or $user->username !== $newUser->username) {
                    if ($DB->record_exists('user', array('username' => $newUser->username, 'mnethostid' => $CFG->mnet_localhost_id))) {
                        $errors .= get_string('usernameexists');
                    }
                    // Check allowed characters.
                    if ($newUser->username !== \core_text::strtolower($newUser->username)) {
                        $errors .= get_string('usernamelowercase');
                    } else {
                        if ($newUser->username !== \core_user::clean_field($newUser->username, 'username')) {
                            $errors .= get_string('invalidusername');
                        }
                    }
                }
                if (!$user or (isset($newUser->email) && $user->email !== $newUser->email)) {
                    if (!validate_email($newUser->email)) {
                        $errors .= get_string('invalidemail');
                    } else if (empty($CFG->allowaccountssameemail)
                        and $DB->record_exists('user', array('email' => $newUser->email, 'mnethostid' => $CFG->mnet_localhost_id))) {
                        $errors .= get_string('emailexists');
                    }
                }
                if ($errors != '') {
                    $this->addCol($errors, !$inserir);
                } elseif (!$inserir) {
                    $this->addCol(get_string_kopere('userimport_noterror'), !$inserir);
                }


                if ($inserir) {
                    try {
                        $newUser->id = user_create_user($newUser);
                        $this->addCol(get_string_kopere('userimport_inserted'), !$inserir);
                        $iserted = true;
                    } catch (\Exception $e) {
                        $this->addCol($e->error, !$inserir);
                        $iserted = false;
                    }
                    if ($iserted) {
                        $user = $DB->get_record('user', array('id' => $newUser->id), '*', IGNORE_MULTIPLE);
                        set_user_preference('auth_forcepasswordchange', 1, $newUser);

                        $sendEventUserCreate = $newUser->id;
                    }
                }
            } else {
                $this->addCol($user->username, !$inserir);
                $this->addCol(get_string_kopere('userimport_cript'), !$inserir);
                if ($col_idnumber)
                    $this->addCol($user->idnumber, !$inserir);
                $this->addCol($user->firstname, !$inserir);
                $this->addCol($user->lastname, !$inserir);
                $this->addCol($user->email, !$inserir);
                if ($col_phone1)
                    $this->addCol($user->phone1, !$inserir);
                if ($col_phone2)
                    $this->addCol($user->phone2, !$inserir);
                if ($col_address)
                    $this->addCol($user->address, !$inserir);
                if ($col_city)
                    $this->addCol($user->city, !$inserir);
                $this->addCol($user->country, !$inserir);
                $this->addCol(get_string_kopere('userimport_exist'), !$inserir);
            }

            // if exist user, add extras
            if ($user) {
                $fieldItems = StringUtil::clearParamsAll ('field', [], PARAM_TEXT);
                foreach ($fieldItems as $key2 => $value2) {
                    if ($value2) {
                        $col = str_replace('col_', '', $value2);
                        $extraData = $this->getColValue($col, $data, 0);
                        if ($extraData) {
                            $user_info_data = new \stdClass();
                            $user_info_data->userid = $user->id;
                            $user_info_data->fieldid = $key2;
                            $user_info_data->data = $extraData;
                            $user_info_data->dataformat = 0;
                            try {
                                $DB->insert_record('user_info_data', $user_info_data);
                            } catch (\Exception $e) {
                            }
                        }
                    }
                }
            }

            $sendEventCourseCreate = 0;
            if ($col_shortnamecourse || $col_idnumbercourse) {
                $course = $this->getCourse($shortnamecourse, $idnumbercourse);
                // if exist user, and exist course, add enrol
                if ($user && $course && $inserir) {

                    EnrolUtil::enrol($course->id, $user->id, $enroltimestart, $enroltimeend, 0);

                    $sendEventCourseCreate = $course->id;

                    $this->addCol($course->fullname, !$inserir);
                    $this->addCol($enroltimestart, !$inserir);
                    $this->addCol($enroltimeend, !$inserir);

                    if ($col_groupmembers && $groupmembers) {
                        $groups = $this->getGroup($groupmembers, $course->id);
                        $groups_name = '';
                        if ($groups) {
                            $groups_name = $groups->name;

                            if (!$DB->get_record('groups_members',
                                array('groupid' => $groups->id, 'userid' => $user->id), '*', IGNORE_MULTIPLE)) {
                                $groups_members = new \stdClass();
                                $groups_members->groupid = $groups->id;
                                $groups_members->userid = $user->id;
                                $groups_members->timeadded = time();
                                $groups_members->component = '';
                                $groups_members->itemid = 0;
                                try {
                                    $DB->insert_record('groups_members', $groups_members);
                                } catch (\Exception $e) {
                                }
                            }
                        }
                        $this->addCol($groups_name, !$inserir);
                    }
                } elseif (!$inserir && $course) {
                    $this->addCol($course->fullname, true);
                    $this->addCol($enroltimestart, true);
                    $this->addCol($enroltimeend, true);

                    $groups = $this->getGroup($groupmembers, $course->id);
                    $groups_name = '';
                    if ($groups) {
                        $groups_name = $groups->name;
                    }
                    $this->addCol($groups_name, !$inserir);
                }
            }

            if ($sendEventUserCreate && $sendEventCourseCreate && $user) {
                $dataEvent = array(
                    'objectid' => $sendEventCourseCreate,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'password' => $password,
                        'courseid' => SITEID
                    ),
                    'context' => \context_user::instance($sendEventCourseCreate)
                );

                try {
                    import_user_created_and_enrol::create($dataEvent)->trigger();
                } catch (\Exception $e) {
                    // Não faz nada
                }
            } elseif ($sendEventUserCreate && $user) {
                $dataEvent = array(
                    'objectid' => $user->id,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'password' => $password,
                        'courseid' => SITEID
                    ),
                    'context' => \context_user::instance($user->id)
                );

                try {
                    import_user_created::create($dataEvent)->trigger();
                } catch (\Exception $e) {
                    // Não faz nada
                }
            } elseif ($sendEventCourseCreate && $user) {
                $dataEvent = array(
                    'objectid' => $sendEventCourseCreate,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'courseid' => $sendEventCourseCreate
                    ),
                    'context' => \context_user::instance($user->id)
                );
                try {
                    import_course_enrol::create($dataEvent)->trigger();
                } catch (\Exception $e) {
                    // Não faz nada
                }
            }

            if (!$inserir) {
                echo '</tr>';
            } else {
                echo "\n";
            }
        }
        fclose($handle);

        if (!$inserir) {
            echo '</table>';
            Mensagem::printInfo(get_string_kopere('userimport_wait'), 'mensage-proccess');
            $this->createFormProccessCollums();
            echo '</div>';

            echo '<script>
                      $(".bt-submit-ok").click(function(){
                          $("form").hide();
                          $("table").hide();
                          $(".mensage-proccess").show();
                      });
                  </script>
                  <style>.mensage-proccess{display:none}</style>';
        } else {
            unlink($target_file);
            EndUtil::endScriptShow();
        }
    }

    /**
     *
     */
    private function createFormProccessCollums() {

        echo '<div>';
        echo '<div style="float:left;margin-right:14px">';
        $form = new Form('UsersImport::proccess');
        $form->createHiddenInput('inserir', 1);

        $post   = StringUtil::clearParamsAll ( null, null, PARAM_TEXT );
        foreach ($post as $key => $value) {
            if ($key == 'POST') {
            } else if ($key == 'field') {
                $fieldItem = StringUtil::clearParamsAll ('field', [], PARAM_TEXT);
                foreach ($fieldItem as $key2 => $value2) {
                    $form->createHiddenInput('field[' . $key2 . ']', $value2);
                }
            } else {
                $form->createHiddenInput($key, $value);
            }
        }
        $form->createSubmitInput(get_string_kopere('userimport_dataok'), 'bt-submit-ok');
        $form->close();
        echo '</div>';

        echo '<div style="float:left">';
        $form = new Form('UsersImport::uploadSuccess');

        $post   = StringUtil::clearParamsAll ( null, null, PARAM_TEXT );
        foreach ($post as $key => $value) {
            if ($key == 'POST' || $key == 'inserir') {
            } else if ($key == 'field') {
                $fieldItem = StringUtil::clearParamsAll ('field', [], PARAM_TEXT);
                foreach ($fieldItem as $key2 => $value2) {
                    $form->createHiddenInput('field[' . $key2 . ']', $value2);
                }
            } else {
                $form->createHiddenInput($key, $value);
            }
        }
        $form->createSubmitInput(get_string_kopere('userimport_datanotok'), 'btn-danger');
        $form->close();

        echo '</div></div>';
    }

    /**
     * @param $paramName
     * @return mixed|null
     */
    private function getColParam($paramName) {
        $param = optional_param($paramName, null, PARAM_TEXT);
        if ($param == null)
            return null;

        return str_replace('col_', '', $param);
    }

    /**
     * @param $col
     * @param $data
     * @param string $default
     * @return string
     */
    private function getColValue($col, $data, $default = '') {
        if (isset($data[$col]))
            return $data[$col];

        return $default;
    }

    /**
     * @param $username
     * @param $email
     * @param $idnumber
     * @return mixed|null
     */
    private function getUser($username, $email, $idnumber) {
        global $DB, $CFG;

        if (strlen($username)) {
            $user = $DB->get_record('user', array('username' => $username), '*', IGNORE_MULTIPLE);

            if ($user)
                return $user;
        }

        if (empty($CFG->allowaccountssameemail)) {
            if (strlen($email)) {
                $user = $DB->get_record('user', array('email' => $email), '*', IGNORE_MULTIPLE);

                if ($user)
                    return $user;
            }
        }

        if (strlen($idnumber)) {
            $user = $DB->get_record('user', array('idnumber' => $idnumber), '*', IGNORE_MULTIPLE);

            if ($user)
                return $user;
        }
        return null;
    }

    /**
     * @param $shortnamecourse
     * @param $idnumbercourse
     * @return mixed|null
     */
    private function getCourse($shortnamecourse, $idnumbercourse) {
        global $DB;

        if (strlen($idnumbercourse)) {
            $course = $DB->get_record('course', array('idnumber' => $idnumbercourse), '*', IGNORE_MULTIPLE);

            if ($course)
                return $course;
        }

        if (strlen($shortnamecourse)) {
            $course = $DB->get_record('course', array('shortname' => $shortnamecourse), '*', IGNORE_MULTIPLE);

            if ($course)
                return $course;
        }

        if (strlen($shortnamecourse)) {
            $course = $DB->get_record('course', array('fullname' => $shortnamecourse), '*', IGNORE_MULTIPLE);

            if ($course)
                return $course;
        }

        return null;
    }

    /**
     * @param $groupmembers
     * @param $courseid
     * @return mixed|null
     */
    private function getGroup($groupmembers, $courseid) {
        global $DB;

        if (strlen($groupmembers)) {
            $groups = $DB->get_record('groups', array('name' => $groupmembers, 'courseid' => $courseid), '*', IGNORE_MULTIPLE);

            if ($groups)
                return $groups;
        }

        if (strlen($groupmembers)) {
            $groups = $DB->get_record('groups', array('idnumber' => $groupmembers, 'courseid' => $courseid), '*', IGNORE_MULTIPLE);

            if ($groups)
                return $groups;
        }
        return null;
    }

    /**
     *
     */
    public function printAllLines() {

        global $CFG;
        ob_clean();

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);
        $separator = optional_param('separator', '', PARAM_TEXT);

        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (!file_exists($target_file))
            Header::notfound(get_string_kopere('userimport_filenotfound', $name));

        echo '<!DOCTYPE html>
              <html lang="pt-BR">
              <head>
                  <meta charset="UTF-8">
                  <title>' . $name . '</title>
              </head>
              <body>';

        $countCols = 0;
        echo '<table border="1">';
        if (($handle = fopen($target_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {

                echo '<tr>';
                if ($countCols == 0) {
                    foreach ($data as $col) {
                        echo '<td>' . $col . '</td>';
                        $countCols++;
                    }
                } else {
                    for ($i = 0; $i < $countCols; $i++) {
                        if (strlen($data[$i]) == 0)
                            echo '<td style="background: #FF9800;">(vazio)</td>';
                        else
                            echo '<td>' . $data[$i] . '</td>';
                    }
                }
                echo '</tr>';
            }
            fclose($handle);
        }
        echo '</table>';

        EndUtil::endScriptShow('</body></html>');
    }

    /**
     * @param $value
     * @param bool $table
     * @param bool $th
     */
    private function addCol($value, $table = true, $th = false) {
        if ($table) {
            if ($th) {
                echo '<th>' . $value . '</th>';
            } else {
                echo '<td>' . $value . '</td>';
            }
        } else {
            echo '"' . $value . '",';
        }
    }
}
