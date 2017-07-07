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
use App\Domains\Employee\Events\Traits\GetEmployeeData;

class PasswordChanged
{
    use GetEmployeeData;

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
}
