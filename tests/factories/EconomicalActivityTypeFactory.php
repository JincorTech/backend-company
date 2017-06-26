<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 9:59 PM
 */

use App\Domains\Company\Entities\EconomicalActivityType;
use Doctrine\ODM\MongoDB\DocumentManager;

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

    public static function makeFromDb()
    {
        $id = '14585013-89c9-4dba-82e2-71a0efe196e9';
        /**
         * @var $type EconomicalActivityType
         */
        $type = App::make(DocumentManager::class)->getRepository(EconomicalActivityType::class)->find($id);
        return $type;
    }

}
