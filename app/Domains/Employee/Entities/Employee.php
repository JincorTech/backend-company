<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 5:09 PM
 */

namespace App\Domains\Employee\Entities;

use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\Department;
use App\Domains\Employee\EntityDecorators\RegistrationVerification;
use App\Domains\Employee\Events\EmployeeActivated;
use App\Domains\Employee\Events\EmployeeDeactivated;
use App\Domains\Employee\Events\EmployeeRegistered;
use App\Domains\Employee\Events\PasswordChanged;
use App\Domains\Employee\Events\ScopeChanged;
use App\Domains\Employee\Exceptions\CompanyRequired;
use App\Domains\Employee\Exceptions\ContactAlreadyAdded;
use App\Domains\Employee\Exceptions\ContactNotFound;
use App\Domains\Employee\ValueObjects\EmployeeContact;
use App\Domains\Employee\ValueObjects\EmployeeContactListItem;
use App\Domains\Employee\ValueObjects\EmployeeProfile;
use App\Domains\Employee\ValueObjects\EmployeeRole;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Hash;
use Ramsey\Uuid\Uuid;

/**
 * Class Employee.
 *
 * @ODM\Document(
 *     collection="employees",
 *     repositoryClass="App\Core\Repositories\EmployeeRepository"
 * )
 */
class Employee implements MetaEmployeeInterface
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
    protected $matrixId;

    /**
     * @var string
     * @ODM\Field(type="bin_uuid")
     */
    protected $departmentId;

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
     * @var ArrayCollection | PersistentCollection
     * @ODM\EmbedMany(targetDocument="App\Domains\Employee\ValueObjects\EmployeeContactListItem")
     */
    protected $contactList;

    /**
     * @var array
     * @ODM\Field(type="collection")
     */
    protected $wallets;

    /**
     * @var bool
     * @ODM\Field(type="bool")
     */
    protected $isActive;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $registeredAt;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $deletedAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->contactList = new ArrayCollection();
        $this->wallets = [];
    }

    public static function register(
        Company $company,
        EmployeeProfile $profile,
        string $password,
        EmployeeContact $employeeContact
    ) {
        $employee = new self();

        $employee->contacts = $employeeContact;
        $employee->profile = $profile;
        $employee->profile->setLogin($company, $employeeContact->getEmail());
        $employee->password = Hash::make($password);
        $employee->isActive = false;
        $employee->setScope($company);
        $employee->department = $company->getRootDepartment();
        $employee->departmentId = $company->getRootDepartment()->getId();
        $employee->department->addEmployee($employee);
        $employee->registeredAt = new DateTime();
        $employee->matrixId = $employee->getMatrixId();

        return $employee;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return EmployeeProfile
     */
    public function getProfile(): EmployeeProfile
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
        return $this->getCompany()->getId() . ':' . $this->getContacts()->getEmail();
    }

    public function getMatrixId() : string
    {
        return
            '@' . $this->getCompany()->getId() . //company
            '_' . str_replace('@', '_', $this->getContacts()->getEmail()); //matrix login
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
    public function getCompany(): Company
    {
        return $this->department->getCompany();
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
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
     * @param string|null $scope
     */
    public function setScope(Company $company, $scope = null)
    {
        $oldValue = $this->profile->scope;
        if ($scope === null) {
            $this->setDefaultScope($company);
            $this->scopeChangedEvent($oldValue);
            return;
        }
        $this->profile->scope = $scope;
        $this->scopeChangedEvent($oldValue);
    }


    private function setDefaultScope(Company $company)
    {
        if ($company->getEmployees()->count() === 0) {
            $this->profile->scope = EmployeeRole::ADMIN;
        } else {
            $this->profile->scope = EmployeeRole::EMPLOYEE;
        }
    }

    public function activate()
    {
        if (!$this->isActive()) {
            $this->isActive = true;
            event(new EmployeeActivated($this->getLogin(), new DateTime()));
        }
    }

    public function deactivate()
    {
        $this->isActive = false;
        $this->deletedAt = new DateTime();
        event(new EmployeeDeactivated($this->getLogin(), $this->getCompany()));
    }

    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @return DateTime
     */
    public function getRegisteredAt(): DateTime
    {
        return $this->registeredAt;
    }

    /**
     * @return \DateTime|null
     *
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param $oldValue
     */
    protected function scopeChangedEvent($oldValue)
    {
        if ($oldValue !== null) {
            event(new ScopeChanged($this, $oldValue));
        }
    }

    /**
     * @param Employee $contact
     * @throws ContactAlreadyAdded
     */
    public function addContact(Employee $contact)
    {
        $key = $this->searchContactInList($contact);
        if ($key !== false) {
            throw new ContactAlreadyAdded();
        }

        $contactListItem = new EmployeeContactListItem($contact);
        $this->contactList->add($contactListItem);
    }

    /**
     * @param Employee $contact
     * @throws ContactNotFound
     */
    public function deleteContact(Employee $contact)
    {
        $key = $this->searchContactInList($contact);
        if ($key === false) {
            throw new ContactNotFound();
        }

        $this->contactList->remove($key);
    }

    /**
     * @param Employee $contact
     * @return bool|int|mixed|string
     */
    protected function searchContactInList(Employee $contact)
    {
        foreach ($this->contactList as $key => $value) {
            /**
             * @var $value EmployeeContactListItem
             */
            if ($value->getEmployee()->getId() === $contact->getId()) {
                return $key;
            }
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getContactList() : ArrayCollection
    {
        if ($this->contactList instanceof PersistentCollection) {
            $this->contactList->initialize();
            return $this->contactList->unwrap();
        }

        return $this->contactList;
    }

    public function isAddedToContactList(Employee $contact)
    {
        return $this->searchContactInList($contact) !== false;
    }

    public function getScope()
    {
        return $this->profile->scope;
    }

    public function getDepartmentId() : string
    {
        return $this->departmentId;
    }

    public function updateDepartmentReference()
    {
        $this->departmentId = $this->department->getId();
    }

    public function setWallets(array $wallets)
    {
        $this->wallets = $wallets;
    }

    public function getWallets()
    {
        return $this->wallets;
    }
}
