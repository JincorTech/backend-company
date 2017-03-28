<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/21/17
 * Time: 11:14 PM
 */

namespace App\Applications\Company\Transformers;


use App\Domains\Employee\Entities\Employee;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class EmployeeRegisterSuccess extends TransformerAbstract
{


    public function transform(Collection $data)
    {
        /** @var Employee $employee */
        $employee = $data['employee'];
        return [
            'id' => $employee->getId(),
            'profile' => [
                'name' => $employee->getProfile()->getName(),
            ],
            'contacts' => [
                'email' => $employee->getContacts()->getEmail(),
                'phone' => $employee->getContacts()->getPhone(),
            ],
            'company' => [
                'id' => $employee->getCompany()->getId(),
                'name' => $employee->getCompany()->getProfile()->getName(),
            ],
            'token' => $data['token']
        ];
    }

}