<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Server Timing enabled
    |--------------------------------------------------------------------------
    |
    | This configuration is used to enable the server timing measurement,
    | if set to false, the middleware will be bypassed
    |
    */

    'enabled' => env('USE_PROFILING_TRACE'),
];
