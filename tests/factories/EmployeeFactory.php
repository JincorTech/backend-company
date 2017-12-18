<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 1:05 AM
 */

use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\ValueObjects\EmployeeContact;
use Doctrine\ODM\MongoDB\DocumentManager;

class EmployeeFactory implements FactoryInterface
{
    public static function make()
    {
        $password = 'test123';
        $profile = EmployeeProfileFactory::make();
        $company = CompanyFactory::make();
        $employeeContact = new EmployeeContact('test@test.com', '+7-900-888-88-88');
        $employee = Employee::register($company, $profile, $password, $employeeContact);
        return $employee;
    }

    public static function makeFromDb() : Employee
    {
        $id = '3e696895-ab1b-44ec-8646-86067877e38c';
        $employee = App::make(DocumentManager::class)->getRepository(Employee::class)->find($id);
        return $employee;
    }
}
