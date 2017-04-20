<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 19/04/2017
 * Time: 18:33
 */

namespace App\Domains\Employee\Events;


use App\Domains\Employee\Entities\Employee;

class ScopeChanged
{

    protected $oldValue;

    public $employee;

    public function __construct(Employee $employee, string $old)
    {
        $this->employee = $employee;
        $this->oldValue = $old;
    }

    /**
     * Get an event data
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            'email' => $this->employee->getContacts()->getEmail(),
            'password' => $this->employee->getPassword(),
            'company' => $this->employee->getCompany()->getId(),
            'companyName' => $this->employee->getCompany()->getProfile()->getName(),
            'name' => $this->employee->getProfile()->getName(),
            'position' => $this->employee->getProfile()->getPosition(),
            'scope' => $this->employee->getProfile()->scope,
        ];
    }

}