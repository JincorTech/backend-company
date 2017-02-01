<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 7:00 PM
 */

namespace App\Domains\Company\Entities;

use App\Domains\Company\ValueObjects\VerificationPin;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;

/**
 * Class EmployeeVerification.
 *
 * @ODM\Document(
 *     collection="employeeVerifications"
 * )
 */
class EmployeeVerification
{
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
     * @var VerificationPin
     * @ODM\EmbedOne(targetDocument="App\Domains\Company\ValueObjects\VerificationPin")
     */
    protected $emailPin;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $phone;

    /**
     * @var VerificationPin
     * @ODM\EmbedOne(targetDocument="App\Domains\Company\ValueObjects\VerificationPin")
     */
    protected $phonePin;

    /**
     * @var Company
     * @ODM\ReferenceOne(targetDocument="App\Domains\Company\Entities\Company")
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

    public function __construct(Company $company)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->emailPin = new VerificationPin();
        $this->phonePin = new VerificationPin();
        $this->emailVerified = false;
        $this->phoneVerified = false;
        $this->company = $company;
    }

    public function associateEmail(string $email)
    {
        $this->email = $email;
    }

    public function associatePhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function verifyEmail(string $pin)
    {
        if ($this->emailPin->getCode() === $pin) {
            $this->emailVerified = true;
        } else {
            $this->emailVerified = false;
        }
    }

    public function getEmailCode()
    {
        return $this->emailPin->getCode();
    }

    public function verifyPhone(string $pin)
    {
        if ($this->phonePin->getCode() === $pin) {
            $this->phoneVerified = true;
        } else {
            $this->phoneVerified = false;
        }
    }

    public function completelyVerified()
    {
        return $this->emailVerified;// && $this->phoneVerified;
    }

    public function isEmailVerified() : bool
    {
        return $this->emailVerified;
    }

    public function isPhoneVerified() : bool
    {
        return $this->phoneVerified;
    }

    public function getEmail()
    {
        return $this->email;
    }

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
     * @return Company
     */
    public function getCompany() : Company
    {
        return $this->company;
    }
}
