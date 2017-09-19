<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 14:33
 */

namespace App\Core\Services\Verification;

use App\Core\Services\Verification\Exceptions\VerificationFatalError;
use GuzzleHttp\Client;

/**
 * Class RestVerificationService
 * @package App\Core\Services\Verification
 */
class RestVerificationService implements VerificationService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * RestVerificationService constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.verification.uri') . '/methods',
            'auth' => [
                'api',
                config('services.verification.jwt'),
            ],
            'headers' => [
                'Accept' => 'application/vnd.jincor+json; version=' . config('services.verification.version', 1)
            ]
        ]);
    }

    /**
     * @param string $httpVerb
     * @param string $apiUrl
     * @param array $parameters
     * @return array
     */
    protected function doApiRequest(string $httpVerb, string $apiUrl, array $parameters = []): array
    {
        $response = $this->client->request($httpVerb, $apiUrl, $parameters);

        $data = json_decode($response->getBody()->getContents(), true);
        if (!$data) {
            throw new VerificationFatalError('Error occurred when json decoding');
        }

        if (!array_key_exists('status', $data)) {
            throw new VerificationFatalError('No status received');
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function initiate(VerificationMethod $method): VerificationIdentifier
    {
        $responseArray = $this->doApiRequest(
            'post',
            "/{$method->getMethodType()}/actions/initiate",
            $method->getRequestParameters()
        );

        if (!array_key_exists('verificationId', $responseArray)) {
            throw new VerificationFatalError('No verificationId');
        }

        if ($responseArray['status'] !== 200) {
            throw new VerificationFatalError($responseArray['error'] ?? 'Unknown behavior', $responseArray['status']);
        }

        return (new VerificationIdentifier($responseArray['verificationId']))
            ->setExpiredOn($responseArray['expiredOn'] ?? null);
    }

    /**
     * @inheritdoc
     */
    public function validate(VerificationData $data): bool
    {
        $responseArray = $this->doApiRequest(
            'post',
            "/{$data->getMethodType()}/verifiers" .
                "/{$data->getVerificationIdentifier()->getVerificationId()}/actions/validate",
            $data->getFormattedApiRequestParameters()
        );

        return $responseArray['status'] === 200;
    }
}
