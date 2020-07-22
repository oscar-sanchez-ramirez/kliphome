<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],
    'onesignal' => [
        // 'app_id' => env('a8a80cb1-1654-4ccc-92be-54dff3e0171e'),
        // 'rest_api_key' => env('NTRkOGJkNGUtOGJhMy00NTMyLWEyYWQtOTk2MTMyM2ZiYTA1')
        'app_id' => 'a8a80cb1-1654-4ccc-92be-54dff3e0171e',
        'rest_api_key' => 'NTRkOGJkNGUtOGJhMy00NTMyLWEyYWQtOTk2MTMyM2ZiYTA1'
    ],
    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    'facebook' => [
        'client_id'     => '187557089277557',
        'client_secret' => 'cc567646c2422d126f9fa58a499e1e79',
        'redirect'      => 'https://kliphome.com/login/callback/facebook',
    ],

];
