<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/06/2017
 * Time: 04:15
 */

namespace App\Core\Services;


use App\Core\Interfaces\MessengerServiceInterface;

class MessengerService extends BaseRestService implements MessengerServiceInterface
{

    public function __construct()
    {
        $options = [
            'base_uri' => config('services.messenger.uri'),
        ];
        parent::__construct($options);
    }

    /**
     * Register employee in messenger service (to allow auth by JWT in messenger)
     * @param array $data
     * @param string $session
     * @return bool
     */
    public function register(array $data, string $session)
    {
        if (!array_key_exists('auth', $data)) {
            $data['auth'] = [
                'type' => 'm.login.dummy',
                'session' => $session,
            ];
        }
        $response = $this->client->post('/_matrix/client/v2_alpha/register', [
            'json' => $data
        ]);

        return $response->getStatusCode() === 200;
    }

}