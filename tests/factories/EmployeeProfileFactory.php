<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:49 AM
 */

use App\Domains\Employee\ValueObjects\EmployeeProfile;
use Faker\Factory;

class EmployeeProfileFactory implements FactoryInterface
{


    public static function make()
    {
        $ru = Factory::create('ru_RU');

        return new EmployeeProfile($ru->firstName(), $ru->lastName, $ru->text(10));
    }

}