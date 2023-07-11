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
 * @created    01/06/17 15:44
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\util\dashboard_util;

/**
 * Class about
 * @package local_kopere_dashboard
 */
class about {

    /**
     *
     */
    public function dashboard() {
        dashboard_util::add_breadcrumb(get_string_kopere('about_title'));
        dashboard_util::start_page();

        echo '<div class="element-box">
                  <p><img src="https://www.eduardokraus.com/logos/kopere_dashboard.svg" style="max-width: 100%" /></p>
                  <p>&nbsp;</p>
                  <p>' . get_string_kopere('about_project') . '
                     <a target="_blank" href="https://www.eduardokraus.com/kopere-dashboard">Eduardo Kraus</a>.</p>
                  <p>' . get_string_kopere('about_code') . '
                     <a target="_blank" href="https://github.com/EduardoKrausME/moodle-local-kopere_dashboard"
                     >github.com/EduardoKrausME/moodle-local-kopere_dashboard</a>.</p>
                  <p>' . get_string_kopere('about_help') . '
                     <a target="_blank" href="https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/wiki"
                     >Wiki</a>.</p>
                  <p>' . get_string_kopere('about_bug') . '
                     <a href="https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/issues"
                        target="_blank">issue</a>.</p>
              </div>';

        dashboard_util::end_page();
    }
}
