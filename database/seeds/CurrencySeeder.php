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
        $file = fopen('database/datasets/Currencies.csv', 'r');

        while (($line = fgetcsv($file)) !== false) {
            $names = [
                'ru' => $line[0],
                'en' => $line[1],
            ];
            $numericId = (int)$line[2];
            $alpha3 = $line[3];
            $unicodeSign = $line[4];
            $iso = new CurrencyISOCodes($alpha3, $numericId);
            $currency = new Currency($names, $iso, $unicodeSign);
            $dm->persist($currency);
        }

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
