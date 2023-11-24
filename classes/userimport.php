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
use local_kopere_dashboard\util\user_util;
use local_kopere_dashboard\util\string_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\vo\kopere_dashboard_events;

/**
 * Class userimport
 * @package local_kopere_dashboard
 */
class userimport {

    /**
     *
     */
    public function dashboard() {
        global $CFG;

        dashboard_util::add_breadcrumb(get_string_kopere('userimport_title'));
        dashboard_util::start_page();

        echo '<div class="element-box table-responsive">';

        echo '<form action="?classname=userimport&method=upload" id="dropzone" class="dropzone needsclick dz-clickable">
                  <div class="dz-message needsclick">
                      ' . get_string_kopere('userimport_upload') . '
                  </div>
              </form>
              <br><br><br><br>

              <script src="' . $CFG->wwwroot . '/local/kopere_dashboard/assets/dropzone/js/dropzone.js"></script>
              <script>
                  new Dropzone("#dropzone", {
                      maxFiles : 1,
                      acceptedFiles: ".csv",
                      success: function(file, response){
                          console.log(response);
                          location.href = "?classname=userimport&method=upload_success"+response;
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

        @mkdir("{$CFG->dataroot}/kopere");
        @mkdir("{$CFG->dataroot}/kopere/dashboard");
        @mkdir("{$CFG->dataroot}/kopere/dashboard/tmp");

        $file = time() . '.csv';
        $targetfile = "{$CFG->dataroot}/kopere/dashboard/tmp/{$file}";
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetfile)) {
            ob_clean();
            end_util::end_script_show("&file={$file}&name=" . urlencode($_FILES['file']['name']));
        } else {
            ob_clean();
            header('HTTP/1.0 404 Not Found');
            end_util::end_script_show(get_string_kopere('userimport_moveuploadedfile_error'));
        }
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \dml_exception
     */
    public function upload_success() {
        global $CFG;

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);

        dashboard_util::add_breadcrumb(get_string_kopere('userimport_title'), '?classname=userimport&method=dashboard');
        dashboard_util::add_breadcrumb($name);
        dashboard_util::add_breadcrumb(get_string_kopere('userimport_title_proccess', $name));
        dashboard_util::start_page();

        echo '<div class="element-box table-responsive">';

        $targetfile = "{$CFG->dataroot}/kopere/dashboard/tmp/{$file}";
        if (!file_exists($targetfile)) {
            header::notfound(get_string_kopere('userimport_filenotfound', $name));
        }

        $csvcontent = file_get_contents($targetfile, false, null, 0, 2000);

        if (count(explode(";", $csvcontent)) > count(explode(",", $csvcontent))) {
            $separator = ";";
        } else {
            $separator = ",";
        }

        if (count(explode($separator, $csvcontent)) < 5) {
            mensagem::print_danger(get_string_kopere('userimport_separator_error'));
            dashboard_util::end_page();
            return;
        }

        title_util::print_h3('userimport_first10');

        $countrow = 0;
        $countcols = 0;
        $cols = array();
        echo '<table class="table table-bordered">';
        $handle = fopen($targetfile, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== false) {

            echo '<tr>';
            if ($countcols == 0) {
                foreach ($data as $col) {
                    echo "<th>{$col}</th>";
                    $cols[] = array($countcols, $col);
                    $countcols++;
                }
            } else {
                for ($i = 0; $i < $countcols; $i++) {
                    if (strlen($data[$i]) == 0) {
                        echo '<td style="background: #FF9800;">(vazio)</td>';
                    } else {
                        echo "<td>{$data[$i]}</td>";
                    }
                }
            }
            echo '</tr>';

            $countrow++;
            if ($countrow == (10 + 1)) {
                break;
            }
        }
        fclose($handle);
        echo '</table>';

        echo "<p><a target='_blank' href='?classname=userimport&method=printuserimportle={$file}" .
            "&name={$name}&separator={$separator}'>" . get_string_kopere('userimport_linkall') . '</a></p>';

        $this->get_events();
        $this->create_form_collums($cols, $separator);

        dashboard_util::end_page();
    }

    /**
     * @throws \dml_exception
     */
    private function get_events() {
        global $DB;

        title_util::print_h2('userimport_messages');

        /** @var kopere_dashboard_events $importcourseenrol */
        $importcourseenrol = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_course_enrol',
        ), '*', IGNORE_MULTIPLE);

