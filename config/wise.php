<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wise API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Wise API settings. You can choose between
    | sandbox and production environments.
    |
    */

    // API Key for authentication
    'api_key' => env('WISE_API_KEY'),

    // Environment (sandbox or live)
    'environment' => env('WISE_ENVIRONMENT', 'sandbox'),

    // Base URLs for different environments
    'base_url' => [
        'sandbox' => 'https://api.sandbox.transferwise.tech',
        'live' => 'https://api.wise.com',
    ],

    // Request if using a proxy
    'proxy' => '',

    'version' => 'v1',

    // Request timeout in seconds
    'timeout' => env('WISE_TIMEOUT', 30),
];
