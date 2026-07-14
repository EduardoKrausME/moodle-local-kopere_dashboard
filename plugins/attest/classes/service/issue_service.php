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
 * issue_service.php
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest\service;

use koperedashboard_attest\audit\logger;
use coding_exception;
use context_course;
use core\exception\moodle_exception;
use dml_exception;
use Exception;
use local_kopere_dashboard\service\placeholders;
use local_kopere_dashboard\util\userdate;
use moodle_url;
use pdf;
use required_capability_exception;
use stdClass;

/**
 * Class issue_service
 */
class issue_service {
    /**
     * Number of days considered close to expiration.
     */
    public const RECREATE_WINDOW_DAYS = 45;

    /**
     * Function user_can_access_course
     *
     * @param int $userid
     * @param int $courseid
     * @return void
     * @throws required_capability_exception
     */
    public static function user_can_access_course(int $userid, int $courseid): void {
        $context = context_course::instance($courseid);

        if (!is_enrolled($context, $userid, "", true)) {
            throw new required_capability_exception($context, "moodle/course:view", "nopermissions", "");
        }
    }

    /**
     * Function template_is_allowed_for_course
     *
     * @param stdClass $tpl
     * @param int $courseid
     * @return bool
     * @throws dml_exception
     */
    public static function template_is_allowed_for_course(stdClass $tpl, int $courseid): bool {
        global $DB;

        if (!empty($tpl->allcourses)) {
            return true;
        }

        $istplcourse = $DB->record_exists("koperedashboard_attest_tpl_course", [
            "tplid" => $tpl->id,
            "courseid" => $courseid,
        ]);
        $istpl = $DB->record_exists("koperedashboard_attest_tpl", [
            "id" => $tpl->id,
            "allcourses" => 1,
        ]);

        return $istplcourse || $istpl;
    }

    /**
     * Returns the latest issue for one template, course and user.
     *
     * @param int $tplid
     * @param int $courseid
     * @param int $userid
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_latest_issue(int $tplid, int $courseid, int $userid): ?stdClass {
        global $DB;

        $sql = "SELECT ai.*
                  FROM {koperedashboard_attest_issue} ai
                 WHERE ai.tplid = :tplid
                   AND ai.courseid = :courseid
                   AND ai.userid = :userid
              ORDER BY ai.timecreated DESC, ai.id DESC";

        return $DB->get_record_sql($sql, [
            "tplid" => $tplid,
            "courseid" => $courseid,
            "userid" => $userid,
        ], IGNORE_MULTIPLE) ?: null;
    }

    /**
     * Returns the latest valid issue for one template, course and user.
     *
     * @param int $tplid
     * @param int $courseid
     * @param int $userid
     * @param int|null $now
     * @return stdClass|null
     * @throws dml_exception
     */
    public static function get_latest_valid_issue(int $tplid, int $courseid, int $userid, ?int $now = null): ?stdClass {
        global $DB;

        $now = $now ?? time();

        $sql = "SELECT ai.*
                  FROM {koperedashboard_attest_issue} ai
                 WHERE ai.tplid = :tplid
                   AND ai.courseid = :courseid
                   AND ai.userid = :userid
                   AND ai.validuntil >= :nowtime
              ORDER BY ai.timecreated DESC, ai.id DESC";

        return $DB->get_record_sql($sql, [
            "tplid" => $tplid,
            "courseid" => $courseid,
            "userid" => $userid,
            "nowtime" => $now,
        ], IGNORE_MULTIPLE) ?: null;
    }

    /**
     * Returns all generated issues for one user.
     *
     * @param int $userid
     * @return array
     * @throws dml_exception
     */
    public static function get_user_issues(int $userid): array {
        global $DB;

        $sql = "SELECT ai.*,
                       tpl.name AS tplname,
                       c.fullname AS coursefullname
                  FROM {koperedashboard_attest_issue} ai
             LEFT JOIN {koperedashboard_attest_tpl} tpl ON tpl.id = ai.tplid
             LEFT JOIN {course} c ON c.id = ai.courseid
                 WHERE ai.userid = :userid
              ORDER BY ai.timecreated DESC, ai.id DESC";

        return $DB->get_records_sql($sql, ["userid" => $userid]);
    }

