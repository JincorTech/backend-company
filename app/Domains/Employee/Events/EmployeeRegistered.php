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
use App\Domains\Employee\Events\Traits\GetEmployeeData;

class EmployeeRegistered
{
    use GetEmployeeData;
    /**
     * EmployeeRegistered constructor.
     *
     * @param Company $company
     * @param Employee $employee
     * @param string $scope
     */
    public function __construct(Company $company, Employee $employee, string $scope)
    {
        $this->employee = $employee;
    }
}
