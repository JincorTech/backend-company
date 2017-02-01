<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:09 AM
 */

use App\Domains\Company\Entities\CompanyType;
use App\Domains\Company\Entities\Company;
use App\Core\Dictionary\Entities\Country;
use App\Core\ValueObjects\Address;
use Faker\Factory;

class CompanyFactory implements FactoryInterface
{


    /**
     * Make a new random company
     * @return Company
     */
    public static function make()
    {
        $ru = Factory::create('ru_RU');

        $companyType = CompanyTypeFactory::make();

        return new Company($ru->company, AddressFactory::make(), $companyType);
    }


}