        title_util::print_h3('userimport_import_course_enrol_name');
        if ($importcourseenrol) {
            if ($importcourseenrol->status == 1) {
                $link = "<a target='_blank' href='?classname=notifications&method=add_segunda_etapa&id={$importcourseenrol->id}'>" .
                    $importcourseenrol->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = "<a target='_blank' href='?classname=notifications&method=add_segunda_etapa&id={$importcourseenrol->id}'>" .
                    $importcourseenrol->subject . '</a>';
                mensagem::print_info(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            mensagem::print_warning(get_string_kopere('userimport_notreceivemessage'));
        }

        /** @var kopere_dashboard_events $importusercreated */
        $importusercreated = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_user_created'
        ), '*', IGNORE_MULTIPLE);

        title_util::print_h3('userimport_import_user_created_name');
        if ($importusercreated) {
            if ($importusercreated->status == 1) {
                $link = "<a target='_blank' href='?classname=notifications&method=add_segunda_etapa&id={$importusercreated->id}'>" .
                    $importusercreated->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = "<a target='_blank' href='?classname=notifications&method=add_segunda_etapa&id={$importusercreated->id}'>" .
                    $importusercreated->subject . '</a>';
                mensagem::print_info(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            mensagem::print_warning(get_string_kopere('userimport_notreceivemessage'));
        }

        /** @var kopere_dashboard_events $importusercreatedandenrol */
        $importusercreatedandenrol = $DB->get_record('kopere_dashboard_events', array(
            'event' => '\\local_kopere_dashboard\\event\\import_user_created_and_enrol'
        ), '*', IGNORE_MULTIPLE);

        title_util::print_h3('userimport_import_user_created_and_enrol_name');
        if ($importusercreatedandenrol) {
            if ($importusercreatedandenrol->status == 1) {
                $link = '<a target="_blank" href="?classname=notifications&method=add_segunda_etapa&id=' .
                    $importusercreatedandenrol->id . '">' .
                    $importusercreatedandenrol->subject . '</a>';
                echo get_string_kopere('userimport_receivemessage', $link);
            } else {
                $link = '<a target="_blank" href="?classname=notifications&method=add_segunda_etapa&id=' .
                    $importusercreatedandenrol->id . '">' .
                    $importusercreatedandenrol->subject . '</a>';
                mensagem::print_info(get_string_kopere('userimport_receivemessage', $link));
            }
        } else {
            mensagem::print_warning(get_string_kopere('userimport_notreceivemessage'));
        }
    }

    /**
     * @param $cols
     * @param $separator
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private function create_form_collums($cols, $separator) {
        global $DB;

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);

        title_util::print_h2('userimport_referencedata');

        $form = new form('?classname=userimport&method=proccess');
        $form->create_hidden_input('name', $name);
        $form->create_hidden_input('file', $file);
        $form->create_hidden_input('separator', $separator);

        $values = array(array(
            'key' => '',
            'value' => get_string_kopere('userimport_colselect')
        ));
        $collnumber = 'A';
        foreach ($cols as $col) {
            $values[] = array(
                "key' => 'col_{$col[0]}",
                'value' => get_string_kopere('userimport_colname', "$collnumber ({$col[1]})")
            );
            $collnumber++;
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
                ->set_title(get_string($field) . " ({$field})");

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

            $fielditem = string_util::clear_all_params('field', [], PARAM_TEXT);

            foreach ($fields as $field) {

                $input = input_select::new_instance()
                    ->set_name("field[{$field->id}]")
                    ->set_values($values)
                    ->set_description($field->name)
                    ->set_title($field->name);
                if (isset($fielditem[$field->id])) {
                    $input->set_value($fielditem[$field->id]);
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
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \Exception
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

        $colusername = $this->get_col_param('username');
        $colpassword = $this->get_col_param('password');
        $colidnumber = $this->get_col_param('idnumber');
        $colfirstname = $this->get_col_param('firstname');
        $collastname = $this->get_col_param('lastname');
        $colemail = $this->get_col_param('email');
        $colphone1 = $this->get_col_param('phone1');
        $colphone2 = $this->get_col_param('phone2');
        $coladdress = $this->get_col_param('address');
        $colcity = $this->get_col_param('city');
        $colcountry = $this->get_col_param('country');
        $colshortnamecourse = $this->get_col_param('shortnamecourse');
        $colidnumbercourse = $this->get_col_param('idnumbercourse');
        $colgroupmembers = $this->get_col_param('groupmembers');
        $colenroltimestart = $this->get_col_param('enroltimestart');
        $colenroltimeend = $this->get_col_param('enroltimeend');

        if (!$inserir) {
            dashboard_util::add_breadcrumb(get_string_kopere('userimport_title'), '?classname=userimport&method=dashboard');
            dashboard_util::add_breadcrumb($name);
            dashboard_util::add_breadcrumb(get_string_kopere('userimport_title_proccess', $name));
            dashboard_util::start_page();
            $this->create_form_proccess_collums();
            echo '<table class="table table-bordered"><tr>';
        } else {
            ob_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"{$name}\"");
        }

        $this->add_col('username', !$inserir, true);
        $this->add_col('password', !$inserir, true);
        if ($colidnumber) {
            $this->add_col('idnumber', !$inserir, true);
        }
        $this->add_col('firstname', !$inserir, true);
        $this->add_col('lastname', !$inserir, true);
        $this->add_col('email', !$inserir, true);
        if ($colphone1) {
            $this->add_col('phone1', !$inserir, true);
        }
        if ($colphone2) {
            $this->add_col('phone2', !$inserir, true);
        }
        if ($coladdress) {
            $this->add_col('address', !$inserir, true);
        }
        if ($colcity) {
            $this->add_col('city', !$inserir, true);
        }
        $this->add_col('country', !$inserir, true);
        $this->add_col('userstatus', !$inserir, true);
        if ($colshortnamecourse || $colidnumbercourse) {
            $this->add_col('course', !$inserir, true);
            $this->add_col('enroltimestart', !$inserir, true);
            $this->add_col('enroltimeend', !$inserir, true);
            if ($colgroupmembers) {
                $this->add_col('groupmembers', !$inserir, true);
            }
        }

        if (!$inserir) {
            echo '</tr>';
        } else {
            echo "\n";
        }

        $isfirst = true;
        $targetfile = "{$CFG->dataroot}/kopere/dashboard/tmp/{$file}";
        if (!file_exists($targetfile)) {
            header::notfound(get_string_kopere('userimport_filenotfound', $name));
        }

        $handle = fopen($targetfile, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== false) {
            if ($isfirst) {
                $isfirst = false;
                continue;
            }

            $username = $this->get_col_value($colusername, $data);
            $password = $this->get_col_value($colpassword, $data);
            $idnumber = $this->get_col_value($colidnumber, $data);
            $firstname = $this->get_col_value($colfirstname, $data);
            $lastname = $this->get_col_value($collastname, $data);
            $email = $this->get_col_value($colemail, $data);
            $phone1 = $this->get_col_value($colphone1, $data);
            $phone2 = $this->get_col_value($colphone2, $data);
            $address = $this->get_col_value($coladdress, $data);
            $city = $this->get_col_value($colcity, $data, '.');
            $country = $this->get_col_value($colcountry, $data, $USER->country);
            $shortnamecourse = $this->get_col_value($colshortnamecourse, $data);
            $idnumbercourse = $this->get_col_value($colidnumbercourse, $data);
            $groupmembers = $this->get_col_value($colgroupmembers, $data);
            $enroltimestart = $this->get_col_value($colenroltimestart, $data, 0);
            $enroltimeend = $this->get_col_value($colenroltimeend, $data, 0);

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
            $sendeventusercreate = 0;
            if (!$user) {
                // Not search! create user.

                if ($username == null) {
                    $username = $email;
                }

                /** @var \stdClass $user */
                $user = null;
                $newuser = new \stdClass();

                $newuser->idnumber = $idnumber;
                $newuser->firstname = $firstname;
                $newuser->lastname = $lastname;
                $newuser->username = strtolower($username);
                $newuser->email = strtolower($email);
                $newuser->address = $address;
                $newuser->city = $city;
                $newuser->country = $country;
                $newuser->phone1 = $phone1;
                $newuser->phone2 = $phone2;

                $newuser->auth = 'manual';
                $newuser->confirmed = 1;
                $newuser->mnethostid = $CFG->mnet_localhost_id;

                $newuser = user_util::explode_name($newuser);

                if ($inserir) {
                    if (strlen($password) < 5) {
                        $password = '#' . random_string();
                    }
                    $newuser->password = $password;
                } else if (strlen($password) < 5) {
                    $password = get_string_kopere('userimport_passcreate');
                }

                $this->add_col($username, !$inserir);
                $this->add_col($password, !$inserir);
                if ($colidnumber) {
                    $this->add_col($idnumber, !$inserir);
                }
                $this->add_col($firstname, !$inserir);
                $this->add_col($lastname, !$inserir);
                $this->add_col($email, !$inserir);
                if ($colphone1) {
                    $this->add_col($phone1, !$inserir);
                }
                if ($colphone2) {
                    $this->add_col($phone2, !$inserir);
                }
                if ($coladdress) {
                    $this->add_col($address, !$inserir);
                }
                if ($colcity) {
                    $this->add_col($city, !$inserir);
                }
                $this->add_col($country, !$inserir);

                $errors = user_util::validate_new_user($newuser);
                if (!$user || $user->username !== $newuser->username) {
                    if ($DB->record_exists('user', array('username' => $newuser->username,
                        'mnethostid' => $CFG->mnet_localhost_id))) {
                        $errors .= get_string('usernameexists');
                    }
                    // Check allowed characters.
                    if ($newuser->username !== \core_text::strtolower($newuser->username)) {
                        $errors .= get_string('usernamelowercase');
                    } else {
                        if ($newuser->username !== \core_user::clean_field($newuser->username, 'username')) {
                            $errors .= get_string('invalidusername');
                        }
                    }
                }

                if ($errors != '') {
                    $this->add_col($errors, !$inserir);
                } else if (!$inserir) {
                    $this->add_col(get_string_kopere('userimport_noterror'), !$inserir);
                }

                if ($inserir) {
                    try {
                        $newuser->id = user_create_user($newuser);
                        $this->add_col(get_string_kopere('userimport_inserted'), !$inserir);
                        $iserted = true;
                    } catch (\Exception $e) {
                        $this->add_col($e->getMessage(), !$inserir);
                        $iserted = false;
                    }
                    if ($iserted) {
                        $user = $DB->get_record('user', array('id' => $newuser->id), '*', IGNORE_MULTIPLE);
                        set_user_preference('auth_forcepasswordchange', 1, $newuser);

                        $sendeventusercreate = $newuser->id;
                    }
                }
            } else {
                $this->add_col($user->username, !$inserir);
                $this->add_col(get_string_kopere('userimport_cript'), !$inserir);
                if ($colidnumber) {
                    $this->add_col($user->idnumber, !$inserir);
                }
                $this->add_col($user->firstname, !$inserir);
                $this->add_col($user->lastname, !$inserir);
                $this->add_col($user->email, !$inserir);
                if ($colphone1) {
                    $this->add_col($user->phone1, !$inserir);
                }
                if ($colphone2) {
                    $this->add_col($user->phone2, !$inserir);
                }
                if ($coladdress) {
                    $this->add_col($user->address, !$inserir);
                }
                if ($colcity) {
                    $this->add_col($user->city, !$inserir);
                }
                $this->add_col($user->country, !$inserir);
                $this->add_col(get_string_kopere('userimport_exist'), !$inserir);
            }

