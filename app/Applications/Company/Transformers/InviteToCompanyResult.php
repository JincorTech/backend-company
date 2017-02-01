<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 11:32 AM
 */

namespace App\Applications\Company\Transformers;


use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Employee\Services\EmployeeService;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class InviteToCompanyResult extends TransformerAbstract
{


    public function transform(Collection $results)
    {
        $response = [];
        /** @var EmployeeVerification $result */
        foreach ($results->get('results') as $result) {
            $response[] = [
                'status' => EmployeeService::VERIFICATION_OK,
                'verification' => [
                    'verificationId' => $result->getId(),
                    'companyId' => $result->getCompany()->getId(),
                    'email' => [
                        'value' => $result->getEmail(),
                        'isVerified' => $result->isEmailVerified(),
                    ]
                ]
            ];
        }
        foreach ($results->get('errors') as $error) {
            $response[] = $error;
        }
        return $response;
    }

}