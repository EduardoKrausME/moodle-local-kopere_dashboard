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
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\date_util;
use local_kopere_dashboard\util\end_util;
use local_kopere_dashboard\util\enroll_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\string_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class usersimport
 * @package local_kopere_dashboard
 */
class usersimport {
    /**
     *
     */
    public function dashboard() {
        global $CFG;

        dashboard_util::start_page(get_string_kopere('userimport_title'), get_string_kopere('userimport_title'));

        echo '<div class="element-box table-responsive">';

        echo '<form action="?usersimport::upload" id="dropzone" class="dropzone needsclick dz-clickable">
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
                          location.href = ususersimportesponse;
                      }
                  });
              </script>';

        dashboard_util::end_page();
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
            end_util::end_script_show('&file=' . $file . '&name=' . urlencode($_FILES['file']['name']));
        } else {
            ob_clean();
            header('HTTP/1.0 404 Not Found');
            end_util::end_script_show(get_string_kopere('userimport_moveuploadedfile_error'));
        }
    }

    /**
     *
     */
    public function upload_success() {
        global $CFG;

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);

        dashboard_util::start_page(array(
            array('usersimport::dashboard', get_string_kopere('userimport_title')),
            $name
        ), get_string_kopere('userimport_title_proccess', $name));

        echo '<div class="element-box table-responsive">';

        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (!file_exists($target_file)) {
            header::notfound(get_string_kopere('userimport_filenotfound', $name));
        }

        $csv_content = file_get_contents($target_file, false, null, 0, 2000);

        if (count(explode(";", $csv_content)) > count(explode(",", $csv_content))) {
            $separator = ";";
        } else {
            $separator = ",";
        }

        if (count(explode($separator, $csv_content)) < 5) {
            mensagem::print_danger(get_string_kopere('userimport_separator_error'));
            dashboard_util::end_page();
            return;
        }

        title_util::print_h3('userimport_first10');

        $count_row = 0;
        $count_cols = 0;
        $cols = array();
        echo '<table class="table table-bordered">';
        $handle = fopen($target_file, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== false) {

            echo '<tr>';
            if ($count_cols == 0) {
                foreach ($data as $col) {
                    echo '<th>' . $col . '</th>';
                    $cols[] = array($count_cols, $col);
                    $count_cols++;
                }
            } else {
                for ($i = 0; $i < $count_cols; $i++) {
                    if (strlen($data[$i]) == 0) {
                        echo '<td style="background: #FF9800;">(vazio)</td>';
                    } else {
                        echo '<td>' . $data[$i] . '</td>';
                    }
                }
            }
            echo '</tr>';

            $count_row++;
            if ($count_row == (10 + 1)) {
                break;
            }
        }
        fclose($handle);
        echo '</table>';

        echo '<p><a target="_blank" href="?UsersImport::printusersimportle=' . $file . '&name=' . $name .
            '&separator=' . $separator . '">' . get_string_kopere('userimport_linkall') . '</a></p>';

        $this->get_events();
        $this->create_form_collums($cols, $separator);

        dashboard_util::end_page();
    }

    /**
     *
     */
    private function get_events() {
        global $DB;

        title_util::print_h2('userimport_messages');

        /** @var kopere_dashboard_events $import_course_enrol */
        $import_course_enrol = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_course_enrol',
        ), '*', IGNORE_MULTIPLE);

        title_util::print_h3('userimport_import_course_enrol_name');
        if ($import_course_enrol) {
            if ($import_course_enrol->status == 1) {
                $link = '<a target="_blank" href="?notifications::add_segunda_etapa&id=' . $import_course_enrol->id . '">' .
                    $import_course_enrol->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?notifications::add_segunda_etapa&id=' . $import_course_enrol->id . '">' .
                    $import_course_enrol->subject . '</a>';
                mensagem::print_info(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            mensagem::print_warning(get_string_kopere('userimport_notreceivemessage'));
        }

        /** @var kopere_dashboard_events $import_user_created */
        $import_user_created = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_user_created'
        ), '*', IGNORE_MULTIPLE);

        title_util::print_h3('userimport_import_user_created_name');
        if ($import_user_created) {
            if ($import_user_created->status == 1) {
                $link = '<a target="_blank" href="?notifications::add_segunda_etapa&id=' . $import_user_created->id . '">' .
                    $import_user_created->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?notifications::add_segunda_etapa&id=' . $import_user_created->id . '">' .
                    $import_user_created->subject . '</a>';
                mensagem::print_info(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            mensagem::print_warning(get_string_kopere('userimport_notreceivemessage'));
        }

        /** @var kopere_dashboard_events $import_user_created_and_enrol */
        $import_user_created_and_enrol = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_user_created_and_enrol'
        ), '*', IGNORE_MULTIPLE);

        title_util::print_h3('userimport_import_user_created_and_enrol_name');
        if ($import_user_created_and_enrol) {
            if ($import_user_created_and_enrol->status == 1) {
                $link = '<a target="_blank" href="?notifications::add_segunda_etapa&id=' .
                    $import_user_created_and_enrol->id . '">' .
                    $import_user_created_and_enrol->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?notifications::add_segunda_etapa&id=' .
                    $import_user_created_and_enrol->id . '">' .
                    $import_user_created_and_enrol->subject . '</a>';
                mensagem::print_info(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            mensagem::print_warning(get_string_kopere('userimport_notreceivemessage'));
        }
    }

    /**
     * @param $cols
     * @param $separator
     */
    private function create_form_collums($cols, $separator) {
        global $DB;

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);

        title_util::print_h2('userimport_referencedata');

        $form = new form('usersimport::proccess');
        $form->create_hidden_input('name', $name);
        $form->create_hidden_input('file', $file);
        $form->create_hidden_input('separator', $separator);

        $values = array(array(
            'key' => '',
            'value' => get_string_kopere('userimport_colselect')
        ));
        $coll_number = 'A';
        foreach ($cols as $col) {
            $values[] = array(
                'key' => 'col_' . $col[0],
                'value' => get_string_kopere('userimport_colname', "$coll_number ({$col[1]})")
            );
            $coll_number++;
        }

        // Campos do usuário.
        $fields = array(
            'password', 'idnumber', 'firstname', 'lastname', 'email', 'username', 'phone1', 'phone2', 'address', 'city', 'country'
        );
        echo '<div class="panel panel-default">
                  <div class="panel-heading">' . get_string_kopere('userimport_userdata') . '</div>
                  <div class="panel-body">';
        foreach ($fields as $field) {

            $input = input_select::new_instance()
                ->set_name($field)
                ->set_values($values)
                ->set_value(optional_param($field, '', PARAM_RAW))
                ->set_title(get_string($field) . ' (' . $field . ')');

            if ($field == 'email') {
                $input->set_required();
            } else if ($field == 'firstname') {
                $input->set_required()
                    ->set_title(get_string_kopere('userimport_firstname') . ' (firstname or fullname)')
                    ->set_description(get_string_kopere('userimport_firstname_desc'));
            }

            $form->add_input($input);
        }
        echo '</div></div>';

        // Campos extras.
        $fields = $DB->get_records('user_info_field', null, 'sortorder ASC');
        if ($fields) {
            echo '<div class="panel panel-default">
                  <div class="panel-heading">' . get_string_kopere('userimport_userfields') . '</div>
                  <div class="panel-body">';

            $field_item = string_util::clear_all_params('field', [], PARAM_TEXT);

            foreach ($fields as $field) {

                $input = input_select::new_instance()
                    ->set_name('field[' . $field->id . ']')
                    ->set_values($values)
                    ->set_description($field->name)
                    ->set_title($field->name);
                if (isset($field_item[$field->id])) {
                    $input->set_value($field_item[$field->id]);
                }
                $form->add_input($input);
            }
            echo '</div></div>';
        }

        // Matrícula.
        echo '<div class="panel panel-default">
                  <div class="panel-heading">' . get_string_kopere('userimport_courseenrol') . '</div>
                  <div class="panel-body">';

        echo get_string_kopere('userimport_courseenrol_desc');

        $form->add_input(input_select::new_instance()
            ->set_name('shortnamecourse')
            ->set_values($values)
            ->set_value(optional_param('shortnamecourse', '', PARAM_RAW))
            ->set_title(get_string('shortnamecourse') . ' (shortname or fullname)'));

        $form->add_input(input_select::new_instance()
            ->set_name('idnumbercourse')
            ->set_values($values)
            ->set_value(optional_param('idnumbercourse', '', PARAM_RAW))
            ->set_title(get_string('idnumbercourse') . ' (idnumber)'));

        $form->add_input(input_select::new_instance()
            ->set_name('groupmembers')
            ->set_values($values)
            ->set_value(optional_param('groupmembers', '', PARAM_RAW))
            ->set_description(get_string_kopere('userimport_group_desc'))
            ->set_title(get_string('groupmembers', 'group')));

        echo get_string_kopere('userimport_date_desc');
        $form->add_input(input_select::new_instance()
            ->set_name('enroltimestart')
            ->set_values($values)
            ->set_value(optional_param('enroltimestart', '', PARAM_RAW))
            ->set_title(get_string('enroltimestart', 'enrol')));

        $form->add_input(input_select::new_instance()
            ->set_name('enroltimeend')
            ->set_values($values)
            ->set_value(optional_param('enroltimeend', '', PARAM_RAW))
            ->set_title(get_string('enroltimeend', 'enrol')));

        echo '</div></div>';

        $form->create_submit_input(get_string_kopere('userimport_next'));
        $form->close();

    }

    /**
     *
     */
    public function proccess() {
        global $CFG, $DB, $USER;
        $CFG->debug = 0;

        require_once('../../user/lib.php');
        require_once('../../lib/classes/user.php');

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);
        $separator = optional_param('separator', '', PARAM_TEXT);
        $inserir = optional_param('inserir', 0, PARAM_INT);

        $col_username = $this->get_col_param('username');
        $col_password = $this->get_col_param('password');
        $col_idnumber = $this->get_col_param('idnumber');
        $col_firstname = $this->get_col_param('firstname');
        $col_lastname = $this->get_col_param('lastname');
        $col_email = $this->get_col_param('email');
        $col_phone1 = $this->get_col_param('phone1');
        $col_phone2 = $this->get_col_param('phone2');
        $col_address = $this->get_col_param('address');
        $col_city = $this->get_col_param('city');
        $col_country = $this->get_col_param('country');
        $col_shortnamecourse = $this->get_col_param('shortnamecourse');
        $col_idnumbercourse = $this->get_col_param('idnumbercourse');
        $col_groupmembers = $this->get_col_param('groupmembers');
        $col_enroltimestart = $this->get_col_param('enroltimestart');
        $col_enroltimeend = $this->get_col_param('enroltimeend');

        if (!$inserir) {
            dashboard_util::start_page(array(
                array('usersimport::dashboard', get_string_kopere('userimport_title')),
                $name
            ), get_string_kopere('userimport_title_proccess', $name));
            $this->create_form_proccess_collums();
            echo '<table class="table table-bordered"><tr>';
        } else {
            ob_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"$name\"");
        }

        $this->add_col('username', !$inserir, true);
        $this->add_col('password', !$inserir, true);
        if ($col_idnumber) {
            $this->add_col('idnumber', !$inserir, true);
        }
        $this->add_col('firstname', !$inserir, true);
        $this->add_col('lastname', !$inserir, true);
        $this->add_col('email', !$inserir, true);
        if ($col_phone1) {
            $this->add_col('phone1', !$inserir, true);
        }
        if ($col_phone2) {
            $this->add_col('phone2', !$inserir, true);
        }
        if ($col_address) {
            $this->add_col('address', !$inserir, true);
        }
        if ($col_city) {
            $this->add_col('city', !$inserir, true);
        }
        $this->add_col('country', !$inserir, true);
        $this->add_col('userstatus', !$inserir, true);
        if ($col_shortnamecourse || $col_idnumbercourse) {
            $this->add_col('course', !$inserir, true);
            $this->add_col('enroltimestart', !$inserir, true);
            $this->add_col('enroltimeend', !$inserir, true);
            if ($col_groupmembers) {
                $this->add_col('groupmembers', !$inserir, true);
            }
        }

        if (!$inserir) {
            echo '</tr>';
        } else {
            echo "\n";
        }

        $is_first = true;
        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (!file_exists($target_file)) {
            header::notfound(get_string_kopere('userimport_filenotfound', $name));
        }

        $handle = fopen($target_file, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== false) {
            if ($is_first) {
                $is_first = false;
                continue;
            }

            $username = $this->get_col_value($col_username, $data);
            $password = $this->get_col_value($col_password, $data);
            $idnumber = $this->get_col_value($col_idnumber, $data);
            $firstname = $this->get_col_value($col_firstname, $data);
            $lastname = $this->get_col_value($col_lastname, $data);
            $email = $this->get_col_value($col_email, $data);
            $phone1 = $this->get_col_value($col_phone1, $data);
            $phone2 = $this->get_col_value($col_phone2, $data);
            $address = $this->get_col_value($col_address, $data);
            $city = $this->get_col_value($col_city, $data, '.');
            $country = $this->get_col_value($col_country, $data, $USER->country);
            $shortnamecourse = $this->get_col_value($col_shortnamecourse, $data);
            $idnumbercourse = $this->get_col_value($col_idnumbercourse, $data);
            $groupmembers = $this->get_col_value($col_groupmembers, $data);
            $enroltimestart = $this->get_col_value($col_enroltimestart, $data, 0);
            $enroltimeend = $this->get_col_value($col_enroltimeend, $data, 0);

            if ($enroltimestart) {
                $enroltimestart = date_util::convert_to_time($enroltimestart);
            }
            if (!$enroltimestart) {
                $enroltimestart = time();
            }
            if ($enroltimeend) {
                $enroltimeend = date_util::convert_to_time($enroltimeend);
            }
            if (!$enroltimeend) {
                $enroltimeend = 0;
            }

            if (!$inserir) {
                echo '<tr>';
            }

            $user = $this->get_user($username, $email, $idnumber);
            $send_event_user_create = 0;
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
                $new_user = new \stdClass();

                $new_user->idnumber = $idnumber;
                $new_user->firstname = $firstname;
                $new_user->lastname = $lastname;
                $new_user->username = strtolower($username);
                $new_user->email = strtolower($email);
                $new_user->address = $address;
                $new_user->city = $city;
                $new_user->country = $country;
                $new_user->phone1 = $phone1;
                $new_user->phone2 = $phone2;

                $new_user->auth = 'manual';
                $new_user->confirmed = 1;
                $new_user->mnethostid = $CFG->mnet_localhost_id;

                if ($inserir) {
                    if (strlen($password) < 5) {
                        $password = '#' . random_string();
                    }
                    $new_user->password = $password;
                } else if (strlen($password) < 5) {
                    $password = get_string_kopere('userimport_passcreate');
                }

                $this->add_col($username, !$inserir);
                $this->add_col($password, !$inserir);
                if ($col_idnumber) {
                    $this->add_col($idnumber, !$inserir);
                }
                $this->add_col($firstname, !$inserir);
                $this->add_col($lastname, !$inserir);
                $this->add_col($email, !$inserir);
                if ($col_phone1) {
                    $this->add_col($phone1, !$inserir);
                }
                if ($col_phone2) {
                    $this->add_col($phone2, !$inserir);
                }
                if ($col_address) {
                    $this->add_col($address, !$inserir);
                }
                if ($col_city) {
                    $this->add_col($city, !$inserir);
                }
                $this->add_col($country, !$inserir);

                $errors = "";
                if (!empty($new_user->password)) {
                    $errmsg = '';
                    if (!check_password_policy($new_user->password, $errmsg)) {
                        $errors .= $errmsg;
                    }
                }
                if (empty($new_user->username)) {
                    $errors .= get_string('required');
                } else if (!$user or $user->username !== $new_user->username) {
                    if ($DB->record_exists('user', array('username' => $new_user->username,
                        'mnethostid' => $CFG->mnet_localhost_id))) {
                        $errors .= get_string('usernameexists');
                    }
                    // Check allowed characters.
                    if ($new_user->username !== \core_text::strtolower($new_user->username)) {
                        $errors .= get_string('usernamelowercase');
                    } else {
                        if ($new_user->username !== \core_user::clean_field($new_user->username, 'username')) {
                            $errors .= get_string('invalidusername');
                        }
                    }
                }
                if (!$user or (isset($new_user->email) && $user->email !== $new_user->email)) {
                    if (!validate_email($new_user->email)) {
                        $errors .= get_string('invalidemail');
                    } else if (empty($CFG->allowaccountssameemail)
                        and $DB->record_exists('user', array('email' => $new_user->email,
                            'mnethostid' => $CFG->mnet_localhost_id))) {
                        $errors .= get_string('emailexists');
                    }
                }
                if ($errors != '') {
                    $this->add_col($errors, !$inserir);
                } else if (!$inserir) {
                    $this->add_col(get_string_kopere('userimport_noterror'), !$inserir);
                }

                if ($inserir) {
                    try {
                        $new_user->id = user_create_user($new_user);
                        $this->add_col(get_string_kopere('userimport_inserted'), !$inserir);
                        $iserted = true;
                    } catch (\Exception $e) {
                        $this->add_col($e->error, !$inserir);
                        $iserted = false;
                    }
                    if ($iserted) {
                        $user = $DB->get_record('user', array('id' => $new_user->id), '*', IGNORE_MULTIPLE);
                        set_user_preference('auth_forcepasswordchange', 1, $new_user);

                        $send_event_user_create = $new_user->id;
                    }
                }
            } else {
                $this->add_col($user->username, !$inserir);
                $this->add_col(get_string_kopere('userimport_cript'), !$inserir);
                if ($col_idnumber) {
                    $this->add_col($user->idnumber, !$inserir);
                }
                $this->add_col($user->firstname, !$inserir);
                $this->add_col($user->lastname, !$inserir);
                $this->add_col($user->email, !$inserir);
                if ($col_phone1) {
                    $this->add_col($user->phone1, !$inserir);
                }
                if ($col_phone2) {
                    $this->add_col($user->phone2, !$inserir);
                }
                if ($col_address) {
                    $this->add_col($user->address, !$inserir);
                }
                if ($col_city) {
                    $this->add_col($user->city, !$inserir);
                }
                $this->add_col($user->country, !$inserir);
                $this->add_col(get_string_kopere('userimport_exist'), !$inserir);
            }

            // If exist user, add extras.
            if ($user) {
                $field_items = string_util::clear_all_params('field', [], PARAM_TEXT);
                foreach ($field_items as $key2 => $value2) {
                    if ($value2) {
                        $col = str_replace('col_', '', $value2);
                        $extra_data = $this->get_col_value($col, $data, 0);
                        if ($extra_data) {
                            $user_info_data = new \stdClass();
                            $user_info_data->userid = $user->id;
                            $user_info_data->fieldid = $key2;
                            $user_info_data->data = $extra_data;
                            $user_info_data->dataformat = 0;
                            try {
                                $DB->insert_record('user_info_data', $user_info_data);
                            } catch (\Exception $e) {
                                debugging($e->getMessage());
                            }
                        }
                    }
                }
            }

            $send_event_course_create = 0;
            if ($col_shortnamecourse || $col_idnumbercourse) {
                $course = $this->get_course($shortnamecourse, $idnumbercourse);

                // If exist user, and exist course, add enrol.
                if ($user && $course && $inserir) {

                    enroll_util::enrol($course->id, $user->id, $enroltimestart, $enroltimeend, 0);

                    $send_event_course_create = $course->id;

                    $this->add_col($course->fullname, !$inserir);
                    $this->add_col($enroltimestart, !$inserir);
                    $this->add_col($enroltimeend, !$inserir);

                    if ($col_groupmembers && $groupmembers) {
                        $groups = $this->get_group($groupmembers, $course->id);
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
                                    debugging($e->getMessage());
                                }
                            }
                        }
                        $this->add_col($groups_name, !$inserir);
                    }
                } else if (!$inserir && $course) {
                    $this->add_col($course->fullname, true);
                    $this->add_col($enroltimestart, true);
                    $this->add_col($enroltimeend, true);

                    $groups = $this->get_group($groupmembers, $course->id);
                    $groups_name = '';
                    if ($groups) {
                        $groups_name = $groups->name;
                    }
                    $this->add_col($groups_name, !$inserir);
                }
            }

            if ($send_event_user_create && $send_event_course_create && $user) {
                $data_event = array(
                    'objectid' => $send_event_course_create,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'password' => $password,
                        'courseid' => SITEID
                    ),
                    'context' => \context_user::instance($send_event_course_create)
                );

                try {
                    import_user_created_and_enrol::create($data_event)->trigger();
                } catch (\Exception $e) {
                    debugging($e->getMessage());
                }
            } else if ($send_event_user_create && $user) {
                $data_event = array(
                    'objectid' => $user->id,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'password' => $password,
                        'courseid' => SITEID
                    ),
                    'context' => \context_user::instance($user->id)
                );

                try {
                    import_user_created::create($data_event)->trigger();
                } catch (\Exception $e) {
                    debugging($e->getMessage());
                }
            } else if ($send_event_course_create && $user) {
                $data_event = array(
                    'objectid' => $send_event_course_create,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'courseid' => $send_event_course_create
                    ),
                    'context' => \context_user::instance($user->id)
                );
                try {
                    import_course_enrol::create($data_event)->trigger();
                } catch (\Exception $e) {
                    debugging($e->getMessage());
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
            mensagem::print_info(get_string_kopere('userimport_wait'), 'mensage-proccess');
            $this->create_form_proccess_collums();
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
            end_util::end_script_show();
        }
    }

    /**
     *
     */
    private function create_form_proccess_collums() {

        echo '<div>';
        echo '<div style="float:left;margin-right:14px">';
        $form = new form('usersimport::proccess');
        $form->create_hidden_input('inserir', 1);

        $post = string_util::clear_all_params(null, null, PARAM_TEXT);
        foreach ($post as $key => $value) {
            if ($key == 'POST') {
                // Ignora.
            } else if ($key == 'field') {
                $field_item = string_util::clear_all_params('field', [], PARAM_TEXT);
                foreach ($field_item as $key2 => $value2) {
                    $form->create_hidden_input('field[' . $key2 . ']', $value2);
                }
            } else {
                $form->create_hidden_input($key, $value);
            }
        }
        $form->create_submit_input(get_string_kopere('userimport_dataok'), 'bt-submit-ok');
        $form->close();
        echo '</div>';

        echo '<div style="float:left">';
        $form = new form('usersimport::upload_success');

        $post = string_util::clear_all_params(null, null, PARAM_TEXT);
        foreach ($post as $key => $value) {
            if ($key == 'POST' || $key == 'inserir') {
                // Ignora estes dados.
            } else if ($key == 'field') {
                $field_item = string_util::clear_all_params('field', [], PARAM_TEXT);
                foreach ($field_item as $key2 => $value2) {
                    $form->create_hidden_input('field[' . $key2 . ']', $value2);
                }
            } else {
                $form->create_hidden_input($key, $value);
            }
        }
        $form->create_submit_input(get_string_kopere('userimport_datanotok'), 'btn-danger');
        $form->close();

        echo '</div></div>';
    }

    /**
     * @param $param_name
     * @return mixed|null
     */
    private function get_col_param($param_name) {
        $param = optional_param($param_name, null, PARAM_TEXT);
        if ($param == null) {
            return null;
        }

        return str_replace('col_', '', $param);
    }

    /**
     * @param $col
     * @param $data
     * @param string $default
     * @return string
     */
    private function get_col_value($col, $data, $default = '') {
        if (isset($data[$col])) {
            return $data[$col];
        }

        return $default;
    }

    /**
     * @param $username
     * @param $email
     * @param $idnumber
     * @return mixed|null
     */
    private function get_user($username, $email, $idnumber) {
        global $DB, $CFG;

        if (strlen($username)) {
            $user = $DB->get_record('user', array('username' => $username), '*', IGNORE_MULTIPLE);

            if ($user) {
                return $user;
            }
        }

        if (empty($CFG->allowaccountssameemail)) {
            if (strlen($email)) {
                $user = $DB->get_record('user', array('email' => $email), '*', IGNORE_MULTIPLE);

                if ($user) {
                    return $user;
                }
            }
        }

        if (strlen($idnumber)) {
            $user = $DB->get_record('user', array('idnumber' => $idnumber), '*', IGNORE_MULTIPLE);

            if ($user) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @param $shortnamecourse
     * @param $idnumbercourse
     * @return mixed|null
     */
    private function get_course($shortnamecourse, $idnumbercourse) {
        global $DB;

        if (strlen($idnumbercourse)) {
            $course = $DB->get_record('course', array('idnumber' => $idnumbercourse), '*', IGNORE_MULTIPLE);

            if ($course) {
                return $course;
            }
        }

        if (strlen($shortnamecourse)) {
            $course = $DB->get_record('course', array('shortname' => $shortnamecourse), '*', IGNORE_MULTIPLE);

            if ($course) {
                return $course;
            }
        }

        if (strlen($shortnamecourse)) {
            $course = $DB->get_record('course', array('fullname' => $shortnamecourse), '*', IGNORE_MULTIPLE);

            if ($course) {
                return $course;
            }
        }

        return null;
    }

    /**
     * @param $groupmembers
     * @param $courseid
     * @return mixed|null
     */
    private function get_group($groupmembers, $courseid) {
        global $DB;

        if (strlen($groupmembers)) {
            $groups = $DB->get_record('groups', array('name' => $groupmembers, 'courseid' => $courseid), '*', IGNORE_MULTIPLE);

            if ($groups) {
                return $groups;
            }
        }

        if (strlen($groupmembers)) {
            $groups = $DB->get_record('groups', array('idnumber' => $groupmembers, 'courseid' => $courseid), '*', IGNORE_MULTIPLE);

            if ($groups) {
                return $groups;
            }
        }
        return null;
    }

    /**
     *
     */
    public function print_all_lines() {

        global $CFG;
        ob_clean();

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);
        $separator = optional_param('separator', '', PARAM_TEXT);

        $target_file = $CFG->dataroot . '/kopere/dashboard/tmp/' . $file;
        if (!file_exists($target_file)) {
            header::notfound(get_string_kopere('userimport_filenotfound', $name));
        }

        echo '<!DOCTYPE html>
              <html lang="pt-BR">
              <head>
                  <meta charset="UTF-8">
                  <title>' . $name . '</title>
              </head>
              <body>';

        $count_cols = 0;
        echo '<table border="1">';
        if (($handle = fopen($target_file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, $separator)) !== false) {

                echo '<tr>';
                if ($count_cols == 0) {
                    foreach ($data as $col) {
                        echo '<td>' . $col . '</td>';
                        $count_cols++;
                    }
                } else {
                    for ($i = 0; $i < $count_cols; $i++) {
                        if (strlen($data[$i]) == 0) {
                            echo '<td style="background: #FF9800;">(vazio)</td>';
                        } else {
                            echo '<td>' . $data[$i] . '</td>';
                        }
                    }
                }
                echo '</tr>';
            }
            fclose($handle);
        }
        echo '</table>';

        end_util::end_script_show('</body></html>');
    }

    /**
     * @param $value
     * @param bool $table
     * @param bool $th
     */
    private function add_col($value, $table = true, $th = false) {
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
