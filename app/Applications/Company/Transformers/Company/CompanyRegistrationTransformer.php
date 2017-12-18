<?php

namespace App\Applications\Company\Transformers\Company;

use App\Applications\Company\Services\Company\CompanyRegistrationResult;
use League\Fractal\TransformerAbstract;

class CompanyRegistrationTransformer extends TransformerAbstract
{
    public function transform(CompanyRegistrationResult $result)
    {
        return [
            'id' => $result->getCompany()->getId(),
            'token' => $result->getToken(),
        ];
    }
}
