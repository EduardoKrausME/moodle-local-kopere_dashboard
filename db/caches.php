<?php

defined('MOODLE_INTERNAL') || die();

$definitions = [
    'performancemonitor_cache' => [
        'mode' => cache_store::MODE_APPLICATION,
        'ttl' => 10* 60, // 10 minutos
    ],
    'report_getdata_cache' => [
        'mode' => cache_store::MODE_APPLICATION,
        'ttl' => 2 * 24 * 60 * 60, // 3d
    ],
];