            // If exist user, add extras.
            if ($user) {
                $fielditems = string_util::clear_all_params('field', [], PARAM_TEXT);
                foreach ($fielditems as $key2 => $value2) {
                    if ($value2) {
                        $col = str_replace('col_', '', $value2);
                        $extradata = $this->get_col_value($col, $data, 0);
                        if ($extradata) {
                            $userinfodata = new \stdClass();
                            $userinfodata->userid = $user->id;
                            $userinfodata->fieldid = $key2;
                            $userinfodata->data = $extradata;
                            $userinfodata->dataformat = 0;
                            try {
                                $DB->insert_record('user_info_data', $userinfodata);
                            } catch (\Exception $e) {
                                debugging($e->getMessage());
                            }
                        }
                    }
                }
            }

            $sendeventcoursecreate = 0;
            if ($colshortnamecourse || $colidnumbercourse) {
                $course = $this->get_course($shortnamecourse, $idnumbercourse);

                // If exist user, and exist course, add enrol.
                if ($user && $course && $inserir) {

                    enroll_util::enrol($course, $user, $enroltimestart, $enroltimeend, ENROL_INSTANCE_ENABLED);

                    $sendeventcoursecreate = $course->id;

                    $this->add_col($course->fullname, !$inserir);
                    $this->add_col($enroltimestart, !$inserir);
                    $this->add_col($enroltimeend, !$inserir);

                    if ($colgroupmembers && $groupmembers) {
                        $groups = $this->get_group($groupmembers, $course->id);
                        $groupsname = '';
                        if ($groups) {
                            $groupsname = $groups->name;

                            if (!$DB->get_record('groups_members',
                                array('groupid' => $groups->id, 'userid' => $user->id), '*', IGNORE_MULTIPLE)) {
                                $groupsmembers = new \stdClass();
                                $groupsmembers->groupid = $groups->id;
                                $groupsmembers->userid = $user->id;
                                $groupsmembers->timeadded = time();
                                $groupsmembers->component = '';
                                $groupsmembers->itemid = 0;
                                try {
                                    $DB->insert_record('groups_members', $groupsmembers);
                                } catch (\Exception $e) {
                                    debugging($e->getMessage());
                                }
                            }
                        }
                        $this->add_col($groupsname, !$inserir);
                    }
                } else if (!$inserir && $course) {
                    $this->add_col($course->fullname, true);
                    $this->add_col($enroltimestart, true);
                    $this->add_col($enroltimeend, true);

                    $groups = $this->get_group($groupmembers, $course->id);
                    $groupsname = '';
                    if ($groups) {
                        $groupsname = $groups->name;
                    }
                    $this->add_col($groupsname, !$inserir);
                }
            }

