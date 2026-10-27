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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Optional Kopere BI summaries in the Dashboard's user and course detail pages.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use context_system;
use core_component;
use local_kopere_bi\block\util\string_util;
use local_kopere_bi\feature;
use local_kopere_bi\page_access;
use moodle_url;
use xmldb_table;

/**
 * Read-only integration. Never assumes that local_kopere_bi is installed.
 */
class bi_entity_reports {
    /** Curated report reference keys from the BI's official catalog. */
    private const USER_REPORTS = [
        "kbi_page_user_site_summary_detail",
        "kbi_page_user_status_summary",
        "kbi_page_login_statistics",
    ];

    /** All these BI pages accept :courseid in their SQL. */
    private const COURSE_REPORTS = [
        "kbi_page_course_access",
        "kbi_page_course_access_details",
        "kbi_page_course_site_summary",
        "kbi_page_course_stats",
    ];

    /**
     * Whether the installed BI is usable by the current viewer.
     *
     * @return bool
     */
    private static function available(): bool {
        global $DB;

        if (!core_component::get_plugin_directory("local", "kopere_bi") ||
                !get_config("local_kopere_bi", "version") ||
                !has_capability("local/kopere_bi:view", context_system::instance()) ||
                !class_exists(page_access::class) || !class_exists(feature::class)) {
            return false;
        }

        $manager = $DB->get_manager();
        return $manager->table_exists(new xmldb_table("local_kopere_bi_tracking")) &&
            $manager->table_exists(new xmldb_table("local_kopere_bi_page"));
    }

    /**
     * Summary for the user being viewed.
     *
     * @param int $userid
     * @param int[] $visiblecourseids Course IDs already verified by the caller.
     * @return array|null
     */
    public static function for_user(int $userid, array $visiblecourseids): ?array {
        global $DB, $USER;

        $context = context_system::instance();
        if (!self::available() ||
                (!has_capability("koperedashboard/users:manage", $context) && $USER->id != $userid)) {
            return null;
        }

        $stats = $DB->get_record_sql(
            "SELECT COALESCE(SUM(visits), 0) AS visits,
                    COALESCE(SUM(timespend), 0) AS seconds,
                    COUNT(DISTINCT CASE WHEN courseid > 1 THEN courseid ELSE NULL END) AS courses,
                    MAX(lastaccess) AS lastaccess
               FROM {local_kopere_bi_tracking}
              WHERE userid = :userid",
            ["userid" => $userid]
        );

        // Only expose courses that the user-detail page is already allowed to list.
        $rows = [];
        $visiblecourseids = array_values(array_unique(array_map("intval", $visiblecourseids)));
        if ($visiblecourseids) {
            [$insql, $inparams] = $DB->get_in_or_equal($visiblecourseids, SQL_PARAMS_NAMED, "bc");
            $params = ["userid" => $userid] + $inparams;
            $records = $DB->get_records_sql(
                "SELECT c.id, c.fullname,
                        SUM(t.visits) AS visits, SUM(t.timespend) AS seconds,
                        MAX(t.lastaccess) AS lastaccess
                   FROM {local_kopere_bi_tracking} t
                   JOIN {course} c ON c.id = t.courseid
                  WHERE t.userid = :userid AND c.id $insql
               GROUP BY c.id, c.fullname
               ORDER BY lastaccess DESC, c.id ASC",
                $params, 0, 5
            );
            foreach ($records as $record) {
                $rows[] = [
                    "name" => format_string($record->fullname),
                    "url" => (new moodle_url("/local/kopere_dashboard/plugins/courses/view.php",
                        ["id" => $record->id]))->out(false),
                    "visits" => format_float($record->visits, 0),
                    "time" => format_time((int)$record->seconds),
                ];
            }
        }

        return self::summary($stats, (int)($stats->courses ?? 0), "bi_metric_courses",
            self::USER_REPORTS, [], $rows, "bi_user_details", true);
    }

