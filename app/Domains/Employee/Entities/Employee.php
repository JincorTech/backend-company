<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 5:09 PM
 */

namespace App\Domains\Employee\Entities;

use App\Domains\Employee\Events\EmployeeRegistered;
use App\Domains\Employee\ValueObjects\EmployeeRole;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\Department;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Employee\EntityDecorators\RegistrationVerification;
use App\Domains\Employee\Events\PasswordChanged;
use App\Domains\Employee\Exceptions\EmployeeVerificationException;
use App\Domains\Employee\ValueObjects\EmployeeContact;
use App\Domains\Employee\ValueObjects\EmployeeProfile;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;
use Hash;

/**
 * Class Employee.
 *
 * @ODM\Document(
 *     collection="employees",
 *     repositoryClass="App\Domains\Employee\Repositories\EmployeeRepository"
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
     * @ODM\ReferenceOne(targetDocument="App\Domains\Company\Entities\Department", inversedBy="employees", cascade={"persist"})
     */
    protected $department;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $password;

    /**
     * @var EmployeeProfile
     * @ODM\EmbedOne(targetDocument="App\Domains\Employee\ValueObjects\EmployeeProfile")
     */
    protected $profile;

    /**
     * @var \App\Domains\Employee\ValueObjects\EmployeeContact
     * @ODM\EmbedOne(targetDocument="App\Domains\Employee\ValueObjects\EmployeeContact")
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
        $registrationVerification = new RegistrationVerification($verification);
        if (!$verification->getCompany()) {
            throw new EmployeeVerificationException("You cannot register employee without the company"); //TODO: message translation
        }
        if (!$registrationVerification->completelyVerified()) {
            throw new EmployeeVerificationException("In order to register you should verify all required contacts first");
        }
        $employee->contacts = new EmployeeContact($verification);
        $employee->profile = $profile;
        $employee->profile->setLogin($verification->getCompany(), $verification->getEmail());
        $employee->password = Hash::make($password);
        $employee->isActive = true;
        $employee->setScope($verification->getCompany());

        $employee->department = $verification->getCompany()->getRootDepartment();
        $verification->getCompany()->getRootDepartment()->addEmployee($employee);

        event(new EmployeeRegistered($employee->getCompany(), $employee, $employee->getProfile()->scope));

        return $employee;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return EmployeeProfile
     */
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

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->getCompany()->getId().':'.$this->getContacts()->getEmail();
    }

    /**
     * @return \App\Domains\Employee\ValueObjects\EmployeeContact
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

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password) : bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getProfile()->scope === EmployeeRole::ADMIN;
    }

    /**
     * @param string $password
     */
    public function changePassword(string $password)
    {
        $oldPass = $this->password;
        $this->password = Hash::make($password);
        event(new PasswordChanged($this, $oldPass));
    }

    /**
     * Set the scope based on company Instance
     *
     * @param Company $company
     */
    private function setScope(Company $company)
    {
        if ($company->getEmployees()->count() === 0) {
            $this->profile->scope = EmployeeRole::ADMIN;
        } else {
            $this->profile->scope = EmployeeRole::EMPLOYEE;
        }
    }

}
