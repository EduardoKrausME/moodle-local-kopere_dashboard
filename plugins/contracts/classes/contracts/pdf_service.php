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
 * pdf_service.php
 *
 * @package   koperedashboard_contracts
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_contracts\contracts;

use context_course;
use context_system;
use core_user;
use local_kopere_dashboard\service\placeholders;
use local_kopere_dashboard\util\userdate;
use pdf;
use stdClass;
use stored_file;

/**
 * Class pdf_service
 */
class pdf_service {
    /**
     * File area used to persist generated contract PDFs.
     */
    public const FILEAREA = "signedpdf";

    /**
     * Returns a stored PDF for the acceptance record, generating it when needed.
     *
     * @param int $acceptid
     * @return stored_file
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \stored_file_creation_exception
     */
    public static function ensure_signed_pdf(int $acceptid): stored_file {
        global $DB;

        $DB->get_record("koperedashboard_contract_accept", ["id" => $acceptid], "*", MUST_EXIST);
        $fs = get_file_storage();
        $context = context_system::instance();

        $files = $fs->get_area_files(
            $context->id,
            "koperedashboard_contracts",
            self::FILEAREA,
            $acceptid,
            "timemodified DESC, id DESC",
            false
        );

        if (!empty($files)) {
            return reset($files);
        }

        return self::store_signed_pdf($acceptid);
    }

    /**
     * Generates and stores the signed PDF for one acceptance record.
     *
     * @param int $acceptid
     * @return stored_file
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \stored_file_creation_exception
     */
    public static function store_signed_pdf(int $acceptid): stored_file {
        global $DB;

        $accept = $DB->get_record("koperedashboard_contract_accept", ["id" => $acceptid], "*", MUST_EXIST);
        $contract = $DB->get_record("koperedashboard_contracts", ["id" => $accept->contractid], "*", MUST_EXIST);
        $course = get_course($accept->courseid);
        $user = core_user::get_user($accept->userid, "*", MUST_EXIST);

        $content = self::build_pdf_binary($accept, $contract, $course, $user);

        $fs = get_file_storage();
        $context = context_system::instance();
        $filename = self::build_filename($contract->name, $acceptid);

        $existing = $fs->get_area_files(
            $context->id,
            "koperedashboard_contracts",
            self::FILEAREA,
            $acceptid,
            "id ASC",
            false
        );
        foreach ($existing as $file) {
            $file->delete();
        }

        return $fs->create_file_from_string([
            "contextid" => $context->id,
            "component" => "koperedashboard_contracts",
            "filearea" => self::FILEAREA,
            "itemid" => $acceptid,
            "filepath" => "/",
            "filename" => $filename,
        ], $content);
    }

    /**
     * Outputs the stored PDF to the browser.
     *
     * @param int $acceptid
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \stored_file_creation_exception
     */
    public static function send_signed_pdf(int $acceptid): void {
        $file = self::ensure_signed_pdf($acceptid);
        send_stored_file($file, 0);
    }

