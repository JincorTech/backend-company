<?php
namespace App\Domains\Employee\Events\Traits;
use App\Domains\Employee\Entities\Employee;

/**
 * Created by PhpStorm.
 * User: artem
 * Date: 07.07.17
 * Time: 14:43
 *
 */
trait GetEmployeeData
{
    /**
     * @var Employee
     */
    private $employee;

    public function getData()
    {
        return [
            'employeeId' => $this->employee->getId(),
            'email' => $this->employee->getContacts()->getEmail(),
            'password' => $this->employee->getPassword(),
            'login' => $this->employee->getLogin(),
            'companyName' => $this->employee->getCompany()->getProfile()->getName(),
            'name' => $this->employee->getProfile()->getName(),
            'position' => $this->employee->getProfile()->getPosition(),
            'scope' => $this->employee->getProfile()->scope,
            'sub' => $this->employee->getMatrixId(),
        ];
    }
}
