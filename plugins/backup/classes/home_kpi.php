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
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_backup;

use context;
use moodle_url;

/**
 * Class home_kpi
 */
class home_kpi {
    /** @var int Fixed dashboard priority. Lower numbers are displayed first. */
    public static $priority = 999;

    /**
     * Return the KPI data for the Kopere Dashboard home.
     *
     * @param context $context
     * @return array|null
     */
    public static function get_metric(context $context): ?array {
        if (!has_capability("koperedashboard/backup:manage", $context)) {
            return null;
        }

        $latest = null;
        foreach (backup_manager::list_backups() as $backup) {
            if ($latest === null || (int) $backup["timemodified"] > (int) $latest["timemodified"]) {
                $latest = $backup;
            }
        }

        if ($latest === null) {
            $value = get_string("home_kpi_empty_value", "koperedashboard_backup");
            $subtitle = get_string("home_kpi_empty_subtitle", "koperedashboard_backup");
        } else {
            $value = userdate((int) $latest["timemodified"], "%d/%m/%Y");
            $subtitle = get_string("home_kpi_subtitle", "koperedashboard_backup", display_size((int) $latest["filesize"]));
        }

        return [
            "title" => get_string("home_kpi_title", "koperedashboard_backup"),
            "value" => $value,
            "subtitle" => $subtitle,
            "url" => (new moodle_url("/local/kopere_dashboard/plugins/backup/"))->out(false),
            "style" => "kopere_dashboard-kpi-slate",
        ];
    }
}
