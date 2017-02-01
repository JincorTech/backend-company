<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/1/17
 * Time: 5:45 PM
 */

namespace App\Core\ValueObjects;

use App\Domains\Company\Entities\Employee;

class EmployeeRole
{
    const ADMIN = 'company-admin';
    const EMPLOYEE = 'employee';

    public static function isAdmin(Employee $employee)
    {
        return $employee->getProfile()->scope === static::ADMIN;
    }

    public static function isEmployee(Employee $employee)
    {
        return $employee->getProfile()->scope === static::EMPLOYEE;
    }
}
