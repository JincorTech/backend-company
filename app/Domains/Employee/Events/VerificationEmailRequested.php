<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 2:27 AM
 */

namespace App\Domains\Employee\Events;


use App\Domains\Employee\Entities\EmployeeVerification;

class VerificationEmailRequested
{

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $code;

    /**
     * VerificationEmailRequested constructor.
     * @param EmployeeVerification $verification
     */
    public function __construct(EmployeeVerification $verification)
    {
        $this->email = $verification->getEmail();
        $this->code = $verification->getEmailCode();
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }
}