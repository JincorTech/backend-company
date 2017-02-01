<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/29/16
 * Time: 11:06 PM
 */

namespace App\Core\ValueObjects;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Core\Dictionary\Entities\Country;
use GeoJson\Geometry\Point;
use JsonSerializable;
use App;

/**
 * Class Address.
 *
 * @ODM\EmbeddedDocument
 */
class Address
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $formattedAddress;

    /**
     * @var Point
     * @ODM\Field(type="hash")
     */
    protected $geoPoint;

    /**
     * @var Country
     * @ODM\ReferenceOne(targetDocument="App\Core\Dictionary\Entities\Country")
     */
    protected $country;

    public function __construct(string $address, Country $country, Point $coordinates)
    {
        $this->formattedAddress = $address;
        $this->geoPoint = $coordinates->jsonSerialize();
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getFormattedAddress() : string
    {
        return $this->formattedAddress;
    }

    /**
     * @return Point
     */
    public function getGeoPoint() : Point
    {
        return $this->geoPoint;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'formattedAddress' => $this->getFormattedAddress(),
            'geoPoint' => $this->getGeoPoint()->jsonSerialize(),
            'country' => $this->country->getId(),
        ];
    }

    public function getCountryId() : string
    {
        return $this->country->getId();
    }
}
