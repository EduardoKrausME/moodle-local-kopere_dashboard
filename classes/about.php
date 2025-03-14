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
 * about file
 *
 * introduced 01/06/17 15:44
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\util\dashboard_util;

/**
 * Class about
 *
 * @package local_kopere_dashboard
 */
class about {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $OUTPUT;

        dashboard_util::add_breadcrumb(get_string("about_title", "local_kopere_dashboard"));
        dashboard_util::start_page();

        echo $OUTPUT->render_from_template("local_kopere_dashboard/about_dashboard", []);

        dashboard_util::end_page();
    }
}
