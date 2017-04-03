<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/23/17
 * Time: 7:10 PM
 */

namespace App\Applications\Company\Transformers\Company;

use App\Domains\Company\Entities\CompanyType as Entity;
use League\Fractal\TransformerAbstract;

class CompanyType extends TransformerAbstract
{


    public function transform(Entity $companyType) : array
    {
        return [
            'id' => $companyType->getId(),
            'name' => $companyType->getName(),
            'code' => $companyType->getCode(),
        ];
    }
}
