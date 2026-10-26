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
        $pdf->SetAutoPageBreak(true, 12);
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
            "overflowdiv" => false,
        ]);

        // The contract editor owns the document header: do not prepend the course/name again.
        return <<<HTML
<style>
    body { font-family: helvetica, arial, sans-serif; color: #202124; font-size: 10.5pt; }
    h1, h2, h3, h4, h5 { color: #123b78; }
    table { border-collapse: collapse; width: 100%; }
    td, th { padding: 4px; }
    img { max-width: 100%; height: auto; }
    .contract-content { line-height: 1.45; }
</style>
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
     * Measures every variable text block without changing the generated PDF.
     *
     * getStringHeight() is an estimate. Running the same MultiCell() call in a
     * reversible transaction also captures the actual vertical advance, so
     * the final layout does not rely solely on a guessed text height.
     *
     * @param pdf $pdf
     * @param float $left
     * @param float $width
     * @param array $fields
     * @param string $title
     * @param string $description
     * @param string $hash
     * @return array
     */
    private static function measure_signature_content(
        pdf $pdf, float $left, float $width, array $fields, string $title, string $description, string $hash
    ): array {
        $fieldwidth = $width - 42.0;
        $contentwidth = $width - 12.0;
        $items = [
            "title" => ["text" => $title, "width" => $width - 24.0, "font" => "helvetica", "style" => "B", "size" => 10.0],
            "description" => ["text" => $description, "width" => $contentwidth, "font" => "helvetica", "style" => "", "size" => 8.0],
            "hashlabel" => ["text" => "SHA-256:", "width" => $contentwidth, "font" => "helvetica", "style" => "B", "size" => 8.0],
            "hash" => ["text" => strtoupper($hash), "width" => $contentwidth, "font" => "courier", "style" => "", "size" => 7.0],
        ];
        foreach ($fields as $index => $field) {
            $items["field{$index}"] = [
                "text" => $field[1], "width" => $fieldwidth, "font" => "helvetica", "style" => "", "size" => 8.0,
            ];
        }

        $heights = [];
        $pdf->startTransaction();
        try {
            $pdf->SetAutoPageBreak(false, 0);
            foreach ($items as $key => $item) {
                $pdf->SetFont($item["font"], $item["style"], $item["size"]);
                $estimate = $pdf->getStringHeight($item["width"], $item["text"], true);
                $testy = 12.0;
                $pdf->MultiCell(
                    $item["width"], 0, $item["text"], 0, "L", false, 1,
                    $left, $testy, true, 0, false, true, 0, "T", false
                );
                // Do not pass a max height: long values must be allowed to wrap.
                $actual = max(0.0, $pdf->GetY() - $testy);
                $heights[$key] = max(5.0, $estimate, $actual) + 0.8;
            }
        } finally {
            $pdf->rollbackTransaction(true);
        }

        return $heights;
    }

    /**
     * Draws the entire electronic acceptance proof on one page.
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
        $margins = $pdf->getMargins();
        $x = (float) $margins["left"];
        $w = $pdf->getPageWidth() - $x - (float) $margins["right"];
        $bottommargin = (float) $margins["bottom"];
        $topmargin = (float) $margins["top"];
        $gap = 5.0;
        $contentx = $x + 6.0;
        $contentwidth = $w - 12.0;
        $labelwidth = 30.0;

        $fields = [
            ["Aluno:", fullname($user)],
            ["Curso:", format_string($course->shortname)],
            ["Contrato:", format_string($contract->name)],
            ["Assinado em:", userdate::format($accept->timeaccepted)],
            ["IP:", (string) ($accept->ip ?? "-")],
        ];
        $title = get_string("accept_title", "koperedashboard_contracts");
        $description = "Contrato aceite eletronicamente no Kopere Dashboard.";
        $heights = self::measure_signature_content($pdf, $contentx, $w, $fields, $title, $description, $hash);

        // Use the measured heights both for the box and for every final cell.
        $headerheight = max(9.0, $heights["title"] + 3.0);
        $cursor = $headerheight + 2.0;
        $descriptiony = $cursor;
        $cursor += $heights["description"] + 2.0;
        $rowpositions = [];
        foreach ($fields as $index => $field) {
            $rowpositions[] = $cursor;
            $cursor += $heights["field{$index}"] + 0.6;
        }
        $dividery = $cursor + 1.0;
        $hashlabely = $dividery + 2.0;
        $hashy = $hashlabely + $heights["hashlabel"];
        $h = $hashy + $heights["hash"] + 3.0;

        // Never place part of the receipt outside the printable area.
        if ($h + $gap > $pdf->getPageHeight() - $topmargin - $bottommargin) {
            throw new \coding_exception("The contract acceptance receipt exceeds the printable page height.");
        }
        $y = $pdf->GetY() + $gap;
        if ($y + $h > $pdf->getPageHeight() - $bottommargin) {
            $pdf->AddPage();
            $y = $pdf->GetY() + $gap;
        }

        // The entire block fits, so disabling automatic breaks prevents a stray page.
        $pdf->SetAutoPageBreak(false, 0);
        try {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->RoundedRect($x, $y, $w, $h, 3, "1111", "F");
            $pdf->SetFillColor(0, 66, 125);
            $pdf->Rect($x, $y, $w, $headerheight, "F");
            $pdf->SetDrawColor(185, 185, 185);
            $pdf->SetLineWidth(0.25);
            $pdf->RoundedRect($x, $y, $w, $h, 3, "1111", "D");

            // The checkmark is vector artwork, independent of installed fonts.
            $iconx = $x + 6.0;
            $icony = $y + ($headerheight - 7.0) / 2.0;
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetLineWidth(1.1);
            $pdf->Line($iconx, $icony + 3.5, $iconx + 2.4, $icony + 5.7);
            $pdf->Line($iconx + 2.4, $icony + 5.7, $iconx + 7.0, $icony + 0.7);

            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont("helvetica", "B", 10.0);
            $pdf->MultiCell($w - 24.0, $heights["title"], $title, 0, "L", false, 1,
                $x + 18.0, $y + 1.5, true, 0, false, true, 0, "T", false);

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont("helvetica", "", 8.0);
            $pdf->MultiCell($contentwidth, $heights["description"], $description, 0, "L", false, 1,
                $contentx, $y + $descriptiony, true, 0, false, true, 0, "T", false);

            foreach ($fields as $index => $field) {
                $rowy = $y + $rowpositions[$index];
                $pdf->SetFont("helvetica", "B", 8.0);
                $pdf->SetXY($contentx, $rowy);
                $pdf->Cell($labelwidth, 5.0, $field[0], 0, 0, "L");
                $pdf->SetFont("helvetica", "", 8.0);
                $pdf->MultiCell($contentwidth - $labelwidth, $heights["field{$index}"], $field[1], 0,
                    "L", false, 1, $contentx + $labelwidth, $rowy, true, 0, false, true, 0, "T", false);
            }

            $pdf->SetDrawColor(185, 185, 185);
            $pdf->SetLineWidth(0.25);
            $pdf->Line($x + 5.0, $y + $dividery, $x + $w - 5.0, $y + $dividery);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont("helvetica", "B", 8.0);
            $pdf->MultiCell($contentwidth, $heights["hashlabel"], "SHA-256:", 0, "L", false, 1,
                $contentx, $y + $hashlabely, true, 0, false, true, 0, "T", false);
            $pdf->SetFont("courier", "", 7.0);
            $pdf->MultiCell($contentwidth, $heights["hash"], strtoupper($hash), 0, "L", false, 1,
                $contentx, $y + $hashy, true, 0, false, true, 0, "T", false);

            if ($hassignature) {
                $pdf->setSignatureAppearance($x + 4.0, $y + 1.0, 10.0, 7.0);
            }
        } finally {
            $pdf->SetAutoPageBreak(true, $bottommargin);
        }
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