    /**
     * Summary for the course being viewed.
     *
     * @param int $courseid
     * @param bool $canviewparticipants
     * @return array|null
     */
    public static function for_course(int $courseid, bool $canviewparticipants): ?array {
        global $DB;

        if (!self::available() ||
                !has_capability("koperedashboard/courses:manage", context_system::instance())) {
            return null;
        }

        $stats = $DB->get_record_sql(
            "SELECT COALESCE(SUM(visits), 0) AS visits,
                    COALESCE(SUM(timespend), 0) AS seconds,
                    COUNT(DISTINCT userid) AS participants,
                    MAX(lastaccess) AS lastaccess
               FROM {local_kopere_bi_tracking}
              WHERE courseid = :courseid",
            ["courseid" => $courseid]
        );

        $rows = [];
        if ($canviewparticipants) {
            $records = $DB->get_records_sql(
                "SELECT u.id, u.firstname, u.lastname, u.firstnamephonetic,
                        u.lastnamephonetic, u.middlename, u.alternatename,
                        SUM(t.visits) AS visits, SUM(t.timespend) AS seconds,
                        MAX(t.lastaccess) AS lastaccess
                   FROM {local_kopere_bi_tracking} t
                   JOIN {user} u ON u.id = t.userid
                  WHERE t.courseid = :courseid AND u.deleted = 0
               GROUP BY u.id, u.firstname, u.lastname, u.firstnamephonetic,
                        u.lastnamephonetic, u.middlename, u.alternatename
               ORDER BY lastaccess DESC, u.id ASC",
                ["courseid" => $courseid], 0, 5
            );
            foreach ($records as $record) {
                $rows[] = [
                    "name" => fullname($record),
                    "url" => (new moodle_url("/local/kopere_dashboard/plugins/users/view.php",
                        ["id" => $record->id]))->out(false),
                    "visits" => format_float($record->visits, 0),
                    "time" => format_time((int)$record->seconds),
                ];
            }
        }

        return self::summary($stats, (int)($stats->participants ?? 0), "bi_metric_participants",
            self::COURSE_REPORTS, ["courseid" => $courseid], $rows, "bi_course_details", false);
    }

    /**
     * Build the shared view data, including links only to accessible BI pages.
     *
     * @param object $stats
     * @param int $distinctcount
     * @param string $distinctlabel
     * @param string[] $reportkeys
     * @param array $urlparams
     * @param array $rows
     * @param string $detailkey
     * @param bool $globalreports True when the stock BI pages have no user filter.
     * @return array
     */
    private static function summary($stats, int $distinctcount, string $distinctlabel,
            array $reportkeys, array $urlparams, array $rows, string $detailkey,
            bool $globalreports): array {
        return [
            "metrics" => [
                ["label" => get_string("bi_metric_visits", "local_kopere_dashboard"),
                    "value" => format_float((int)($stats->visits ?? 0), 0)],
                ["label" => get_string("bi_metric_time", "local_kopere_dashboard"),
                    "value" => format_time((int)($stats->seconds ?? 0))],
                ["label" => get_string($distinctlabel, "local_kopere_dashboard"),
                    "value" => format_float($distinctcount, 0)],
                ["label" => get_string("bi_metric_lastaccess", "local_kopere_dashboard"),
                    "value" => !empty($stats->lastaccess) ? userdate((int)$stats->lastaccess) :
                        get_string("bi_never_accessed", "local_kopere_dashboard")],
            ],
            "reports" => self::reports($reportkeys, $urlparams),
            "details" => $rows,
            "detailtitle" => get_string($detailkey, "local_kopere_dashboard"),
            "isglobalreports" => $globalreports,
        ];
    }

    /**
     * Resolve stable BI refkeys to installation-specific page IDs.
     *
     * @param string[] $refkeys
     * @param array $urlparams
     * @return array
     */
    private static function reports(array $refkeys, array $urlparams): array {
        global $DB;

        [$insql, $params] = $DB->get_in_or_equal($refkeys, SQL_PARAMS_NAMED, "br");
        $pages = $DB->get_records_sql(
            "SELECT id, refkey, title, capability
               FROM {local_kopere_bi_page}
              WHERE refkey $insql
           ORDER BY id ASC",
            $params
        );

        $bykey = [];
        foreach ($pages as $page) {
            if (!isset($bykey[$page->refkey]) &&
                    feature::page_is_available($page) &&
                    page_access::can_view($page, context_system::instance())) {
                $bykey[$page->refkey] = [
                    "title" => string_util::get_string($page->title),
                    "url" => (new moodle_url("/local/kopere_bi/index.php",
                        ["classname" => "dashboard", "method" => "preview", "page_id" => $page->id]
                        + $urlparams))->out(false),
                ];
            }
        }

        $items = [];
        foreach ($refkeys as $key) {
            if (isset($bykey[$key])) {
                $items[] = $bykey[$key];
            }
        }
        return $items;
    }
}
