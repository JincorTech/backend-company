<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/10/16
 * Time: 8:25 PM
 */

namespace App\Applications\Dictionary\Transformers\Registration;

use App\Core\Dictionary\Entities\Country;
use League\Fractal\TransformerAbstract;
use App;

class CountryTransformer extends TransformerAbstract
{
    /**
     * @SWG\Definition(
     *     definition="RegistrationCountryElement",
     *     @SWG\Property(
     *         property="id",
     *         type="string",
     *     ),
     *     @SWG\Property(
     *         property="alpha2",
     *         type="string"
     *     ),
     *     @SWG\Property(
     *         property="name",
     *         type="string"
     *     ),
     *     @SWG\Property(
     *         property="taxValidation",
     *         type="string"
     *     )
     * )
     *
     * @param \App\Core\Dictionary\Entities\Country $country
     * @return array
     */
    public function transform(Country $country)
    {
        return [
            'id' => $country->getId(),
            'alpha2' => $country->getAlpha2Code(),
            'name' => $country->getName(App::getLocale()),
            'locale' => App::getLocale(),
        ];
    }
}
