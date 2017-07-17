<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/6/16
 * Time: 11:42 PM
 */

namespace App\Core\Services\INNInfo;

use App\Core\Services\BaseRestService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DadataService extends BaseRestService implements INNInfoInterface
{
    public function __construct()
    {
        $options = [
            'base_uri' => 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/',
        ];
        parent::__construct($options);
    }

    /**
     * @param string $INN
     * @return array|null
     */
    public function getInfoByINN(string $INN)
    {
        $response = $this->client->post('party', [
            'json' => [
                'query' => $INN,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Token '.env('DADATA_TOKEN'),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if (!array_key_exists('suggestions', $data) || empty($data['suggestions'])) {
                return;
            }
            $firstMatch = array_shift($data['suggestions']);
            if ($this->validateInfo($firstMatch) && $firstMatch['data']['inn'] === $INN) {
                return $this->normalize($firstMatch, $INN);
            }

            return;
        }
        throw new HttpException(500, 'Dadata service returned wrong HTTP status'); // TODO: translate exception
    }

    /**
     * Retunrs true if we can use info that we got.
     *
     * @param array $rawInfo
     * @return bool
     */
    private function validateInfo(array $rawInfo) : bool
    {
        if (!array_key_exists('data', $rawInfo)) {
            return false;
        }
        $data = $rawInfo['data'];
        if (!array_key_exists('name', $data) || !array_key_exists('short_with_opf', $data['name'])) {
            return false;
        }
        if (!array_key_exists('address', $data) || !array_key_exists('value', $data['address'])) {
            return false;
        }
        if (!array_key_exists('opf', $data) || !array_key_exists('full', $data['opf'])) {
            return false;
        }

        return true;
    }

    /**
     * @param array $rawData
     * @param string $taxNumber
     * @return array
     */
    private function normalize(array $rawData, string $taxNumber) : array
    {
        return [
            'legalName' => $rawData['data']['name']['short_with_opf'],
            'taxNumber' => $taxNumber,
            'formattedAddress' => $rawData['data']['address']['value'],
            'companyType' => $rawData['data']['opf']['full'],
        ];
    }
}
