<?php

return [

    /*
    |--------------------------------------------------------------------------
    | External ECHO report API
    |--------------------------------------------------------------------------
    |
    | HTTP(S) endpoint that returns JSON. Expected shapes:
    | - [ { "cardio_id": "...", ... }, ... ]
    | - { "data": [ ... ] } or { "reports": [ ... ] }
    |
    */
    'api_url' => env('ECHO_REPORT_API_URL'),

    'timeout' => (int) env('ECHO_REPORT_API_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Pad / letterhead printing
    |--------------------------------------------------------------------------
    |
    | Extra top margin (mm) so report content starts below pre-printed hospital
    | header when printing on institutional pad. PDF download uses the same value.
    |
    */
    'letterhead_top_mm' => (int) env('ECHO_LETTERHEAD_TOP_MM', 40),

    /*
    |--------------------------------------------------------------------------
    | Report signature block (PDF + screen)
    |--------------------------------------------------------------------------
    |
    | Right-aligned doctor block at the end of the report. Override all lines with
    | ECHO_REPORT_SIGNATURE_LINES (pipe-separated).
    |
    */
    'signature_lines' => filled(env('ECHO_REPORT_SIGNATURE_LINES'))
        ? array_values(array_filter(array_map(
            'trim',
            explode('|', (string) env('ECHO_REPORT_SIGNATURE_LINES'))
        )))
        : [
            'Dr. Md Akhtaruzzaman',
            'MBBS, MD (Cardiology)',
            'Associate Professor of Cardiology',
            'Clinical & Interventional Cardiologist',
            'Bangladesh Medical College Hospital',
        ],
];
