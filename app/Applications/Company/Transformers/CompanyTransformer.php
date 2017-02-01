<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 3:35 AM
 */

namespace App\Applications\Company\Transformers;

use App\Domains\Company\Entities\Company;
use League\Fractal\TransformerAbstract;
use App;

class CompanyTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        return [
            'id' => $company->getId(),
            'legalName' => $company->getProfile()->getName(),
            'country' => $company->getProfile()->getAddress()->getFormattedAddress(),
            'formattedAddress' => $company->getProfile()->getAddress()->getFormattedAddress(),
            'type' => $company->getProfile()->getType()->getName(App::getLocale()),
        ];
    }
}
