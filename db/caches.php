<?php

defined('MOODLE_INTERNAL') || die();

$definitions = [
    'performancemonitor' => [
        'mode' => cache_store::MODE_APPLICATION,
        'ttl' => 600,
        'staticacceleration' => true,
    ],
];