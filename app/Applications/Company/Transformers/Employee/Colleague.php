<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 07:46
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use League\Fractal\TransformerAbstract;

class Colleague extends SelfProfile
{

    public function transform(Employee $employee)
    {
        return [
            'id' => $employee->getId(),
            'profile' => $this->getProfile($employee),
            'contacts' => $this->getContacts($employee),
            'meta' => $this->getMeta($employee)
        ];
    }

    public function getMeta(Employee $employee)
    {
        return [
            'status' => $employee->isActive() ? 'active' : 'deleted',
            'registered_at' => $employee->getRegisteredAt()->format(\DateTime::ISO8601)
        ];
    }

}