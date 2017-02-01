<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:24 AM
 */

use App\Core\Dictionary\Entities\Currency;
use App\Core\ValueObjects\CurrencyISOCodes;
use Faker\Factory;

class CurrencyFactory implements FactoryInterface
{


    public static function make()
    {
        $en = Factory::create();
        $names = [
            'en' => 'Currency',
            'ru' => 'Валюта',
        ];
        $sign = '^';
        $codes = new CurrencyISOCodes($en->currencyCode, 304);
        return new Currency($names, $codes, $sign);
    }

}