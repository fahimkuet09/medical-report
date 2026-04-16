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

];
