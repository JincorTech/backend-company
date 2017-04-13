<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 3:35 AM
 */

namespace App\Applications\Company\Transformers\Company;

use App\Domains\Company\Entities\Company;
use League\Fractal\TransformerAbstract;
use App\Core\Dictionary\Entities\Country;
use App\Applications\Company\Transformers\Dictionary\CountryTransformer;
use App;

class CompanyTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        return [
            'id' => $company->getId(),
            'legalName' => $company->getProfile()->getName(),
            'country' => $this->transformCountry($company->getProfile()->getAddress()->getCountry()),
            'formattedAddress' => $company->getProfile()->getAddress()->getFormattedAddress(),
            'type' => $company->getProfile()->getType()->getName(App::getLocale()),
            'picture' => 'http://www.thewrap.com/wp-content/uploads/2013/11/RandomMedia.png',
        ];
    }

    private function transformCountry(Country $country)
    {
        $transformer = new CountryTransformer();
        return $transformer->transform($country);
    }
}
