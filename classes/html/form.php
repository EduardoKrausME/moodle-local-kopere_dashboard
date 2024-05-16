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

namespace local_kopere_dashboard\html;

use local_kopere_dashboard\html\inputs\i_input;

/**
 * Class form
 *
 * @package local_kopere_dashboard\html
 */
class form {

    /** @var null */
    private $formaction;

    /**
     * Form constructor.
     *
     * @param null $formaction
     * @param string $classextra
     */
    public function __construct($formaction = null, $classextra = '') {
        $this->formaction = $formaction;
        if ($this->formaction) {
            echo "<form method='post' class='validate {$classextra}' enctype='multipart/form-data'
                        action='{$this->formaction}' >";
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
            echo '<input name="POST" type="hidden" value="true" />';
        }
    }

    /**
     * @param        $titulo
     * @param        $input
     * @param string $name
     * @param string $adicionaltext
     */
    public function print_row($titulo, $input, $name = '', $adicionaltext = '', $type = '') {
        if ($titulo) {
            $titulo = "<label for='{$name}'>{$titulo}</label>";
        }

        echo "<div class='form-group area_{$name} type_{$type}'>
                  {$titulo} {$input}
                  <div class='help-block form-text with-errors'>{$adicionaltext}</div>
              </div>";
    }

    /**
     * @param $titulo
     * @param $panelbody
     */
    public function print_panel($titulo, $panelbody) {
        echo "<div class='form-group'>
                  <label>{$titulo}</label>
                  <div class='panel panel-default'>
                      <div class='panel-body'>{$panelbody}</div>
                  </div>
              </div>";
    }

    /**
     * @param        $titulo
     * @param        $input
     * @param string $name
     * @param string $adicionaltext
     */
    public function print_row_one($titulo, $input, $name = '', $adicionaltext = '') {
        echo "<div class='form-check area_{$name}'>
                  <label for='{$name}' class='form-check-label'>
                      {$input} {$titulo}</label>
                  <div class='help-block form-text with-errors form-control-feedback-'>{$adicionaltext}</div>
              </div>";
    }

    /**
     * @param $sectiontitle
     */
    public function print_section($sectiontitle) {
        echo "<div class='form-section'><span>{$sectiontitle}</span></div>";
    }

    /**
     * @param $height
     */
    public function print_spacer($height) {
        echo "<div class='form-group' style='height: {$height}px'>&nbsp;</div>";
    }

    /**
     * @param i_input $input
     */
    public function add_input(i_input $input) {
        $this->print_row(
            $input->get_title(),
            $input->to_string(),
            $input->get_name(),
            $input->get_description(),
            $input->get_type()
        );
    }

    /**
     * @param        $name
     * @param string $value
     */
    public function create_hidden_input($name, $value = '') {
        echo "<input type='hidden' id='hidden_{$name}' name='{$name}' id='{$name}' value='" . htmlspecialchars($value) . "'/>";
    }

    /**
     * @param string $value
     * @param string $class
     * @param string $additionaltext
     *
     * @throws \coding_exception
     */
    public function create_submit_input($value = '', $class = '', $additionaltext = '') {
        if (AJAX_SCRIPT) {
            echo "<div class='modal-footer margin-form'>
                      <button class='btn btn-default' data-dismiss='modal'>" . get_string('cancel') . "</button>
                      <input type='submit' class='btn btn-primary margin-left-15' value='{$value}'>
                  </div>";
        } else {
            $html = "<input name='' class='btn btn-success bt-submit {$class}' type='submit' value='" .
                htmlspecialchars($value) . "' />";
            $this->print_row('', $html, 'btsubmit', $additionaltext);
        }
    }

    /**
     *
     */
    public function close() {
        if ($this->formaction) {
            echo '</form>';
        }
    }

    /**
     * @param $campo
     */
    public function close_and_auto_submit_input($campo) {
        global $PAGE;
        echo "<input id='submit_{$campo}' name='' type='submit' style='display: none;' />";

        $PAGE->requires->js_call_amd('local_kopere_dashboard/form_exec', 'form_close_and_auto_submit_input', [$campo]);

        $this->close();
    }

    /**
     * @return bool
     * @throws \coding_exception
     */
    public static function check_post() {
        return optional_param('POST', false, PARAM_TEXT);

    }
}
