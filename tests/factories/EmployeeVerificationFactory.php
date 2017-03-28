<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:42 AM
 */

use Faker\Factory;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Company\Contracts\EmployeeVerificationActionContract;
use App\Domains\Company\ValueObjects\VerificationProcess\Actions\RegisterAction;

class EmployeeVerificationFactory implements FactoryInterface
{


    public static function make()
    {
        $ru = Factory::create('ru_RU');
        $employeeVerification = new EmployeeVerification();
        $employeeVerification->associateEmail($ru->email);
        $employeeVerification->associateCompany(CompanyFactory::make());
        $employeeVerification->associatePhone($ru->phoneNumber);
        return $employeeVerification;
    }

}