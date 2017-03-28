<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 12:10 AM
 */

namespace App\Domains\Employee\EntityDecorators;


use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Entities\EmployeeVerification;

class InvitationVerification extends AbstractVerificationDecorator implements EmployeeVerificationDecoratorInterface
{

    /**
     * @var EmployeeVerification
     */
    private $verification;

    /**
     * InvitationVerification constructor.
     * @param EmployeeVerification $verification
     */
    public function __construct(EmployeeVerification $verification)
    {
        $this->verification = $verification;
    }

    /**
     * @param Employee $employee
     * @param string $email
     *
     * @return EmployeeVerification
     */
    public static function make(Employee $employee, string $email) : EmployeeVerification
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_INVITED_BY_EMPLOYEE);
        $verification->associateCompany($employee->getCompany());
        $verification->associateEmail($email);
        return $verification;
    }

    /**
     * @return EmployeeVerification
     */
    public function getVerification(): EmployeeVerification
    {
        return $this->verification;
    }

    public function completelyVerified(): bool
    {
        return $this->verification->isEmailVerified();
    }


}