    /**
     * Validates if the issue is still valid.
     *
     * @param stdClass $issue
     * @param int|null $now
     * @return bool
     */
    public static function is_issue_valid(stdClass $issue, ?int $now = null): bool {
        $now = $now ?? time();

        return !empty($issue->validuntil) && $issue->validuntil >= $now;
    }

    /**
     * Validates if the issue is within the recreation window.
     *
     * @param stdClass $issue
     * @param int|null $now
     * @param int $days
     * @return bool
     */
    public static function is_issue_expiring_soon(stdClass $issue, ?int $now = null, int $days = self::RECREATE_WINDOW_DAYS): bool {
        $now = $now ?? time();

        if (!self::is_issue_valid($issue, $now)) {
            return false;
        }

        return ($issue->validuntil - $now) <= ($days * DAYSECS);
    }

    /**
     * Validates if a valid issue can be recreated.
     *
     * @param stdClass $issue
     * @param int|null $now
     * @param int $days
     * @return bool
     */
    public static function can_recreate_issue(stdClass $issue, ?int $now = null, int $days = self::RECREATE_WINDOW_DAYS): bool {
        return self::is_issue_expiring_soon($issue, $now, $days);
    }

    /**
     * Builds the final rendered HTML for one attestation.
     *
     * @param stdClass $tpl
     * @param int $courseid
     * @return string
     * @throws Exception
     */
    public static function build_rendered_html(stdClass $tpl, int $courseid): string {
        $data = placeholders::build_data($courseid);

        return self::render_html($tpl->html, $data);
    }

    /**
     * Function create_issue
     *
     * @param int $tplid
     * @param int $courseid
     * @param int $userid
     * @param string $renderedhtml
     * @param int $validmonths
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function create_issue(int $tplid, int $courseid, int $userid, string $renderedhtml, int $validmonths): stdClass {
        global $DB;

        $now = time();
        $validuntil = strtotime("+" . max(1, $validmonths) . " months", $now);
        $token = sha1($tplid . "|" . $courseid . "|" . $userid . "|" . $now);
        $issue = (object) [
            "tplid" => $tplid,
            "courseid" => $courseid,
            "userid" => $userid,
            "token" => random_string(12),
            "timecreated" => $now,
            "validuntil" => $validuntil ?: ($now + 365 * DAYSECS),
            "html" => $renderedhtml,
            "htmlsha1" => $token,
            "ip" => getremoteaddr(),
            "useragent" => !empty($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null,
        ];

        $issue->id = $DB->insert_record("koperedashboard_attest_issue", $issue);

        logger::issue_created($issue->id, $tplid, $courseid);

        return $issue;
    }

    /**
     * Function render_html
     *
     * @param string $htmltemplate
     * @param array $data
     * @return string
     */
    public static function render_html(string $htmltemplate, array $data): string {
        if (!class_exists('Mustache_Engine')) {
            class_alias('Mustache\Engine', 'Mustache_Engine');
        }
        $engine = new \Mustache_Engine([
            "escape" => function($value) {
                return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
            },
        ]);

        return $engine->render($htmltemplate, $data);
    }

