<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/04/2017
 * Time: 14:57
 */

namespace App\Applications\Company\Transformers\Dictionary;


use App\Core\Dictionary\Entities\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
{

    /**
     * Transform country to represent in company domain
     *
     * @param Country $country
     * @return array
     */
    public function transform(Country $country)
    {
        return [
            'id' => $country->getId(),
            'name' => $country->getName(),
        ];
    }

}