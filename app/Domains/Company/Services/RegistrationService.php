<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 1:57 AM
 */

namespace App\Domains\Company\Services;

use App\Core\Dictionary\Repositories\CountryRepository;
use App\Core\Services\AddressService;

class RegistrationService
{
    /**
     * @var AddressService
     */
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function register(
        string $country,
        string $tax,
        string $legalName,
        string $formattedAddress,
        array $coordinates,
        string $companyType
    ) {
        $address = $this->addressService->build($formattedAddress, $country, $coordinates);
    }

    public function getInfoByTaxNumber(string $country, string $taxNumber)
    {
        //TODO
    }

    public function validateTaxNumber()
    {
    }
}
