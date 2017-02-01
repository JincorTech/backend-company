<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 12:32 AM
 */

namespace App\Core\Services;

use App\Core\Dictionary\Entities\Country;
use App\Core\Dictionary\Repositories\CountryRepository;
use App\Core\ValueObjects\Address;
use Doctrine\ODM\MongoDB\DocumentManager;
use GeoJson\Geometry\Point;
use InvalidArgumentException;

class AddressService
{
    protected $countryRepository;

    protected $dm;

    public function __construct()
    {
        $this->dm = \App::make(DocumentManager::class);
        $this->countryRepository = $this->dm->getRepository(Country::class);
    }

    public function build(string $address, string $country)//, array $coordinates)
    {
        if (empty($address)) {
            throw new InvalidArgumentException('Formatted address cannot be empty!');
        }
//        if (!array_key_exists('lng', $coordinates) || !array_key_exists('lat', $coordinates)) {
//            throw new InvalidArgumentException("Coordinates must have lat and lng keys");
//        }
        $geoCoordinates = [47.7467108,61.1279301]; //TODO: replace with real stuff
        $geoPoint = new Point($geoCoordinates);
        /** @var Country|null $country */
        $country = $this->countryRepository->find($country);
        if (!$country) {
            throw new InvalidArgumentException('Country must represent existing country');
        }

        return new Address($address, $country, $geoPoint);
    }
}
