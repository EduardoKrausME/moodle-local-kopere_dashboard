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
 * studentcard_helper
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_attest\helper;

use coding_exception;
use context_system;
use context_user;
use core\output\notification;
use dml_exception;
use moodle_exception;
use moodle_url;
use stdClass;
use TCPDF;

/**
 * Helper for student card PDF generation and validation.
 */
class studentcard_helper {
    /** Credit card width in millimeters. */
    public const CARD_WIDTH = 85.60;

    /** Credit card height in millimeters. */
    public const CARD_HEIGHT = 53.98;

    /** Unicode PDF font used to support Cyrillic and other non-Latin characters. */
    private const PDF_FONT = "freesans";

    /** Unicode monospace PDF font used for validation codes. */
    private const PDF_MONO_FONT = "freemono";

    /**
     * Returns the target user if the current user can access the card.
     *
     * @param int $userid
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function get_target_user(int $userid): stdClass {
        global $DB;

        if (!self::can_view_user_card($userid)) {
            throw new moodle_exception("nopermissions", "error", "", get_string("studentcard", "koperedashboard_attest"));
        }

        if (!self::has_visible_enrolled_courses($userid)) {
            throw new moodle_exception("studentcardnovisiblecourses", "koperedashboard_attest");
        }

        return $DB->get_record(
            "user",
            ["id" => $userid, "deleted" => 0],
            "id, firstname, lastname, email, picture, imagealt, idnumber, deleted, suspended, " .
            "firstnamephonetic, lastnamephonetic, middlename, alternatename",
            MUST_EXIST
        );
    }

    /**
     * Checks whether the current user can view a user card.
     *
     * @param int $userid
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function can_view_user_card(int $userid): bool {
        global $USER;

        if ((int) $USER->id === $userid) {
            return true;
        }

        $context = context_system::instance();

        if (is_siteadmin()) {
            return true;
        }

        if (has_capability("moodle/site:config", $context)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the CPF from custom profile field or fallback to idnumber.
     *
     * @param stdClass $user
     * @return string
     */
    public static function get_user_cpf(stdClass $user): string {
        global $CFG;

        require_once("{$CFG->dirroot}/user/profile/lib.php");
        profile_load_data($user);

        if (!empty($user->profile_field_cpf)) {
            return trim($user->profile_field_cpf);
        }

        if (!empty($user->idnumber)) {
            return trim((string) $user->idnumber);
        }

        return "";
    }

    /**
     * Creates the validator code as base64url of {"userid":NNN}.
     *
     * @param int $userid
     * @return string
     */
    public static function build_validation_code(int $userid): string {
        $payload = json_encode(["userid" => $userid]);

        return bin2hex($payload);
    }

    /**
     * Decodes the validator code.
     *
     * @param string $code
     * @return array|null
     * @throws \moodle_exception
     */
    public static function decode_validation_code(string $code): ?array {
        $decoded = @hex2bin($code);

        if (!$decoded) {
            throw new moodle_exception("invalidaccessparameter", "error");
        }

        $data = json_decode($decoded, true);
        if (!is_array($data) || empty($data["userid"])) {
            return null;
        }

        return [
            "userid" => (int) $data["userid"],
        ];
    }

    /**
     * Builds the public validation URL.
     *
     * @param int $userid
     * @param string $code
     * @return moodle_url
     * @throws \core\exception\moodle_exception
     */
    public static function build_validation_url(int $userid, string $code): moodle_url {
        return new moodle_url(
            "/local/kopere_dashboard/plugins/attest/v/index.php",
            [
                "token" => $code,
            ]
        );
    }

