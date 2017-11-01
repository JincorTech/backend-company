<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 1:05 AM
 */

use App\Domains\Employee\Entities\Employee;
use Doctrine\ODM\MongoDB\DocumentManager;

class EmployeeFactory implements FactoryInterface
{


    public static function make()
    {
        $password = 'test123';
        $profile = EmployeeProfileFactory::make();
        $verification = EmployeeVerificationFactory::make();
        $verification->setVerifyEmail(true);
        $employee = Employee::register($verification, $profile, $verification->getEmail(), $password);
        return $employee;
    }

    public static function makeFromDb() : Employee
    {
        $id = '3e696895-ab1b-44ec-8646-86067877e38c';
        $employee = App::make(DocumentManager::class)->getRepository(Employee::class)->find($id);
        return $employee;
    }

}
