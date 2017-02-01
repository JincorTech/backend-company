<?php

/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/24/16
 * Time: 8:25 PM
 */
use Illuminate\Database\Seeder;
use App\Core\ValueObjects\CurrencyISOCodes;
use App\Core\Dictionary\Entities\Currency;
use Doctrine\ODM\MongoDB\DocumentManager;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $dm = $this->getDm();
        $rubleISO = new CurrencyISOCodes('RUB', 643);
        $rubleNames = [
            'en' => 'Russian ruble',
            'ru' => 'Российский рубль',
        ];
        $ruble = new Currency($rubleNames, $rubleISO, '₽');
        $dollarISO = new CurrencyISOCodes('USD', 840);
        $dollarNames = [
            'en' => 'US dollar',
            'ru' => 'Доллар США',
        ];
        $dollar = new Currency($dollarNames, $dollarISO, '$');
        $dm->persist($ruble);
        $dm->persist($dollar);
        $dm->flush();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm()
    {
        return $this->container->make(DocumentManager::class);
    }
}
