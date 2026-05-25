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
 * home_kpi.php
 *
 * @package   koperedashboard_reportcard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_reportcard;

use koperedashboard_reportcard\service\report_service;
use context;
use moodle_url;

/**
 * Class home_kpi
 */
class home_kpi {
    /** @var int Fixed dashboard priority. Lower numbers are displayed first. */
    public static $priority = 6;

    /**
     * Return the KPI data for the Kopere Dashboard home.
     *
     * @param context $context
     * @return array|null
     */
    public static function get_metric(context $context): ?array {
        global $USER;

        if (!has_capability("koperedashboard/reportcard:view", $context)) {
            return null;
        }

        $courses = report_service::get_available_courses((int) $USER->id);
        $value = count($courses);

        return [
            "title" => get_string("menu_title", "koperedashboard_reportcard"),
            "value" => self::format_number($value),
            "subtitle" => get_string("select_course", "koperedashboard_reportcard"),
            "url" => (new moodle_url("/local/kopere_dashboard/plugins/reportcard/my.php"))->out(false),
            "style" => "kopere_dashboard-kpi-violet",
        ];
    }

    /**
     * Format a KPI number.
     *
     * @param int $value
     * @return string
     */
    private static function format_number(int $value): string {
        return number_format($value, 0, ",", ".");
    }
}
