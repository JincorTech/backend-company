<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 4:05 PM
 */

namespace App\Applications\Company\Transformers;

use App\Domains\Company\Entities\EmployeeVerification;
use League\Fractal\TransformerAbstract;

class EmployeeVerificationTransformer extends TransformerAbstract
{
    public function transform(EmployeeVerification $verification)
    {
        return [
            'id' => $verification->getId(),
            'companyId' => $verification->getCompany()->getId(),

            'email' => [
                'value' => $verification->getEmail(),
                'isVerified' => $verification->isEmailVerified(),
            ],
            'phone' => [
                'value' => $verification->getPhone(),
                'isVerified' => $verification->isPhoneVerified(),
            ],
        ];
    }
}
