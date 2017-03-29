<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 28/03/2017
 * Time: 13:57
 */

namespace App\Applications\Company\Transformers;


use App\Domains\Employee\Entities\Employee;
use League\Fractal\TransformerAbstract;

class EmployeeSelfTransformer extends TransformerAbstract
{


    public function transform(Employee $employee)
    {
        return [
            'id' => $employee->getId(),
            'profile' => [
                'name' => $employee->getProfile()->getName(),
                'avatar' => 'http://i.imgur.com/n613Ki4.jpg',
                'position' => $employee->getProfile()->getPosition(),
            ],
            'contacts' => [
                'email' => $employee->getContacts()->getEmail(),
                'phone' => $employee->getContacts()->getPhone(),
            ],
            'company' => [
                'id' => $employee->getCompany()->getId(),
                'name' => $employee->getCompany()->getProfile()->getName(),
            ]
        ];
    }

}