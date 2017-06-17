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
        $cities = json_decode(file_get_contents('database/datasets/Cities.json'), true);
        foreach ($cities as $city) {
            $names = [
                'en' => $city['names']['values']['en'],
                'ru' => $city['names']['values']['ru'],
            ];

            $countryIso = $city['country'];

            /** @var Country $country */
            $country = $this->getCountryRepository()->findOneBy([
                'ISOCodes.alpha3Code' => $countryIso,
            ]);

            $cityToStore = new City($names, $country);
            $this->getDm()->persist($cityToStore);
        }

        $this->getDm()->flush();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function getCountryRepository()
    {
        return $this->getDm()->getRepository(Country::class);
    }
}