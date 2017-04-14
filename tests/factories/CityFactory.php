<?php

use App\Core\Dictionary\Entities\City;
use GeoJson\Geometry\Point;
use Faker\Factory;
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 03/04/2017
 * Time: 14:37
 */
class CityFactory implements FactoryInterface
{

    /**
     * @return City
     */
    public static function make() : City
    {
        $ru = Factory::create('ru_RU');
        $en = Factory::create('en_US');
        $country = CountryFactory::make();
        $point = new Point([$ru->longitude, $ru->latitude]);
        $city = new City([
            'en' => $en->city,
            'ru' => $ru->city,
        ], $country, $point);
        return $city;
    }

}