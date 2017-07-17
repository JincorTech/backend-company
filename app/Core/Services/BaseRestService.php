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
     * @param array $options
     */
    public function __construct($options)
    {
        $headers = [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
        $options = array_merge($options, $headers);
        $this->client = new Client($options);
    }
}
