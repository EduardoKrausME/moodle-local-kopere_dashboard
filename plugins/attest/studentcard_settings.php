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
 * Student card front and back Mustache settings.
 *
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_attest\form\studentcard_settings_form;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/attest:manage", $context);

$url = new moodle_url("/local/kopere_dashboard/plugins/attest/studentcard_settings.php");

$PAGE->set_url($url);
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("studentcard_settings_title", "koperedashboard_attest"));

$loadtemplate = static function(string $configname, string $filename): string {
    $template = get_config("koperedashboard_attest", $configname);
    if ($template !== false && trim((string) $template) !== "") {
        return (string) $template;
    }

    $defaultfile = __DIR__ . "/db/files/" . $filename;
    return is_readable($defaultfile) ? (string) file_get_contents($defaultfile) : "";
};

$form = new studentcard_settings_form($url);
$form->set_data((object) [
    "studentcard_front_mustache" => $loadtemplate(
        "studentcard_front_mustache",
        "studentcard_front.mustache"
    ),
    "studentcard_back_mustache" => $loadtemplate(
        "studentcard_back_mustache",
        "studentcard_back.mustache"
    ),
]);

if ($form->is_cancelled()) {
    redirect(new moodle_url("/local/kopere_dashboard/plugins/attest/studentcard.php"));
}

if ($data = $form->get_data()) {
    set_config(
        "studentcard_front_mustache",
        $data->studentcard_front_mustache,
        "koperedashboard_attest"
    );
    set_config(
        "studentcard_back_mustache",
        $data->studentcard_back_mustache,
        "koperedashboard_attest"
    );

    redirect($url, get_string("changessaved"), null, \core\output\notification::NOTIFY_SUCCESS);
}

$makeplaceholders = static function(array $keys, string $prefix): array {
    $placeholders = [];
    foreach ($keys as $key) {
        $placeholders[] = [
            "key" => "{{{$key}}}",
            "description" => get_string("{$prefix}{$key}", "koperedashboard_attest"),
        ];
    }

    return $placeholders;
};

$content = $OUTPUT->render_from_template("koperedashboard_attest/studentcard_settings", [
    "formhtml" => $form->render(),
    "frontplaceholders" => $makeplaceholders([
        "title",
        "photo",
        "fullname",
        "email",
        "cpflabel",
        "cpf",
        "courselabel",
        "coursefullname",
        "userid",
    ], "studentcard_front_placeholder_"),
    "backplaceholders" => $makeplaceholders([
        "title",
        "description",
        "hashlabel",
        "validationcode",
        "verifyurl",
        "qrcode",
        "sitefullname",
        "fullname",
        "userid",
    ], "studentcard_back_placeholder_"),
]);

layout::page_render($context, $content, true);
