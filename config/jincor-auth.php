<?php

return [
    'uri' => env('IDENTITY_SCHEME').'://'.env('IDENTITY_HOST').':'.env('IDENTITY_PORT'),
    'jwt' => env('IDENTITY_JWT'),
];
