<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 7:23 PM
 */

namespace App\Domains\Company\Events;

use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\Employee;

class EmployeeRegisteredEvent
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

    public function __construct(Company $company, Employee $employee, string $scope)
    {
        $this->companyId = $company->getId();
        $this->companyName = $company->getLegalName();
        $this->email = $employee->getContacts()->getEmail();
        $this->password = $employee->getPassword();
        $this->scope = $scope;
        $this->name = $employee->getProfile()->getName();
        $this->position = $employee->getProfile()->getPosition();
    }

    public function getData(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'company' => $this->companyId,
            'companyName' => $this->companyName,
            'name' => $this->name,
            'position' => $this->position,
            'scope' => $this->scope,
        ];
    }
}
