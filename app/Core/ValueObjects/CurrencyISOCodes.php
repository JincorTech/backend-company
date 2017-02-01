<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/20/16
 * Time: 6:57 PM
 */

namespace App\Core\ValueObjects;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CurrencyISOCodes.
 *
 * @ODM\EmbeddedDocument
 */
class CurrencyISOCodes
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $alpha3Code;

    /**
     * @var int
     * @ODM\Field(type="integer")
     */
    protected $numericCode;

    /**
     * CurrencyISOCodes constructor.
     * @param string $alpha3
     * @param int $numeric
     */
    public function __construct(string $alpha3, int $numeric)
    {
        $this->setAlpha3Code($alpha3);
        $this->setNumericCode($numeric);
    }

    /**
     * @param string $code
     */
    public function setAlpha3Code(string $code)
    {
        if (strlen($code) !== 3) {
            throw new \InvalidArgumentException('Alpha-3 ISO code must be 3 chars in length');
        }
        $this->alpha3Code = $code;
    }

    /**
     * @param int $code
     */
    public function setNumericCode(int $code)
    {
        if (!is_numeric($code)) {
            throw new \InvalidArgumentException('Numeric ISO code should be numeric');
        }
        if ($code < 1 || $code > 999) {
            throw new \InvalidArgumentException('Numeric ISO code should be in range between 1 and 999');
        }
        $this->numericCode = $code;
    }

    /**
     * @return int
     */
    public function getNumeric() : int
    {
        return $this->numericCode;
    }

    /**
     * @return string
     */
    public function getAlpha3() : string
    {
        return $this->alpha3Code;
    }
}
