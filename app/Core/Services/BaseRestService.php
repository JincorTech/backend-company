<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/6/16
 * Time: 11:42 PM
 */

namespace App\Core\Services;

use GuzzleHttp\Client;

abstract class BaseRestService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * BaseRestService constructor.
     *
     * @param string $baseUri
     */
    public function __construct($baseUri)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);
    }
}
