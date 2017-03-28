<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 1:05 AM
 */

use App\Domains\Employee\Entities\Employee;

class EmployeeFactory implements FactoryInterface
{


    public static function make()
    {
        $password = 'test123';
        $profile = EmployeeProfileFactory::make();
        $verification = EmployeeVerificationFactory::make();
        $verification->verifyEmail($verification->getEmailCode());
        $employee = Employee::register($verification, $profile, $password);
        return $employee;
    }

}