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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'mercadolibre' => [
        'base_uri' => env('MERCADO_LIBRE_URL'),
        'client_id' => env('MERCADO_LIBRE_CLIENT_ID'),
        'client_secret' => env('MERCADO_LIBRE_CLIENT_SECRET'),
    ],

    'esimgo' => [
        'base_uri' => env('ESIM_GO_URL'),
    ],

    'simco' => [
        'base_uri' => env('SIMCO_URL'),
        'consumer_key' => env('SIMCO_CONSUMER_KEY'),
        'consumer_secret' => env('SIMCO_CONSUMER_SECRET'),
    ],

];
