<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 7:23 PM
 */

namespace App\Domains\Employee\Events;

use App\Domains\Company\Entities\Company;
use App\Domains\Employee\Entities\Employee;

class EmployeeRegistered
{
    /**
     * UUID4 of the company employee belongs to.
     *
     * @var string
     */
    protected $companyId;

    protected $companyName;

    protected $email;

    protected $password;

    protected $scope;

    protected $name;

    protected $position;

    protected $employeeId;

    protected $sub;

    /**
     * EmployeeRegistered constructor.
     *
     * @param Company $company
     * @param Employee $employee
     * @param string $scope
     */
    public function __construct(Company $company, Employee $employee, string $scope)
    {
        $this->companyId = $company->getId();
        $this->companyName = $company->getProfile()->getName();
        $this->email = $employee->getContacts()->getEmail();
        $this->password = $employee->getPassword();
        $this->scope = $scope;
        $this->name = $employee->getProfile()->getName();
        $this->position = $employee->getProfile()->getPosition();
        $this->employeeId = $employee->getId();
        $this->sub = $employee->getSubject();
    }

    /**
     * Get an event data
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            'employeeId' => $this->employeeId,
            'email' => $this->email,
            'password' => $this->password,
            'company' => $this->companyId,
            'companyName' => $this->companyName,
            'name' => $this->name,
            'position' => $this->position,
            'scope' => $this->scope,
            'sub' => $this->sub,
        ];
    }
}
