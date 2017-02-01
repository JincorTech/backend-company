<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/7/16
 * Time: 12:20 AM
 */

namespace App\Domains\Company\ValueObjects;

/**
 * Class InfoByTaxNumber.
 */
class InfoByTaxNumber
{
    /**
     * @var string
     */
    public $legalName;

    /**
     * @var string
     */
    public $taxNumber;

    /**
     * @var string
     */
    public $formattedAddress;

    /**
     * @var string
     */
    public $companyType;

    /**
     * InfoByTaxNumber constructor.
     *
     * @param string $name
     * @param string $taxNumber
     * @param string $address
     * @param string $type
     */
    public function __construct(string $name, string $taxNumber, string $address, $type)
    {
        $this->legalName = $name;
        $this->taxNumber = $taxNumber;
        $this->formattedAddress = $address;
        $this->companyType = $type;
    }
}
