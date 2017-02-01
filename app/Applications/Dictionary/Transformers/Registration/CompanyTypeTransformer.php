<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/7/16
 * Time: 3:55 AM
 */

namespace App\Applications\Dictionary\Transformers\Registration;

use App\Domains\Company\Entities\CompanyType;
use League\Fractal\TransformerAbstract;
use App;

class CompanyTypeTransformer extends TransformerAbstract
{
    /**
     * @SWG\Definition(
     *     definition="RegistrationCompanyType",
     *     @SWG\Property(
     *         property="id",
     *         type="string",
     *     ),
     *     @SWG\Property(
     *         property="name",
     *         type="string"
     *     ),
     *     @SWG\Property(
     *         property="locale",
     *         type="string"
     *     ),
     * )
     *
     * @param CompanyType $ct
     * @return array
     */
    public function transform(CompanyType $ct)
    {
        return [
            'id' => $ct->getId(),
            'name' => $ct->getName(App::getLocale()),
            'locale' => App::getLocale(),
        ];
    }
}
