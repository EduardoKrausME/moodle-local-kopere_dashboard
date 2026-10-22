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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Tests for the PHP October 2026 security advisory.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use advanced_testcase;
use local_kopere_dashboard\security\php_security_notice;

/**
 * Checks PHP branch thresholds and administrator-only visibility.
 *
 * @covers \local_kopere_dashboard\security\php_security_notice::is_affected_version
 * @covers \local_kopere_dashboard\security\php_security_notice::fixed_version
 * @return void
 */
final class php_security_notice_test extends advanced_testcase {
    /**
     * Affected branches must be compared independently.
     *
     * @return void
     */
    public function test_php_security_thresholds(): void {
        $this->assertTrue(php_security_notice::is_affected_version(80199));
        $this->assertTrue(php_security_notice::is_affected_version(80234));
        $this->assertTrue(php_security_notice::is_affected_version(80335));
        $this->assertTrue(php_security_notice::is_affected_version(80426));
        $this->assertTrue(php_security_notice::is_affected_version(80511));
        $this->assertFalse(php_security_notice::is_affected_version(80235));
        $this->assertFalse(php_security_notice::is_affected_version(80336));
        $this->assertFalse(php_security_notice::is_affected_version(80427));
        $this->assertFalse(php_security_notice::is_affected_version(80512));
        $this->assertFalse(php_security_notice::is_affected_version(80600));
        $this->assertSame("8.2.35", php_security_notice::fixed_version(80234));
        $this->assertSame("8.5.12", php_security_notice::fixed_version(80511));
        $this->assertNull(php_security_notice::fixed_version(80199));
    }

    /**
     * Site admin status does not extend to students or other authenticated users.
     *
     * @return void
     */
    public function test_security_notice_visible_only_to_admin_on_affected_runtime(): void {
        $this->resetAfterTest(true);
        $this->setAdminUser();

        $affected = php_security_notice::is_affected_version(PHP_VERSION_ID);
        $this->assertSame($affected, php_security_notice::should_show());

        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertFalse(php_security_notice::should_show());
        $this->setGuestUser();
        $this->assertFalse(php_security_notice::should_show());
    }

    /**
     * Dismiss only the currently installed PHP version.
     *
     * @return void
     */
    public function test_security_notice_dismissal(): void {
        $this->resetAfterTest(true);
        $this->setAdminUser();
        set_user_preference(php_security_notice::PREFERENCE_DISMISSED_VERSION, PHP_VERSION);
        $this->assertFalse(php_security_notice::should_show());

        set_user_preference(php_security_notice::PREFERENCE_DISMISSED_VERSION, "a-different-php-version");
        $this->assertSame(
            php_security_notice::is_affected_version(PHP_VERSION_ID),
            php_security_notice::should_show()
        );
    }
}
