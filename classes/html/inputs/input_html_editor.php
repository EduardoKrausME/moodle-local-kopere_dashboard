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
 * @created    10/06/17 23:13
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

use local_kopere_dashboard\html\tinymce;

/**
 * Class input_html_editor
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_html_editor extends input_textarea {
    /**
     * @return input_html_editor
     */
    public static function new_instance() {
        return new input_html_editor();
    }

    /**
     * @return string
     */
    public function to_string() {
        $this->set_style($this->get_style() . ';height:500px');

        $inputid = preg_replace('/[\W]/', '', $this->name);

        $return = parent::to_string();
        $return .= tinymce::create_input_editor("#{$inputid}");

        return $return;
    }
}
