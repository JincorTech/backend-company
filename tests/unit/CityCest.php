<?php

use App\Core\Dictionary\Entities\City;
use App\Core\ValueObjects\TranslatableString;
use App\Core\ValueObjects\Coordinates;
use GeoJson\Geometry\Point;

class CityCest
{

    public function testCityEntity(UnitTester $I)
    {
        $country = CountryFactory::make();
        $point = new Point([56.2928515, 43.7866642]);
        $city = new City([
            'en' => 'Nizhniy Novgorod',
            'ru' => 'Нижний Новгород',
        ], $country, $point);
        $coordinates = new Coordinates($point);
        $names = new TranslatableString([
            'en' => 'Nizhniy Novgorod',
            'ru' => 'Нижний Новгород',
        ]);
        $I->assertInstanceOf(City::class, $city);
        $I->assertEquals($country, $city->getCountry());
        $I->assertEquals($names->getValues(), $city->getNames());
        $I->assertNotEquals(\Ramsey\Uuid\Uuid::uuid4()->toString(), $city->getId());

    }



}
