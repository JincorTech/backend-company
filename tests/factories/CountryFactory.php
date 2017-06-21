<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:23 AM
 */

use App\Core\Dictionary\Entities\Country;
use App\Core\ValueObjects\CountryISOCodes;
use GeoJson\Geometry\MultiPolygon;
use Faker\Factory;


class CountryFactory implements FactoryInterface
{


    public static function make()
    {
        $currency = CurrencyFactory::make();
        $en = Factory::create();
        $ru = Factory::create('ru_RU');
        $names = [
            'en' => $en->country,
            'ru' => $ru->country,
        ];
        $countryCodes = new CountryISOCodes('RUS:123456043', '203', 'RU', 'RUS');
        $bounds = new MultiPolygon([]);
        return new Country($names, '+31', $countryCodes, $currency);
    }
}