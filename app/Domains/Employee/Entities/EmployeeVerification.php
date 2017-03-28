<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 7:00 PM
 */

namespace App\Domains\Employee\Entities;

use App\Domains\Company\Contracts\EmployeeVerificationActionContract;
use App\Domains\Company\Entities\Company;
use App\Domains\Employee\Exceptions\EmployeeVerificationException;
use App\Domains\Company\ValueObjects\VerificationProcess\Actions\RestorePasswordAction;
use App\Domains\Company\ValueObjects\VerificationProcess\EmployeeVerificationAction;
use App\Domains\Employee\ValueObjects\VerificationPin;
use App\Domains\Company\ValueObjects\VerificationProcess\MustReferCompany;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;

/**
 * Class EmployeeVerification.
 *
 * @ODM\Document(
 *     collection="employeeVerifications",
 *     repositoryClass="App\Domains\Employee\Repositories\EmployeeVerificationRepository"
 * )
 */
class EmployeeVerification
{


    const REASON_REGISTER = 'register';
    const REASON_RESTORE = 'restore';
    const REASON_INVITED_BY_EMPLOYEE = 'invited-by-employee';

    /**
     * @var string
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $email;

    /**
     * @var \App\Domains\Employee\ValueObjects\VerificationPin
     * @ODM\EmbedOne(targetDocument="App\Domains\Employee\ValueObjects\VerificationPin")
     */
    protected $emailPin;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $phone;

    /**
     * @var VerificationPin
     * @ODM\EmbedOne(targetDocument="App\Domains\Employee\ValueObjects\VerificationPin")
     */
    protected $phonePin;

    /**
     * @var Company
     * @ODM\ReferenceOne(targetDocument="App\Domains\Company\Entities\Company", cascade={"persist"})
     */
    protected $company;

    /**
     * @var bool
     * @ODM\Field(type="bool")
     */
    protected $phoneVerified;

    /**
     * @var bool
     * @ODM\Field(type="bool")
     */
    protected $emailVerified;

    /**
     * @var
     * @ODM\Field(type="string")
     */
    protected $reason;

    /**
     * EmployeeVerification constructor.
     * @param $reason string|null
     */
    public function __construct($reason = null)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->emailPin = new VerificationPin();
        $this->phonePin = new VerificationPin();
        $this->emailVerified = false;
        $this->phoneVerified = false;
        $this->reason = $reason;
    }

    /**
     * @param Company $company
     */
    public function associateCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @param string $email
     */
    public function associateEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $phone
     */
    public function associatePhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param string $pin
     */
    public function verifyEmail(string $pin)
    {
        if ($this->emailPin->getCode() === $pin) {
            $this->emailVerified = true;
        } else {
            $this->emailVerified = false;
        }
    }

    /**
     * @return string
     */
    public function getEmailCode()
    {
        return $this->emailPin->getCode();
    }


    /**
     * @return bool
     */
    public function completelyVerified()
    {
        return $this->isEmailVerified();
    }

    /**
     * @return bool
     */
    public function isEmailVerified() : bool
    {
        return $this->emailVerified;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

}
