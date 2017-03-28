<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 5:51 PM
 */

namespace App\Domains\Employee\ValueObjects;

use App\Domains\Employee\Entities\EmployeeVerification;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class EmployeeContact.
 *
 * @ODM\EmbeddedDocument
 */
class EmployeeContact
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $email;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $phone;

    public function __construct(EmployeeVerification $verification)
    {
        $this->email = $verification->getEmail();
        $this->phone = $verification->getPhone();
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
    public function getEmail()
    {
        return $this->email;
    }
}
