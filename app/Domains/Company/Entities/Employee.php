<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 5:09 PM
 */

namespace App\Domains\Company\Entities;

use App\Domains\Company\ValueObjects\EmployeeContact;
use App\Domains\Company\ValueObjects\EmployeeProfile;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;
use Hash;

/**
 * Class Employee.
 *
 * @ODM\Document(
 *     collection="employees",
 *     repositoryClass="App\Domains\Company\Repositories\EmployeeRepository"
 * )
 */
class Employee
{
    /**
     * @var string
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var Department
     *
     * @ODM\ReferenceOne(targetDocument="App\Domains\Company\Entities\Department", inversedBy="employees")
     */
    protected $department;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $password;

    /**
     * @var EmployeeProfile
     * @ODM\EmbedOne(targetDocument="App\Domains\Company\ValueObjects\EmployeeProfile")
     */
    protected $profile;

    /**
     * @var EmployeeContact
     * @ODM\EmbedOne(targetDocument="App\Domains\Company\ValueObjects\EmployeeContact")
     */
    protected $contacts;

    /**
     * @var bool
     * @ODM\Field(type="bool")
     */
    protected $isActive;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public static function register(EmployeeVerification $verification, EmployeeProfile $profile, string $password)
    {
        $employee = new self();
        $employee->contacts = new EmployeeContact($verification);
        $employee->profile = $profile;
        $employee->password = Hash::make($password);
        $employee->isActive = true;

        return $employee;
    }

    public function attachToDepartment(Department $department)
    {
        $this->department = $department;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProfile() : EmployeeProfile
    {
        return $this->profile;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLogin(): string
    {
        return $this->getCompany()->getId().':'.$this->getContacts()->getEmail();
    }

    /**
     * @return EmployeeContact
     */
    public function getContacts(): EmployeeContact
    {
        return $this->contacts;
    }

    /**
     * @return Company
     */
    public function getCompany() : Company
    {
        return $this->department->getCompany();
    }

    public function checkPassword(string $password) : bool
    {
        return Hash::check($password, $this->password);
    }
}
