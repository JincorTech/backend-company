<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'apiUri' => env('MAILGUN_API_URI'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'identity' => [
        'uri' => env('IDENTITY_SCHEME') . '://' . env('IDENTITY_HOST') . ':' . env('IDENTITY_PORT'),
        'jwt' => env('IDENTITY_JWT'),
    ],

    'verification' => [
        'uri' => env('VERIFICATION_SCHEME') . '://' . env('VERIFICATION_HOST') . ':' . env('VERIFICATION_PORT'),
        'jwt' => env('VERIFICATION_JWT'),
    ],

    'messenger' => [
        'uri' => env('MESSENGER_SCHEME') . '://' . env('MESSENGER_HOST') . ':' . env('MESSENGER_PORT'),
    ],

];