    /**
     * Generates and outputs the student card PDF.
     *
     * @param stdClass $user
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     * @throws \core\exception\moodle_exception
     */
    public static function output_pdf(stdClass $user): void {
        global $CFG, $SITE;

        require_once($CFG->libdir . "/tcpdf/tcpdf.php");

        $pdf = new TCPDF("L", "mm", [self::CARD_WIDTH, self::CARD_HEIGHT], true, "UTF-8", false);
        $pdf->SetCreator("Kopere Dashboard");
        $pdf->SetAuthor(format_string($SITE->fullname));
        $pdf->SetTitle(get_string("studentcard", "koperedashboard_attest"));
        $pdf->SetSubject(get_string("studentcard", "koperedashboard_attest"));
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont(self::PDF_FONT, "", 10);

        self::render_front_page($pdf, $user);
        self::render_back_page($pdf, $user);

        $filename = clean_filename("student-card-user-" . $user->id . ".pdf");
        $pdf->Output($filename);
        exit;
    }

    /**
     * Renders page 1 with user identity.
     *
     * @param TCPDF $pdf
     * @param stdClass $user
     * @return void
     * @throws coding_exception
     * @throws moodle_exception
     */
    private static function render_front_page(TCPDF $pdf, stdClass $user): void {
        $pdf->AddPage("L", [self::CARD_WIDTH, self::CARD_HEIGHT]);

        $pdf->Rect(0, 0, self::CARD_WIDTH, self::CARD_HEIGHT, "F", [], [246, 248, 251]);
        $pdf->SetDrawColor(215, 220, 228);
        $pdf->RoundedRect(1.5, 1.5, self::CARD_WIDTH - 3, self::CARD_HEIGHT - 3, 3);

        $pdf->SetFont(self::PDF_FONT, "B", 15);
        $pdf->SetTextColor(35, 43, 56);
        $pdf->SetXY(5, 5);
        $pdf->Cell(76, 5, get_string("studentcard", "koperedashboard_attest"), 0, 1, "C");

        $photopath = self::get_user_photo_tempfile($user);

        if (!empty($photopath) && is_readable($photopath)) {
            $pdf->Image($photopath, 5, 15, 28);
        } else {
            $url = new moodle_url("/local/kopere_dashboard/plugins/attest/studentcard.php");
            $message = get_string("studentcardnophoto", "koperedashboard_attest");
            redirect($url, $message, null, notification::NOTIFY_ERROR);
        }

        $userfullname = fullname($user);
        $cpf = self::get_user_cpf($user);
        $labelcourse = get_string("course");
        $course = self::get_first_visible_enrolled_course($user->id);
        if (!$course) {
            throw new moodle_exception("studentcardnovisiblecourses", "koperedashboard_attest");
        }

        $userfullname = s($userfullname);
        $useremail = s($user->email);
        $cpf = s($cpf);
        $labelcourse = s($labelcourse);
        $coursefullname = s(format_string($course->fullname));

        $pdf->SetFont(self::PDF_FONT, "", 6.9);
        $html = "
            <div style=\"font-family:" . self::PDF_FONT . ";\">
                <h3>{$userfullname}</h3>
                <div>{$useremail}</div>
                <div><b>CPF</b>: {$cpf}</div>
                <div><b>{$labelcourse}</b>: {$coursefullname}</div>
            </div>";
        $pdf->writeHTMLCell(50, 40, 35, 14, $html, 0, 0, false, true, "L");
    }