    /**
     * Builds the PDF bytes.
     *
     * @param \stdClass $accept
     * @param \stdClass $contract
     * @param \stdClass $course
     * @param \stdClass $user
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private static function build_pdf_binary(stdClass $accept, stdClass $contract, stdClass $course, stdClass $user): string {
        global $CFG;

        require_once("{$CFG->libdir}/pdflib.php");

        $coursecontext = context_course::instance($course->id);
        $html = self::build_contract_html($contract, $course, $user, $coursecontext);
        $hash = hash("sha256", implode("|", [
            $accept->id,
            $accept->contractid,
            $accept->courseid,
            $accept->userid,
            $accept->timeaccepted,
            (string) $accept->ip,
            (string) $accept->useragent,
            sha1($html),
        ]));

        $pdf = new pdf();
        $pdf->SetCreator("Moodle Kopere Dashboard");
        $pdf->SetAuthor(fullname($user));
        $pdf->SetTitle(format_string($contract->name));
        $pdf->SetSubject(format_string($course->fullname));
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 50);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setAllowLocalFiles(true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true);

        $hassignature = self::apply_digital_signature($pdf);
        self::draw_signature_box($pdf, $accept, $contract, $course, $user, $hash, $hassignature);

        return $pdf->Output("", "S");
    }

    /**
     * Renders the contract HTML for one specific user.
     *
     * @param \stdClass $contract
     * @param \stdClass $course
     * @param \stdClass $user
     * @param \context_course $coursecontext
     * @return string
     * @throws \coding_exception
     * @throws \Exception
     */
    private static function build_contract_html(
        stdClass $contract, stdClass $course, stdClass $user, context_course $coursecontext
    ): string {
        global $USER;

        $originaluser = $USER;
        $USER = $user;

        try {
            $placeholderdata = placeholders::build_data($course->id);
        } finally {
            $USER = $originaluser;
        }

        if (!class_exists('Mustache_Engine')) {
            class_alias('Mustache\Engine', 'Mustache_Engine');
        }
        $engine = new \Mustache_Engine([
            "escape" => static function($value) {
                return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
            },
        ]);

        $renderedcontent = $engine->render($contract->content, $placeholderdata);
        $content = format_text($renderedcontent, $contract->contentformat ?? FORMAT_HTML, [
            "context" => $coursecontext,
            "overflowdiv" => true,
        ]);

        $title = format_string($contract->name);
        $coursename = format_string($course->fullname);

        return <<<HTML
<style>
    body { font-family: helvetica, arial, sans-serif; color: #202124; font-size: 10.5pt; }
    h1, h2, h3, h4, h5 { color: #123b78; }
    table { border-collapse: collapse; width: 100%; }
    td, th { padding: 4px; }
    img { max-width: 100%; height: auto; }
    .contract-header { margin-bottom: 14px; }
    .contract-title { font-size: 18pt; font-weight: bold; color: #123b78; }
    .contract-subtitle { font-size: 9.5pt; color: #555555; }
    .contract-content { line-height: 1.45; }
</style>
<div class="contract-header">
    <div class="contract-title">{$title}</div>
    <div class="contract-subtitle">{$coursename}</div>
</div>
<div class="contract-content">{$content}</div>
HTML;
    }

    /**
     * Applies the same certificate-based digital signature used in other PDFs.
     *
     * @param pdf $pdf
     * @return bool
     * @throws \dml_exception
     */
    private static function apply_digital_signature(pdf $pdf): bool {
        global $CFG, $OUTPUT;

        $config = get_config("koperedashboard_attest");
        $certificatepath = "";
        $certificatepassword = "";

        if (!empty($config->attestcertificatepath)) {
            $certificatepath = (string) $config->attestcertificatepath;
        }

        if (!empty($config->attestcertificatepassword)) {
            $certificatepassword = (string) $config->attestcertificatepassword;
        }

        if (empty($certificatepath) || !is_readable($certificatepath)) {
            return false;
        }

        $signatureinfo = [
            "Name" => "Kopere Dashboard",
            "Location" => $CFG->wwwroot,
            "Reason" => "Signed contract",
            "ContactInfo" => $OUTPUT->supportemail(),
        ];

        $pdf->setSignature(
            "file://" . $certificatepath,
            "file://" . $certificatepath,
            $certificatepassword,
            "",
            2,
            $signatureinfo
        );

        return true;
    }

    /**
     * Draws the visible signature box without any public verification URL.
     *
     * @param pdf $pdf
     * @param \stdClass $accept
     * @param \stdClass $contract
     * @param \stdClass $course
     * @param \stdClass $user
     * @param string $hash
     * @param bool $hassignature
     * @return void
     * @throws \coding_exception
     */
    private static function draw_signature_box(
        pdf $pdf,
        stdClass $accept,
        stdClass $contract,
        stdClass $course,
        stdClass $user,
        string $hash,
        bool $hassignature
    ): void {
        $x = 112;
        $w = 89;
        $h = 44;
        $bottom = 6;
        $y = $pdf->getPageHeight() - $bottom - $h;

        $radius = 3;
        $headerh = 9;
        $padding = 2.5;

        $blue = [0, 66, 125];
        $black = [0, 0, 0];
        $border = [185, 185, 185];

        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetDrawColor($blue[0], $blue[1], $blue[2]);
        $pdf->RoundedRect($x, $y, $w, $h, $radius, "1111", "DF");

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->RoundedRect($x, $y + $headerh, $w, $h - $headerh, $radius, "0011", "DF");

        $pdf->SetLineWidth(0.25);
        $pdf->SetDrawColor($border[0], $border[1], $border[2]);
        $pdf->RoundedRect($x, $y, $w, $h, $radius, "1111", "D");

        $iconx = $x + $padding;
        $icony = $y + $padding + 0.6;
        $iconw = 22;
        $iconh = 22;

        $pdf->SetFillColor(245, 248, 252);
        $pdf->SetDrawColor(225, 232, 240);
        $pdf->RoundedRect($iconx, $icony, $iconw, $iconh, 2.3, "1111", "DF");

        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetFont("helvetica", "B", 22);
        $pdf->SetXY($iconx, $icony + 4.2);
        $pdf->Cell($iconw, 8, "✓", 0, 0, "C");

        $textx = $iconx + $iconw + 2.4;
        $textw = ($x + $w - $padding) - $textx;

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont("helvetica", "B", 12.5);
        $pdf->SetXY($textx, $y + 1.8);
        $pdf->Cell($textw, $headerh - 3.5, "Assinatura digital", 0, 0, "C");

        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->SetFont("helvetica", "", 8.1);
        $pdf->SetXY($textx, $y + $headerh + 1.8);
        $pdf->MultiCell($textw, 3.5, "Contrato aceito eletronicamente no Kopere Dashboard.", 0, "L");

        $pdf->SetFont("helvetica", "", 7.1);
        self::draw_signature_line($pdf, $textx, $pdf->GetY() + 0.5, $textw, "Aluno:", fullname($user));
        self::draw_signature_line($pdf, $textx, $pdf->GetY(), $textw, "Curso:", format_string($course->shortname));
        self::draw_signature_line($pdf, $textx, $pdf->GetY(), $textw, "Contrato:", format_string($contract->name), 6.6);
        self::draw_signature_line($pdf, $textx, $pdf->GetY(), $textw, "Assinado em:", userdate::format($accept->timeaccepted), 6.8);
        self::draw_signature_line($pdf, $textx, $pdf->GetY(), $textw, "IP:", (string) ($accept->ip ?? "-"), 6.8);

        $pdf->SetXY($x + $padding, $y + $h - 7.7);
        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->SetFont("helvetica", "B", 6.5);
        $pdf->Cell(10.5, 4.1, "Hash:", 0, 0, "L");
        $pdf->SetFont("helvetica", "", 6.0);
        $pdf->Cell($w - 16, 4.1, strtoupper(substr($hash, 0, 48)), 0, 1, "L");

        if ($hassignature) {
            $pdf->setSignatureAppearance($iconx, $icony, $iconw, $iconh);
        }
    }

    /**
     * Draws one label/value line in the signature box.
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
    private static function draw_signature_line(
        pdf $pdf, float $x, float $y, float $w, string $title, string $text, float $size = 7.1
    ): void {
        $pdf->SetXY($x, $y);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("helvetica", "B", $size);
        $titlew = $pdf->GetStringWidth($title . " ") + 0.7;
        $pdf->Cell($titlew, 3.8, $title, 0, 0, "L");

        $pdf->SetFont("helvetica", "", $size);
        $pdf->Cell($w - $titlew, 3.8, shorten_text($text, 42, true), 0, 1, "L");
    }

    /**
     * Builds the file name.
     *
     * @param string $contractname
     * @param int $acceptid
     * @return string
     */
    private static function build_filename(string $contractname, int $acceptid): string {
        $base = clean_filename($contractname);
        if (empty($base)) {
            $base = "contrato";
        }

        return $base . "-" . $acceptid . ".pdf";
    }
}
