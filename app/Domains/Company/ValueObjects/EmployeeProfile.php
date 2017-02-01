<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 5:49 PM
 */

namespace App\Domains\Company\ValueObjects;

use App\Domains\Company\Entities\Company;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class EmployeeProfile.
 *
 * @ODM\EmbeddedDocument
 */
class EmployeeProfile
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $position;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $firstName;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $lastName;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $login;

    /**
     * @var string
     */
    public $scope;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $extensionNumber;

    /**
     * EmployeeProfile constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $position
     */
    public function __construct(string $firstName, string $lastName, string $position)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->position = $position;
    }

    public function getName() : string
    {
        return ucfirst($this->firstName).' '.ucfirst($this->lastName);
    }

    public function getPosition() : string
    {
        return $this->position;
    }

    public function setLogin(Company $company, string $email)
    {
        $this->login = $company->getId().':'.$email;
    }
}
