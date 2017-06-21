<?php

/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/25/16
 * Time: 4:44 PM
 */
use Illuminate\Database\Seeder;
use App\Core\ValueObjects\CountryISOCodes;
use App\Core\Dictionary\Entities\Currency;
use App\Core\Dictionary\Entities\Country;
use Doctrine\ODM\MongoDB\DocumentManager;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $file = fopen('database/datasets/Countries.csv', 'r');
        while (($line = fgetcsv($file)) !== false) {
            $names = [
                'ru' => $line[0],
                'en' => $line[1],
            ];
            $numericId = (int)$line[2];
            $alpha2 = $line[3];
            $alpha3 = $line[4];
            $callingCode = $line[5];
            $currencyCode = $line[6];
            $iso = new CountryISOCodes('ISO 3166-2:' . $alpha2, $numericId, $alpha2, $alpha3);

            /** @var Currency $currency */
            $currency = $this->getCurrencyRepository()->findOneBy([
                'ISOCodes.alpha3Code' => $currencyCode,
            ]);

            $country = new Country($names, $callingCode, $iso, $currency);
            $this->getDm()->persist($country);
        }

        $this->getDm()->flush();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm()
    {
        return $this->container->make(DocumentManager::class);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private function getCurrencyRepository()
    {
        return $this->getDm()->getRepository(Currency::class);
    }

}
