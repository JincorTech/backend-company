<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 07:46
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use DateTime;

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

    public function getMeta(Employee $employee) : array
    {
        $meta =  [
            'status' => $employee->isActive() ? 'active' : 'deleted',
            'registeredAt' => $employee->getRegisteredAt()->format(DateTime::ISO8601)
        ];
        if (!$employee->isActive()) {
            $meta['deletedAt'] = $employee->getDeletedAt()->format(DateTime::ISO8601);
        }
        return $meta;
    }

}