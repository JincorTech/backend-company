<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 11:07
 */

namespace App\Applications\Dictionary\Transformers;


use App\Core\Dictionary\Entities\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract
{

    public function transform(City $city)
    {
        return [
            'id' => $city->getId(),
            'name' => $city->getName()
        ];
    }

}