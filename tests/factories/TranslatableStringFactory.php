<?php


use App\Core\ValueObjects\TranslatableString;
use Faker\Factory;

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 10:37 PM
 */
class TranslatableStringFactory implements FactoryInterface
{


    public static function make()
    {
        $defaultLocale = config('app.locale');
        $defaultFaker = Factory::create($defaultLocale);
        $frenchFaker = Factory::create('fr_FR');
        $ruFaker = Factory::create('ru_RU');

        return new TranslatableString([
            $defaultLocale => $defaultFaker->sentence,
            'ru' => $ruFaker->sentence,
            'fr' => $frenchFaker->sentence
        ]);
    }


}