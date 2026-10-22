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
 * PHP security advisory visibility for the Kopere Dashboard homepage.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\security;

/**
 * Determines whether the October 2026 critical PHP advisory applies.
 */
final class php_security_notice {
    /** @var string User preference is scoped to the PHP runtime version. */
    public const PREFERENCE_DISMISSED_VERSION = "local_kopere_dashboard_php_security_dismissed_version";

    /**
     * First upstream release with both CVE-2026-109618 and CVE-2026-109619 fixes.
     * Keys are PHP major/minor branch numbers (e.g. 804 for PHP 8.4).
     */
    private const FIXED_VERSIONS = [
        802 => 80235,
        803 => 80336,
        804 => 80427,
        805 => 80512,
    ];

    /**
     * Check the PHP interpreter handling this web request, not the PHP CLI.
     *
     * @return bool
     */
    public static function should_show(): bool {
        if (!isloggedin() || isguestuser() || !is_siteadmin()) {
            return false;
        }

        if (!self::is_affected_version(PHP_VERSION_ID)) {
            return false;
        }

        return get_user_preferences(self::PREFERENCE_DISMISSED_VERSION, "") !== PHP_VERSION;
    }

    /**
     * Compare a PHP_VERSION_ID to the affected upstream release ranges.
     * Older unsupported PHP releases also lack an upstream fix.
     * PHP 8.6+ is not covered by the published 8.2-8.5 advisory ranges.
     * Vendor security backports cannot be detected from PHP_VERSION_ID.
     *
     * @param int $versionid PHP_VERSION_ID, or a version ID for unit tests.
     * @return bool
     */
    public static function is_affected_version(int $versionid): bool {
        if ($versionid < 80200) {
            return true;
        }

        $branch = intdiv($versionid, 100);
        return isset(self::FIXED_VERSIONS[$branch]) && $versionid < self::FIXED_VERSIONS[$branch];
    }

    /**
     * The fixed upstream release for the current supported branch.
     *
     * @param int $versionid PHP_VERSION_ID.
     * @return string|null Null for unsupported or unlisted branches.
     */
    public static function fixed_version(int $versionid): ?string {
        $branch = intdiv($versionid, 100);
        if (!isset(self::FIXED_VERSIONS[$branch])) {
            return null;
        }

        $fixed = self::FIXED_VERSIONS[$branch];
        return intdiv($fixed, 10000) . "." . (intdiv($fixed, 100) % 100) . "." . ($fixed % 100);
    }
}
