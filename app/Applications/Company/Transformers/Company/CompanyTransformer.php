<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 3:35 AM
 */

namespace App\Applications\Company\Transformers\Company;

use App\Applications\Company\Transformers\Dictionary\CountryTransformer;
use App\Core\ValueObjects\TranslatableString;
use App\Core\Dictionary\Entities\Country;
use App\Domains\Company\Entities\Company;
use League\Fractal\TransformerAbstract;
use App;

class CompanyTransformer extends MyCompany
{
    /**
     * @param TranslatableString $brandName
     * @return array
     */
    protected function transformBrandName($brandName)
    {
        if ($brandName instanceof TranslatableString) {
            return $brandName->getValue();
        }
        return null;
    }
}
