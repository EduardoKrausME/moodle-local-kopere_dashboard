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
 * @created    20/05/17 18:20
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\report\files;
use local_kopere_dashboard\util\bytes_util;
use local_kopere_dashboard\util\dashboard_util;

defined('MOODLE_INTERNAL') || die();

/**
 * Class performancemonitor
 * @package local_kopere_dashboard
 */
class performancemonitor {

    /**
     * @var bool
     */
    CONST CRON = false;

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function start() {
        dashboard_util::add_breadcrumb("Performance Monitor");
        dashboard_util::start_page();

        self::load_monitor();

        dashboard_util::end_page();
    }

    public static function load_monitor() {
    }
}