    /**
     * Function output_pdf
     *
     * @param stdClass $issue
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function output_pdf($issue): void {
        global $CFG, $DB;

        require_once("{$CFG->libdir}/pdflib.php");
        require_once("{$CFG->libdir}/tcpdf/tcpdf_barcodes_2d.php");

        $tpl = $DB->get_record("koperedashboard_attest_tpl", ["id" => $issue->tplid], "*", MUST_EXIST);

        $pdf = new pdf();
        $pdf->SetCreator("Moodle Kopere Dashboard");
        $pdf->SetAuthor("Kopere Dashboard");
        $pdf->SetTitle($tpl->name);
        $pdf->SetMargins(12, 12, 12);
        $pdf->AddPage();
        $pdf->setAllowLocalFiles(true);

        $pdf->writeHTML($issue->html);

        self::draw_signature_box($pdf, $issue);

        $pdf->Output("{$tpl->name}.pdf");
        exit;
    }

    /**
     * Draw the digital signature box.
     *
     * @param pdf $pdf
     * @param $issue
     * @return void
     * @throws moodle_exception
     * @throws coding_exception
     */
    private static function draw_signature_box(pdf $pdf, $issue) {
        global $CFG;

        $x = 115;
        $w = 86;
        $h = 42;
        $bottom = 18;
        $y = $pdf->getPageHeight() - $bottom - $h - 3;

        $radius = 3;
        $headerh = 9;
        $padding = 2.5;

        $blue = [0, 66, 125];
        $black = [0, 0, 0];
        $border = [185, 185, 185];

        // Full blue rounded box.
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetDrawColor($blue[0], $blue[1], $blue[2]);
        $pdf->RoundedRect($x, $y, $w, $h, $radius, "1111", "DF");

        // White body area.
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->RoundedRect($x, $y + $headerh, $w, $h - $headerh, $radius, "0011", "DF");

        // Outer border.
        $pdf->SetLineWidth(0.25);
        $pdf->SetDrawColor($border[0], $border[1], $border[2]);
        $pdf->RoundedRect($x, $y, $w, $h, $radius, "1111", "D");

        // QR white panel.
        $qrpanelx = $x + $padding;
        $qrpanely = $y + $padding;
        $qrpanelw = 26;
        $qrpanelh = 26;

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->RoundedRect($qrpanelx, $qrpanely, $qrpanelw, $qrpanelh, 2.3, "1111", "DF");

        // QR code.
        $verifyurl = new moodle_url("/local/kopere_dashboard/plugins/attest/v/", ["token" => $issue->token]);
        $pdf->write2DBarcode(
            "{$verifyurl}",
            "QRCODE,L",
            $qrpanelx + 1.7,
            $qrpanely + 1.7,
            $qrpanelw - 3.4,
            $qrpanelh - 3.4,
            ["border" => false, "padding" => 0, "fgcolor" => $black, "bgcolor" => false],
            "N"
        );

        // Title.
        $textx = $qrpanelx + $qrpanelw;
        $textw = ($x + $w - $padding) - $textx;

        $label = get_string("footer_title", "koperedashboard_attest");
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont("helvetica", "B", 14);
        $pdf->SetXY($textx, $y + 1.6);
        $pdf->Cell($textw, $headerh - 4, $label, 0, 0, "C");

        // Description.
        $label = get_string("footer_desc", "koperedashboard_attest");
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("helvetica", "", 8.6);
        $pdf->SetXY($textx, $y + $headerh + 2.0);
        $pdf->MultiCell($textw, 3, $label, 0, "L");

        // Info lines.
        $pdf->SetFont("helvetica", "", 7.9);
        $title = get_string("footer_created", "koperedashboard_attest");
        $createdtxt = userdate::format($issue->timecreated);
        self::draw_signature_line($pdf, $textx, $pdf->GetY(), $textw, "{$title}:", $createdtxt);

        $title = get_string("footer_validuntil", "koperedashboard_attest");
        $validtxt = userdate::format($issue->validuntil);
        self::draw_signature_line($pdf, $textx, $pdf->GetY(), $textw, "{$title}:", $validtxt);

        // Clickable area for the URL.
        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetFont("helvetica", "", 7.2);
        $urly = $pdf->GetY() + 0.3;
        $pdf->SetXY($textx, $urly);
        $pdf->MultiCell($textw, 0, $CFG->wwwroot, 0, "L");

        // Area clicável sobre o texto.
        $pdf->Link($textx, $urly, $pdf->GetStringWidth($CFG->wwwroot), 4.5, "{$verifyurl}");

        // Clickable area for the URL.
        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $hashtitle = get_string("footer_hash", "koperedashboard_attest");
        self::draw_signature_line($pdf, $textx - 26, $pdf->GetY() + 2, $textw, "{$hashtitle}:", $issue->htmlsha1, 6.5);

        self::draw_signature_line($pdf, $textx - 26, $pdf->GetY(), $textw, "IP:", $issue->ip, 6.5);
    }

    /**
     * Draw a label/value line inside the signature box.
     *
     * @param pdf $pdf
     * @param float $x
     * @param float $y
     * @param float $w
     * @param string $title
     * @param string $text
     * @param float $size
     * @return void
     */
    private static function draw_signature_line(pdf $pdf, float $x, float $y, float $w, string $title, string $text, $size = 7.9
    ): void {
        $pdf->SetXY($x, $y);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("helvetica", "B", $size);
        $titlew = $pdf->GetStringWidth($title . " ") + 0.8;
        $pdf->Cell($titlew, 4.1, $title, 0, 0, "L");

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("helvetica", "", $size);
        $pdf->Cell($w - $titlew, 4.1, $text, 0, 1, "L");
    }
}
