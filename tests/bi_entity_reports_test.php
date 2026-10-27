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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * Tests graceful degradation when the optional Kopere BI is unavailable.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use advanced_testcase;
use core_component;

/**
 * @covers \local_kopere_dashboard\bi_entity_reports
 */
final class bi_entity_reports_test extends advanced_testcase {
    /**
     * User and course details must work when the BI plugin is not installed.
     */
    public function test_optional_bi_is_not_required(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        if (core_component::get_plugin_directory("local", "kopere_bi")) {
            $this->markTestSkipped("This test requires a Moodle installation without local_kopere_bi.");
        }

        $this->assertNull(bi_entity_reports::for_user(2, []));
        $this->assertNull(bi_entity_reports::for_course(2, false));
    }
}
