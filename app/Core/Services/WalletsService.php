<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/11/2017
 * Time: 19:27
 */

namespace App\Core\Services;


use App\Core\Interfaces\WalletsServiceInterface;

class WalletsService extends BaseRestService implements WalletsServiceInterface
{

    public function __construct()
    {
        $options = [
            'base_uri' => config('services.wallets.uri'),
        ];
        parent::__construct($options);
    }

    /**
     * @param array $data
     * @param string $jwt
     * @return array
     */
    public function register(array $data, string $jwt)
    {
        $response = $this->client->post('/wallets/personal', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $jwt
            ],
            'body' => '{}',
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $data
     * @param string $jwt
     * @return array
     */
    public function registerCorporate(array $data, string $jwt)
    {
        $response = $this->client->post('/wallets/corporate', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $jwt
            ],
            'body' => '{}',
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }


}