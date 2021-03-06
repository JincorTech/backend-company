<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/20/16
 * Time: 2:45 PM
 *
 * Country Entity
 */

namespace App\Core\Dictionary\Entities;

use App\Core\ValueObjects\CountryISOCodes;
use App\Core\ValueObjects\TranslatableString;
use GeoJson\Geometry\MultiPolygon;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Country.
 *
 * @ODM\Document(
 *     collection="countries",
 *     repositoryClass="App\Core\Dictionary\Repositories\CountryRepository"
 * )
 */
class Country
{
    /**
     * @var string
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var TranslatableString
     *
     * @ODM\Field(type="translatableString")
     */
    protected $names;

    /**
     * @var Currency
     * @ODM\ReferenceOne(
     *     targetDocument="App\Core\Dictionary\Entities\Currency",
     *     cascade={"persist"}
     * )
     */
    protected $currency;

    /**
     * @var MultiPolygon
     * @ODM\Field(type="hash")
     */
    protected $bounds;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $phoneCode;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $flagUrl;

    /**
     * @var \App\Core\ValueObjects\CountryISOCodes
     * @ODM\EmbedOne(targetDocument="App\Core\ValueObjects\CountryISOCodes")
     */
    protected $ISOCodes;

    /**
     * Country constructor.
     *
     * @param array $names
     * @param string $code
     * @param \App\Core\ValueObjects\CountryISOCodes $ISOCodes
     * @param Currency $currency
     */
    public function __construct(array $names, string $code, CountryISOCodes $ISOCodes, Currency $currency)
    {
        $this->id = Uuid::uuid4();
        $this->setNames($names);
        $this->setPhoneCode($code);
        $this->setISOCodes($ISOCodes);
        $this->setCurrency($currency);
    }

    /**
     * @param string $locale
     * @return mixed
     */
    public function getName($locale = null) : string
    {
        if (is_array($this->names)) {
            $this->names = new TranslatableString($this->names);
        }
        return $this->names->getValue($locale);
    }


    public function getNames()
    {
        if (is_array($this->names)) {
            $this->names = new TranslatableString($this->names);
        }
        return $this->names->getValues();
    }


    /**
     * @return Currency
     */
    public function getCurrency() : Currency
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getPhoneCode() : string
    {
        return $this->phoneCode;
    }

    /**
     * @return int
     */
    public function getNumericCode() : int
    {
        return $this->ISOCodes->getNumericCode();
    }

    /**
     * @return string
     */
    public function getAlpha2Code() : string
    {
        return $this->ISOCodes->getAlpha2Code();
    }

    /**
     * @return string
     */
    public function getAlpha3Code() : string
    {
        return $this->ISOCodes->getAlpha3Code();
    }

    /**
     * @return string
     */
    public function getISO2Code() : string
    {
        return $this->ISOCodes->getISO2Code();
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        if ($this->id instanceof UuidInterface) {
            return $this->id->toString();
        }
        return $this->id;
    }

    /**
     * @param array $names
     */
    public function setNames(array $names)
    {
        $this->names = new TranslatableString($names);
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param string $code
     */
    public function setPhoneCode(string $code)
    {
        if (empty($code)) {
            throw new InvalidArgumentException("Phone code can't be empty");
        }
        if (!starts_with($code, '+')) {
            throw new InvalidArgumentException("Phone code must always start with '+'");
        }
        if (strlen($code) < 2) {
            throw new InvalidArgumentException('Phone code cant be less then 2 symbols length');
        }
        $this->phoneCode = $code;
    }

    /**
     * @param \App\Core\ValueObjects\CountryISOCodes $ISOCodes
     */
    public function setISOCodes(CountryISOCodes $ISOCodes)
    {
        $this->ISOCodes = $ISOCodes;
    }
}
