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

];
