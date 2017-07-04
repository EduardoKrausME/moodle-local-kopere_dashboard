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

use local_kopere_dashboard\html\inputs\IInput;

class Form {

    private $formAction;

    public function __construct($formAction = null, $classExtra = '') {
        $this->formAction = $formAction;
        if ($this->formAction) {
            echo "<form method=\"post\" class=\"validate $classExtra\" enctype=\"multipart/form-data\" action=\"?{$this->formAction}\" >";
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
            echo '<input name="POST" type="hidden" value="true" />';
        }
    }

    public function printRow($titulo, $input, $name = '', $adicionalText = '') {
        echo '<div class="form-group area_' . $name . '">
                  <label for="' . $name . '">' . $titulo . '</label>
                  ' . $input . '
                  <div class="help-block form-text with-errors">' . $adicionalText . '</div>
              </div>';
    }

    public function printPanel($titulo, $panelBody) {
        echo '<div class="form-group">
                  <label>' . $titulo . '</label>
                  <div class="panel panel-default">
                      <div class=" panel-body ">' . $panelBody . '</div>
                  </div>
              </div>';
    }

    public function printRowOne($titulo, $input, $name = '', $adicionalText = '') {
        echo '<div class="form-check area_' . $name . '"">
                  <label for="' . $name . '" class="form-check-label">
                      ' . $input . ' ' . $titulo . '</label>
                  <div class="help-block form-text with-errors form-control-feedback-">' . $adicionalText . '</div>
              </div>';
    }

    public function printSection($sectionTitle) {
        echo '<div class="form-section"><span>' . $sectionTitle . '</span></div>';
    }

    public function printSpacer($height) {
        echo '<div class="form-group" style="height: ' . $height . 'px">&nbsp;</div>';
    }

    public function addInput(IInput $input) {
        if ($input->getType() == 'checkbox') {
            $this->createHiddenInput($input->getName(), 0);
        }

        $this->printRow(
            $input->getTitle(),
            $input->toString(),
            $input->getName(),
            $input->getDescription()
        );
    }

    public function createHiddenInput($name, $value = '') {
        echo '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '"/>';
    }

    public function createSubmitInput($value = '', $class = '', $additionalText = '') {
        $html = '<input name="" class="btn btn-success bt-submit' . $class . '" type="submit" value="' . htmlspecialchars($value) . '" />';
        $this->printRow('', $html, 'btsubmit', $additionalText);
    }

    public function close() {
        if ($this->formAction) {
            echo '</form>';
        }
    }

    public function closeAndAutoSubmitInput($campo) {
        echo "<input id=\"submit_$campo\" name=\"\" type=\"submit\" style='display: none;' />
              <script>
                  $( '#$campo' ).change( function() {
                      $( '#submit_$campo' ).click();
                  });
              </script>";

        $this->close();
    }
}