    /**
     * Renders page 2 with validator and digital signature info.
     *
     * @param TCPDF $pdf
     * @param stdClass $user
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     * @throws \core\exception\moodle_exception
     */
    private static function render_back_page(TCPDF $pdf, stdClass $user): void {
        $pdf->AddPage("L", [self::CARD_WIDTH, self::CARD_HEIGHT]);

        $pdf->Rect(0, 0, self::CARD_WIDTH, self::CARD_HEIGHT, "F", [], [250, 250, 250]);
        $pdf->SetDrawColor(215, 220, 228);
        $pdf->RoundedRect(1.5, 1.5, self::CARD_WIDTH - 3, self::CARD_HEIGHT - 3, 3);

        $code = self::build_validation_code($user->id);
        $validationurl = self::build_validation_url($user->id, $code)->out(false);

        $pdf->SetFont(self::PDF_FONT, "B", 8.5);
        $pdf->SetTextColor(35, 43, 56);
        $pdf->SetXY(5, 5);
        $pdf->Cell(45, 5, get_string("studentcardsignaturetitle", "koperedashboard_attest"), 0, 1, "L");

        $pdf->SetFont(self::PDF_FONT, "", 6.3);
        $pdf->SetXY(5, 12);
        $label = get_string("studentcardsignaturedesc", "koperedashboard_attest");
        $pdf->MultiCell(46, 10, $label, 0, "L");

        $pdf->SetFont(self::PDF_FONT, "B", 6.5);
        $pdf->SetXY(5, 32);
        $label = get_string("footer_title", "koperedashboard_attest");
        $pdf->MultiCell(46, 10, "{$label}:", 0, "L");

        $pdf->SetFont(self::PDF_MONO_FONT, "", 6.4);
        $pdf->SetXY(5, 35);
        $pdf->MultiCell(70, 8, $code, 0, "L");

        $qrcodestyle = [
            "border" => 0,
            "padding" => 0,
            "fgcolor" => [0, 0, 0],
            "bgcolor" => false,
        ];

        $pdf->write2DBarcode($validationurl, "QRCODE", 58, 10, 22, 22, $qrcodestyle, "N");

        $pdf->SetFont(self::PDF_FONT, "", 5.5);
        $url = htmlspecialchars($validationurl, ENT_QUOTES, "UTF-8");
        $html = '<a href="' . $url . '" style="font-family:' . self::PDF_FONT . '; color:#000000; ' .
            'text-decoration:none;">' . $url . '</a>';
        $pdf->writeHTMLCell(76, 8, 5, 39.5, $html, 0, 0, false, true, "L");

        self::apply_existing_attest_signature($pdf);
    }

    /**
     * Reuses the same digital signature logic already used by the attest PDF.
     *
     * If your current attest PDF already has a method/class for signing,
     * call it here. The fallback below uses TCPDF signature directly.
     *
     * @param TCPDF $pdf
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    private static function apply_existing_attest_signature(TCPDF $pdf): void {
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
            return;
        }

        $signatureinfo = [
            "Name" => "Kopere Dashboard",
            "Location" => $CFG->wwwroot,
            "Reason" => get_string("studentcard", "koperedashboard_attest"),
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

        $pdf->setSignatureAppearance(5, 30.5, 26, 10);
    }

    /**
     * Exports the user profile photo to a temporary file.
     *
     * @param stdClass $user
     * @return string|null
     * @throws coding_exception
     */
    public static function get_user_photo_tempfile(stdClass $user): ?string {
        if (empty($user->picture)) {
            return null;
        }

        $context = context_user::instance($user->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            "user",
            "icon",
            0,
            "timemodified DESC, id DESC",
            false
        );

        if (empty($files)) {
            return null;
        }

        $file = reset($files);
        if (!$file) {
            return null;
        }

        $temppath = make_request_directory() . "/studentcard_user_" . $user->id . "_" . clean_filename($file->get_filename());
        $file->copy_content_to($temppath);

        return $temppath;
    }

    /**
     * Returns the visible enrolled courses for a user.
     *
     * @param int $userid
     * @return array
     */
    public static function get_visible_enrolled_courses(int $userid): array {
        $courses = enrol_get_users_courses($userid, true, "id, fullname, visible", "fullname ASC");

        return array_filter($courses, static function(stdClass $course): bool {
            return !empty($course->visible);
        });
    }

    /**
     * Checks whether the user has at least one visible enrolled course.
     *
     * @param int $userid
     * @return bool
     */
    public static function has_visible_enrolled_courses(int $userid): bool {
        return !empty(self::get_visible_enrolled_courses($userid));
    }

    /**
     * Returns the first visible enrolled course for the user.
     *
     * @param int $userid
     * @return ?stdClass
     */
    public static function get_first_visible_enrolled_course(int $userid): ?stdClass {
        $courses = self::get_visible_enrolled_courses($userid);

        if (!$courses) {
            return null;
        }

        $course = reset($courses);

        return $course ?: null;
    }
}
