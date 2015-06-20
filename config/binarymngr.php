<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binary Version End-of-Live Threshold
    |--------------------------------------------------------------------------
    |
    | The threshold in days for binary versions to accept before marking them as
    | unsupported.
    |
    | A positive value means that a message is only created AFTER the version has
    | already reached end-of-life.
    |
    */

    'eol_threshold' => 1,

    /*
    |--------------------------------------------------------------------------
    | Binary Versions Gatherers Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | The lifetime in minutes that binary versions gatherers should be kept in
    | cache before looking them up again.
    |
    */
    'gatherers_cache_lifetime' => 10,

];
