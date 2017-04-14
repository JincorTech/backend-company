<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 01/04/2017
 * Time: 21:05
 */

namespace App\Core\Dictionary\Repositories;


use App\Core\Dictionary\Entities\Country;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentRepository;
use GeoJson\Geometry\Point;

/**
 * Class CityRepository
 * @package App\Core\Dictionary\Repositories
 */
class CityRepository extends DocumentRepository
{

    public function findInCountry(Country $country) : ArrayCollection
    {
        return new ArrayCollection($this->createQueryBuilder()
            ->field('country')->references($country)
            ->getQuery()->execute()->toArray());
    }


}