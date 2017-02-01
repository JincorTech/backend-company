<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 9:38 PM
 */

use App\Domains\Company\Entities\CompanyType;

class CompanyTypeFactory implements FactoryInterface
{

    public static function make()
    {
        $faker = \Faker\Factory::create('en_US');
        return new CompanyType([
            'en' => $faker->companySuffix,
            'ru' => $faker->companySuffix,
        ], strtoupper($faker->word));
    }

}