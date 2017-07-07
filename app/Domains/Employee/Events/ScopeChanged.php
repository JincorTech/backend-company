<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 19/04/2017
 * Time: 18:33
 */

namespace App\Domains\Employee\Events;


use App\Domains\Employee\Events\Traits\GetEmployeeData;
use App\Domains\Employee\Entities\Employee;

class ScopeChanged
{
    use GetEmployeeData;

    protected $oldValue;

    public function __construct(Employee $employee, string $old)
    {
        $this->employee = $employee;
        $this->oldValue = $old;
    }

}
