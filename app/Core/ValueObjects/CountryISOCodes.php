<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/20/16
 * Time: 4:37 PM
 */

namespace App\Core\ValueObjects;

use InvalidArgumentException;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CountryISOCodes.
 *
 * Data Value Object contaning ISO specification of the country
 *
 *
 * @ODM\EmbeddedDocument
 */
class CountryISOCodes
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $ISO2Code;

    /**
     * @var int
     * @ODM\Field(type="integer")
     */
    protected $numericCode;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $alpha2Code;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $alpha3Code;

    /**
     * CountryISOCodes constructor.
     * @param string $iso2
     * @param string $numeric
     * @param string $alpha2
     * @param string $alpha3
     */
    public function __construct(string $iso2, string $numeric, string $alpha2, string $alpha3)
    {
        $this->setISO2Code($iso2);
        $this->setNumericCode($numeric);
        $this->setAlpha2Code($alpha2);
        $this->setAlpha3Code($alpha3);
    }

    /**
     * @return string
     */
    public function getISO2Code() : string
    {
        return $this->ISO2Code;
    }

    /**
     * @param string $ISO2Code
     */
    public function setISO2Code(string $ISO2Code)
    {
        if (strlen($ISO2Code) !== 13) {
            throw new InvalidArgumentException('ISO2Code must be 13 characters length!');
        }
        $this->ISO2Code = $ISO2Code;
    }

    /**
     * @return int
     */
    public function getNumericCode() : int
    {
        return $this->numericCode;
    }

    /**
     * @param int $numericCode
     */
    public function setNumericCode(int $numericCode)
    {
        if ($numericCode <= 0 || $numericCode > 999) {
            throw new InvalidArgumentException('Numeric code must be in range from 1 to 999');
        }
        $this->numericCode = $numericCode;
    }

    /**
     * @return string
     */
    public function getAlpha2Code() : string
    {
        return $this->alpha2Code;
    }

    /**
     * @param string $alpha2Code
     */
    public function setAlpha2Code(string $alpha2Code)
    {
        if (strlen($alpha2Code) !== 2) {
            throw new InvalidArgumentException('alpha2Code must be 2 characters length!');
        }
        $this->alpha2Code = $alpha2Code;
    }

    /**
     * @return string
     */
    public function getAlpha3Code() : string
    {
        return $this->alpha3Code;
    }

    /**
     * @param string $alpha3Code
     */
    public function setAlpha3Code(string $alpha3Code)
    {
        if (strlen($alpha3Code) !== 3) {
            throw new InvalidArgumentException('alpha2Code must be 3 characters length!');
        }
        $this->alpha3Code = $alpha3Code;
    }
}
