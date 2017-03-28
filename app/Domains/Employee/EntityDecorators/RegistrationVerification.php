<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 12:09 AM
 */

namespace App\Domains\Employee\EntityDecorators;


use App\Domains\Company\Entities\Company;
use App\Domains\Employee\Entities\EmployeeVerification;

class RegistrationVerification extends AbstractVerificationDecorator implements EmployeeVerificationDecoratorInterface
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
     * @param Company $company
     *
     * @return EmployeeVerification
     */
    public static function make(Company $company) : EmployeeVerification
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_REGISTER);
        $verification->associateCompany($company);
        return $verification;
    }

    /**
     * @return EmployeeVerification
     */
    public function getVerification(): EmployeeVerification
    {
        return $this->verification;
    }

    /**
     * @return bool
     */
    public function completelyVerified(): bool
    {
        return $this->verification->isEmailVerified();
    }

}