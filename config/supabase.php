<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Supabase integration. Make sure to set these
    | values in your .env file for security.
    |
    */

    'url' => env('SUPABASE_URL', 'https://rosmfuawkgwmjvovhcbz.supabase.co'),
    'key' => env('SUPABASE_KEY', 'sb_publishable_1MDcNwY-Wm2pyxr725V9qQ_whFupe5q'),
    'secret_key' => env('SUPABASE_SECRET_KEY', 'sb_secret_er2V_JCWA0ftdA-qT952sg_t505vR1W'),
    'project_id' => env('SUPABASE_PROJECT_ID', 'rosmfuawkgwmjvovhcbz'),
    'jwt_secret' => env('SUPABASE_JWT_SECRET'),
];