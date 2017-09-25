<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mailing list Driver
    |--------------------------------------------------------------------------
    */
    'driver' => env('MAILING_LIST_DRIVER', 'mailchimp'),

    'mailchimp' => [
        'api' => [
            'secret' => env('MAILCHIMP_SECRET'),
            'apiUri' => env('MAILCHIMP_API_URI'),
        ],

        'lists' => [
            'ico' => strval(env('MAILCHIMP_LIST_ICO_ID')),
            'beta' => strval(env('MAILCHIMP_LIST_BETA_ID'))
        ]
    ],

    'mailgun' => [
        'api' => [
            'domain' => env('MAILGUN_DOMAIN'),
            'secret' => env('MAILGUN_SECRET'),
            'apiUri' => env('MAILGUN_API_URI'),
        ],

        'lists' => [
            'ico' => env('MAILGUN_LIST_ICO_ID'),
            'beta' => env('MAILGUN_LIST_BETA_ID')
        ]
    ]

];
