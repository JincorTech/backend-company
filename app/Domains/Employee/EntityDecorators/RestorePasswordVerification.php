<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 12:11 AM
 */

namespace App\Domains\Employee\EntityDecorators;


use App\Domains\Employee\Entities\EmployeeVerification;

/**
 * Class RestorePasswordVerification
 * @package App\Domains\Employee\EntityDecorators
 *
 * @method RestorePasswordVerification::getEmail string
 */
class RestorePasswordVerification extends AbstractVerificationDecorator implements EmployeeVerificationDecoratorInterface
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
     * @param string $email
     *
     * @return RestorePasswordVerification
     */
    public static function make(string $email) : RestorePasswordVerification
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_RESTORE);
        $verification->associateEmail($email);
        return new self($verification);
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