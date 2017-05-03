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
     * @var Country
     * @ODM\ReferenceOne(
     *     targetDocument="App\Core\Dictionary\Entities\Country",
     *     cascade={"persist"}
     * )
     */
    protected $country;

    /**
     * @var App\Core\Dictionary\Entities\City
     * @ODM\ReferenceOne(
     *     targetDocument="App\Core\Dictionary\Entities\City",
     *     cascade="{persist}"
     * )
     */
    protected $city;

    public function __construct(string $address, Country $country, $city = null)
    {
        $this->formattedAddress = $address;
        $this->country = $country;
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getFormattedAddress() : string
    {
        return $this->formattedAddress;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'formattedAddress' => $this->getFormattedAddress(),
            'country' => $this->country->getId(),
            'city' => $this->city->getId(),
        ];
    }

    /**
     * @return Country
     */
    public function getCountry() : Country
    {
        return $this->country;
    }

    /**
     * @return App\Core\Dictionary\Entities\City|null
     */
    public function getCity()
    {
        return $this->city;
    }

    public function getStringValues() : array
    {
        $values = [];
        foreach ($this->country->getNames() as $locale => $name) {
            $values[$locale]['country'] = $name;
        }
        if ($this->getCity()) {
            foreach ($this->city->getNames() as $loc => $city) {
                $values[$loc]['city'] = $city;
            }
        }

        return $values;
    }
}
