<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 21.06.17
 * Time: 14:05
 */
namespace App\Applications\Company\Transformers\Employee;

use League\Fractal\TransformerAbstract;
use App\Domains\Employee\Entities\Employee;

class EmployeeContactList extends TransformerAbstract
{
    public function transform(Employee $employee) : array
    {
        return [
            'id' => $employee->getId(),
            'name' => $employee->getProfile()->getName(),
            'firstName' => $employee->getProfile()->getFirstName(),
            'lastName' => $employee->getProfile()->getLastName(),
            'avatar' => $employee->getProfile()->getAvatar(),
            'position' => $employee->getProfile()->getPosition(),
            'companyId' => $employee->getCompany()->getId(),
            'companyName' => $employee->getCompany()->getProfile()->getName(),
            'companyLogo' => $employee->getCompany()->getProfile()->getPicture(),
        ];
    }
}
