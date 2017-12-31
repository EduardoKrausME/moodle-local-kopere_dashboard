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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\inputs\i_input;

/**
 * Class form
 *
 * @package local_kopere_dashboard\html
 */
class form {

    /**
     * @var null
     */
    private $formaction;

    /**
     * Form constructor.
     *
     * @param null $formaction
     * @param string $class_extra
     */
    public function __construct($formaction = null, $class_extra = '') {
        $this->formaction = $formaction;
        if ($this->formaction) {
            echo "<form method=\"post\" class=\"validate $class_extra\" enctype=\"multipart/form-data\"
                        action=\"?{$this->formaction}\" >";
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
            echo '<input name="POST" type="hidden" value="true" />';
        }
    }

    /**
     * @param        $titulo
     * @param        $input
     * @param string $name
     * @param string $adicional_text
     */
    public function print_row($titulo, $input, $name = '', $adicional_text = '') {
        if ($titulo) {
            $titulo = '<label for="' . $name . '">' . $titulo . '</label>';
        }

        echo '<div class="form-group area_' . $name . '">
                  ' . $titulo . $input . '
                  <div class="help-block form-text with-errors">' . $adicional_text . '</div>
              </div>';
    }

    /**
     * @param $titulo
     * @param $panel_body
     */
    public function print_panel($titulo, $panel_body) {
        echo '<div class="form-group">
                  <label>' . $titulo . '</label>
                  <div class="panel panel-default">
                      <div class=" panel-body ">' . $panel_body . '</div>
                  </div>
              </div>';
    }

    /**
     * @param        $titulo
     * @param        $input
     * @param string $name
     * @param string $adicional_text
     */
    public function print_row_one($titulo, $input, $name = '', $adicional_text = '') {
        echo '<div class="form-check area_' . $name . '"">
                  <label for="' . $name . '" class="form-check-label">
                      ' . $input . ' ' . $titulo . '</label>
                  <div class="help-block form-text with-errors form-control-feedback-">' . $adicional_text . '</div>
              </div>';
    }

    /**
     * @param $section_title
     */
    public function print_section($section_title) {
        echo '<div class="form-section"><span>' . $section_title . '</span></div>';
    }

    /**
     * @param $height
     */
    public function print_spacer($height) {
        echo '<div class="form-group" style="height: ' . $height . 'px">&nbsp;</div>';
    }

    /**
     * @param i_input $input
     */
    public function add_input(i_input $input) {
        $this->print_row(
            $input->get_title(),
            $input->to_string(),
            $input->get_name(),
            $input->get_description()
        );
    }

    /**
     * @param        $name
     * @param string $value
     */
    public function create_hidden_input($name, $value = '') {
        echo '<input type="hidden" id="hidden_' . $name . '" name="' . $name .
            '" id="' . $name . '" value="' . htmlspecialchars($value) . '"/>';
    }

    /**
     * @param string $value
     * @param string $class
     * @param string $additional_text
     */
    public function create_submit_input($value = '', $class = '', $additional_text = '') {
        $html = '<input name="" class="btn btn-success bt-submit ' . $class .
            '" type="submit" value="' . htmlspecialchars($value) . '" />';
        $this->print_row('', $html, 'btsubmit', $additional_text);
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
        echo "<input id=\"submit_$campo\" name=\"\" type=\"submit\" style='display: none;' />
              <script>
                  $( '#$campo' ).change( function() {
                      $( '#submit_$campo' ).click();
                  });
              </script>";

        $this->close();
    }

    /**
     * @return bool
     */
    public static function check_post() {
        return optional_param('POST', false, PARAM_TEXT);

    }
}
