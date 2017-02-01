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
     * @var array
     * @ODM\Field(type="hash")
     */
    protected $names;

    /**
     * @var Currency
     * @ODM\ReferenceOne(targetDocument="App\Core\Dictionary\Entities\Currency")
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
     * @param string $flag
     * @param MultiPolygon $bounds
     */
    public function __construct(array $names, string $code, CountryISOCodes $ISOCodes, Currency $currency, string $flag, MultiPolygon $bounds)
    {
        $this->id = Uuid::uuid4();
        $this->setNames($names);
        $this->setPhoneCode($code);
        $this->setISOCodes($ISOCodes);
        $this->setCurrency($currency);
        $this->setFlagUrl($flag);
        $this->setBounds($bounds);
    }

    /**
     * @param string $locale
     * @return mixed
     */
    public function getName(string $locale = 'en') : string
    {
        if (array_key_exists($locale, $this->names)) {
            return $this->names[$locale];
        }

        return $this->names['en'];
    }

    /**
     * @return Currency
     */
    public function getCurrency() : Currency
    {
        return $this->currency;
    }

    /**
     * @return MultiPolygon
     */
    public function getBounds() : MultiPolygon
    {
        return new MultiPolygon($this->bounds['coordinates']);
    }

    /**
     * @return string
     */
    public function getPhoneCode() : string
    {
        return $this->phoneCode;
    }

    /**
     * @return string
     */
    public function getFlagUrl() : string
    {
        return $this->flagUrl;
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
        if (!array_key_exists('en', $names)) {
            throw new InvalidArgumentException("English name('en' key) must be presented in names array");
        }
        $this->names = $names;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param MultiPolygon $bounds
     */
    public function setBounds(MultiPolygon $bounds)
    {
        $this->bounds = $bounds->jsonSerialize();
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
     * @param string $flagUrl
     */
    public function setFlagUrl(string $flagUrl)
    {
        if (empty($flagUrl)) {
            throw new InvalidArgumentException('Flag URL can not be empty!');
        }
        $this->flagUrl = $flagUrl;
    }

    /**
     * @param \App\Core\ValueObjects\CountryISOCodes $ISOCodes
     */
    public function setISOCodes(CountryISOCodes $ISOCodes)
    {
        $this->ISOCodes = $ISOCodes;
    }
}
