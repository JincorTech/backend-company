<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/20/16
 * Time: 2:47 PM
 */

namespace App\Core\Dictionary\Entities;

use App\Core\ValueObjects\CurrencyISOCodes;
use App\Core\ValueObjects\TranslatableString;
use Ramsey\Uuid\Uuid;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Currency.
 *
 * @ODM\Document(collection="currencies")
 */
class Currency
{
    /**
     * @var string
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var TranslatableString
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\TranslatableString"
     * )
     */
    protected $names;

    /**
     * @var CurrencyISOCodes
     * @ODM\EmbedOne(targetDocument="App\Core\ValueObjects\CurrencyISOCodes")
     */
    protected $ISOCodes;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $sign;

    /**
     * Currency constructor.
     * @param array $names
     * @param CurrencyISOCodes $ISOCodes
     * @param string $sign
     */
    public function __construct(array $names, CurrencyISOCodes $ISOCodes, string $sign)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->setNames($names);
        $this->setISOCodes($ISOCodes);
        $this->setSign($sign);
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getName($locale = null) : string
    {
        return $this->names->getValue($locale);
    }

    /**
     * @return string
     */
    public function getAlpha3Code() : string
    {
        return $this->ISOCodes->getAlpha3();
    }

    /**
     * @return int
     */
    public function getNumericCode() : int
    {
        return $this->ISOCodes->getNumeric();
    }

    /**
     * @return string
     */
    public function getSign() : string
    {
        return $this->sign;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
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
     * @param CurrencyISOCodes $ISOCodes
     */
    public function setISOCodes(CurrencyISOCodes $ISOCodes)
    {
        $this->ISOCodes = $ISOCodes;
    }

    /**
     * @param string $sign
     */
    public function setSign(string $sign)
    {
        if (empty($sign)) {
            throw new \InvalidArgumentException('Sign of the currency cannot be empty');
        }
        $this->sign = $sign;
    }

}
