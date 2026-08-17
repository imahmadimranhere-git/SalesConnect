<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Visit Radius
    |--------------------------------------------------------------------------
    |
    | The maximum distance (in meters) a distributor is allowed to be
    | from a shop's saved GPS location in order to mark a visit.
    |
    */
    'radius_meters' => env('VISIT_RADIUS_METERS', 200),
];