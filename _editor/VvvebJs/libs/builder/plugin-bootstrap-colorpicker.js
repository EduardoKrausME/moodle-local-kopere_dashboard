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
 * plugin-bootstrap-colorpicker.js
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

let ColorInput = {
    ...ColorInput, ...{

        events : [
            ["change", "onChange", "input"],
        ],

        setValue : function(value) {
            let color = this.rgb2hex(value);
            $('input', this.element).val();
            $('i', this.element).css('background-color', value);
        },

        init : function(data) {
            let colorinput = this.render("bootstrap-color-picker-input", data);
            let colorpicker = $('.input-group', colorinput).colorpicker();
            return colorinput;
        },
    }
)
;

