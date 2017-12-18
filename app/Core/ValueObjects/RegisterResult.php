<?php

namespace App\Core\ValueObjects;

use App\Domains\Employee\Entities\Employee;

class RegisterResult
{

    /**
     * @var Employee
     */
    private $employee;

    /**
     * @var string
     */
    private $verificationId;

    /**
     * RegisterResult constructor.
     * @param Employee $employee
     * @param string $verificationId
     */
    public function __construct($employee, $verificationId)
    {
        $this->employee = $employee;
        $this->verificationId = $verificationId;
    }

    /**
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    /**
     * @return string
     */
    public function getVerificationId(): string
    {
        return $this->verificationId;
    }

}
