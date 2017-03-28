<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:36 AM
 */

use App\Core\ValueObjects\Address;
use Faker\Factory;

class AddressFactory implements FactoryInterface
{


    public static function make()
    {
        $ru = Factory::create('ru_RU');
        return new Address($ru->address . ', ' . $ru->city, CountryFactory::make());
    }

}