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
     * Address constructor.
     * @param string $address
     * @param Country $country
     */
    public function __construct(string $address, Country $country)
    {
        $this->formattedAddress = $address;
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
     * @return Country
     */
    public function getCountry() : Country
    {
        return $this->country;
    }
}
