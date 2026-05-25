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
 * index.php
 *
 * @package   koperedashboard_benchmark
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_benchmark\service\benchmark_service;
use local_kopere_dashboard\output\layout;

require_once(__DIR__ . '/../../../../config.php');

$context = context_system::instance();
require_login();

if (!has_capability('koperedashboard/benchmark:run', $context) && !has_capability('koperedashboard/benchmark:run', $context)) {
    throw new required_capability_exception($context, 'koperedashboard/benchmark:run', 'nopermissions', '');
}

$PAGE->set_url(new moodle_url('/local/kopere_dashboard/plugins/benchmark/index.php'));
$PAGE->add_body_class('local-kopere_dashboard');
$PAGE->set_context($context);
$PAGE->set_title(get_string('menu_title', 'koperedashboard_benchmark'));

$mustachedata = [
    "runurl" => new moodle_url("/local/kopere_dashboard/plugins/benchmark/", ["execute" => 1]),
    "canrun" => has_capability("koperedashboard/benchmark:run", $context),
    "environment" => benchmark_service::get_environment(),
    "checks" => benchmark_service::get_recommendations(),
];

if (optional_param("execute", false, PARAM_INT)) {
    $results = benchmark_service::run_all();

    $mustachedata += [
        "hasexecute" => true,
        'summary' => $results['summary'],
        'results' => $results['results'],
        'environment' => $results['environment'],
        'checks' => $results['checks'],
    ];
}

$content = $OUTPUT->render_from_template("koperedashboard_benchmark/index", $mustachedata);
layout::page_render($context, $content, true);
