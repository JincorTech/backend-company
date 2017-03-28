<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 9:59 PM
 */

use App\Domains\Company\Entities\EconomicalActivityType;


class EconomicalActivityTypeFactory implements FactoryInterface
{


    public static function make()
    {
        $en = Faker\Factory::create('en_US');
        $ru = Faker\Factory::create('ru_RU');
        return new EconomicalActivityType([
            config('app.locale') => $en->word,
            'ru' => $ru->word
        ], $en->randomLetter);
    }


}