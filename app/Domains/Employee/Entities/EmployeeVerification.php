<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 7:00 PM
 */

namespace App\Domains\Employee\Entities;

use App\Domains\Company\Entities\Company;
use App\Domains\Employee\ValueObjects\VerificationPin;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;
use DateTime;

/**
 * Class EmployeeVerification.
 *
 * @ODM\Document(
 *     collection="employeeVerifications",
 *     repositoryClass="App\Core\Repositories\EmployeeVerificationRepository"
 * )
 */
class EmployeeVerification implements MetaEmployeeInterface
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
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $emailVerifiedAt;

    /**
     * EmployeeVerification constructor.
     * @param $reason string|null
     */
    public function __construct($reason = null)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new DateTime();
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
     * @param bool $isVerified
     */
    public function setVerifyEmail(bool $isVerified)
    {
        $this->emailVerified = $isVerified;
        $this->emailVerifiedAt = $isVerified ? new DateTime() : null;
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

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getEmailVerifiedAt(): DateTime
    {
        return $this->emailVerifiedAt;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }



}
