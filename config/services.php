<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'oracle-idcs' => [    
        'base_url' => env('ORACLEIDCS_BASE_URL'),
        'client_id' => env('ORACLEIDCS_CLIENT_ID'),  
        'client_secret' => env('ORACLEIDCS_CLIENT_SECRET'),  
        'redirect' => env('ORACLEIDCS_REDIRECT'),
        'enterprise_key' => env('ORACLEIDCS_ENTERPRISE_KEY'),
        'group_name' => env('ORACLEIDCS_GROUP_NAME'),
    ],      

];
