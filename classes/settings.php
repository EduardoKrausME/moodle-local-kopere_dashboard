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
 * Settings file
 *
 * introduced 13/05/17 13:28
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\inputs\input_htmleditor;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\string_util;

/**
 * Class Settings
 *
 * @package local_kopere_dashboard
 */
class settings {

    /**
     * Function save
     *
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function save() {

        require_sesskey();

        $post = string_util::clear_all_params(null, null, PARAM_RAW);
        foreach ($post as $keyname => $value) {
            $save = true;
            switch ($keyname) {
                case "POST":
                case "action":
                case "redirect":
                    $save = false;
                    break;
                case "kopere_pay-meiodeposito-conta":
                case "formulario_pedir_aceite":
                    if (!input_htmleditor::$editorhtml) {
                        $value = "<div style=\"white-space:break-spaces;\">{$value}</div>";
                    }
                    break;
            }

            if ($save) {
                if (is_array($value)) {
                    set_config($keyname, implode(",", $value), "local_kopere_dashboard");
                } else {
                    set_config($keyname, $value, "local_kopere_dashboard");
                }
            }
        }

        message::schedule_message_success(get_string("setting_saved", "local_kopere_dashboard"));

        $redirect = optional_param("redirect", false, PARAM_TEXT);
        if ($redirect) {
            header::location("?{$redirect}");
        }
    }
}
