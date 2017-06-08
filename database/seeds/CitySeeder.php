<?php


use App\Core\Dictionary\Entities\Country;
use App\Core\Dictionary\Entities\City;
use GeoJson\Geometry\Point;
use Faker\Factory;

/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 03/04/2017
 * Time: 15:58
 */
class CitySeeder extends DatabaseSeeder
{

    public function run()
    {
        $en = Factory::create('en_US');
        $ru = Factory::create('ru_RU');
        $countries = $this->getDm()->getRepository(Country::class)->findAll();
        /** @var Country $country */
        foreach ($countries as $country) {
            $cities = [];
            while (count($cities) < 5) {
                $city = new City([
                    'en' => $en->city,
                    'ru' => $ru->city,
                ], $country);
                $this->getDm()->persist($city);
                $cities[] = $city;
            }
        }
        $this->getDm()->flush();
    }

}