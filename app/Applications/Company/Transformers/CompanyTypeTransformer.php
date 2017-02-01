<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/23/17
 * Time: 7:10 PM
 */

namespace App\Applications\Company\Transformers;

use App\Domains\Company\Entities\CompanyType;
use League\Fractal\TransformerAbstract;

class CompanyTypeTransformer extends TransformerAbstract
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function transform(CompanyType $companyType) : array
    {
        return [
            'id' => $companyType->getId(),
            'name' => $companyType->getName($this->locale),
            'code' => $companyType->getCode(),
        ];
    }
}