            if ($sendeventusercreate && $sendeventcoursecreate && $user) {
                $dataevent = array(
                    'userid' => $user->id,
                    'objectid' => $sendeventcoursecreate,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'password' => $password,
                        'courseid' => SITEID
                    ),
                    'context' => \context_user::instance($sendeventcoursecreate)
                );

                try {
                    import_user_created_and_enrol::create($dataevent)->trigger();
                } catch (\Exception $e) {
                    debugging($e->getMessage());
                }
            } else if ($sendeventusercreate && $user) {
                $dataevent = array(
                    'userid' => $user->id,
                    'objectid' => $user->id,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'password' => $password,
                        'courseid' => SITEID
                    ),
                    'context' => \context_user::instance($user->id)
                );

                try {
                    import_user_created::create($dataevent)->trigger();
                } catch (\Exception $e) {
                    debugging($e->getMessage());
                }
            } else if ($sendeventcoursecreate && $user) {
                $dataevent = array(
                    'userid' => $user->id,
                    'objectid' => $sendeventcoursecreate,
                    'relateduserid' => $user->id,
                    'other' => array(
                        'courseid' => $sendeventcoursecreate
                    ),
                    'context' => \context_user::instance($user->id)
                );
                try {
                    import_course_enrol::create($dataevent)->trigger();
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
            unlink($targetfile);
            end_util::end_script_show();
        }
    }

    /**
     * @throws \coding_exception
     */
    private function create_form_proccess_collums() {

        echo '<div>';
        echo '<div style="float:left;margin-right:14px">';
        $form = new form('?classname=userimport&method=proccess');
        $form->create_hidden_input('inserir', 1);

        $post = string_util::clear_all_params(null, null, PARAM_TEXT);
        foreach ($post as $key => $value) {
            if ($key == 'POST') {
                continue;
            } else if ($key == 'field') {
                $fielditem = string_util::clear_all_params('field', [], PARAM_TEXT);
                foreach ($fielditem as $key2 => $value2) {
                    $form->create_hidden_input("field[{$key2}]", $value2);
                }
            } else {
                $form->create_hidden_input($key, $value);
            }
        }
        $form->create_submit_input(get_string_kopere('userimport_dataok'), 'bt-submit-ok');
        $form->close();
        echo '</div>';

        echo '<div style="float:left">';
        $form = new form('?classname=userimport&method=upload_success');

        $post = string_util::clear_all_params(null, null, PARAM_TEXT);
        foreach ($post as $key => $value) {
            if ($key == 'POST' || $key == 'inserir') {
                continue;
            } else if ($key == 'field') {
                $fielditem = string_util::clear_all_params('field', [], PARAM_TEXT);
                foreach ($fielditem as $key2 => $value2) {
                    $form->create_hidden_input("field[{$key2}]", $value2);
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
     * @param $paramname
     * @return mixed|null
     * @throws \coding_exception
     */
    private function get_col_param($paramname) {
        $param = optional_param($paramname, null, PARAM_TEXT);
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
     * @return \stdClass
     * @throws \dml_exception
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
     * @throws \dml_exception
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
     * @throws \dml_exception
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
     * @throws \coding_exception
     */
    public function print_all_lines() {

        global $CFG;
        ob_clean();

        $name = optional_param('name', '', PARAM_TEXT);
        $file = optional_param('file', '', PARAM_TEXT);
        $separator = optional_param('separator', '', PARAM_TEXT);

        $targetfile = "{$CFG->dataroot}/kopere/dashboard/tmp/{$file}";
        if (!file_exists($targetfile)) {
            header::notfound(get_string_kopere('userimport_filenotfound', $name));
        }

        echo '<!DOCTYPE html>
              <html lang="pt-BR">
              <head>
                  <meta charset="UTF-8">
                  <title>' . $name . '</title>
              </head>
              <body>';

        $countcols = 0;
        echo '<table border="1">';
        if (($handle = fopen($targetfile, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, $separator)) !== false) {

                echo '<tr>';
                if ($countcols == 0) {
                    foreach ($data as $col) {
                        echo "<td>{$col}</td>";
                        $countcols++;
                    }
                } else {
                    for ($i = 0; $i < $countcols; $i++) {
                        if (strlen($data[$i]) == 0) {
                            echo '<td style="background: #FF9800;">(vazio)</td>';
                        } else {
                            echo "<td>{$data[$i]}</td>";
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
                echo "<th>{$value}</th>";
            } else {
                echo "<td>{$value}</td>";
            }
        } else {
            echo "\"{$value}\",";
        }
    }
}
