<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 9:12 PM
 */

namespace App\Applications\Company\Transformers;

use App\Domains\Employee\Entities\Employee;
use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
{
    public function transform(Employee $employee)
    {
        return [
            'id' => $employee->getId(),
            'profile' => [
                'name' => $employee->getProfile()->getName(),
                'firstName' => $employee->getProfile()->getFirstName(),
                'lastName' => $employee->getProfile()->getLastName(),
            ],
            'contacts' => [
                'email' => $employee->getContacts()->getEmail(),
                'phone' => $employee->getContacts()->getPhone(),
            ],
            'company' => [
                'id' => $employee->getCompany()->getId(),
                'name' => $employee->getCompany()->getProfile()->getName(),
            ],
        ];
    }
}
