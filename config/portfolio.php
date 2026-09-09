<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported portfolio locales
    |--------------------------------------------------------------------------
    |
    | Locales the public portfolio is available in, keyed by the URL segment
    | used to reach them (e.g. /es, /en). The default locale is used when no
    | preference can be determined from the visitor's browser.
    |
    */

    'locales' => ['es', 'en'],

    'default_locale' => 'es',

    /*
    |--------------------------------------------------------------------------
    | Admin user seeding
    |--------------------------------------------------------------------------
    |
    | Used by AdminUserSeeder to create/update the admin account from
    | environment variables, so real credentials never live in the codebase.
    |
    */

    'admin' => [
        'name' => env('ADMIN_NAME', 'Admin'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],
];
