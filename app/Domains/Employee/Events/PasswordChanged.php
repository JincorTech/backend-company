<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 2:38 PM
 */

namespace App\Domains\Employee\Events;


use App\Domains\Employee\Entities\Employee;

class PasswordChanged
{

    /**
     * @var Employee
     */
    private $employee;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $old;


    public function __construct(Employee $employee, $old)
    {
        $this->employee = $employee;
        $this->old = $old;
        $this->password = $employee->getPassword();
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
            'password' => $this->password,
            'company' => $this->employee->getCompany()->getId(),
            'companyName' => $this->employee->getCompany()->getProfile()->getName(),
            'name' => $this->employee->getProfile()->getName(),
            'position' => $this->employee->getProfile()->getPosition(),
            'scope' => $this->employee->getProfile()->scope,
        ];
    }

}