<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 4:54 PM
 */

namespace App\Domains\Company\Entities;

use App\Core\ValueObjects\Address;
use App\Domains\Company\ValueObjects\CompanyProfile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;

/**
 * Class Company.
 *
 * @ODM\Document(
 *     collection="companies",
 *     repositoryClass="App\Domains\Company\Repositories\CompanyRepository"
 * )
 */
class Company
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
    protected $legalName;

    /**
     * @var Address
     * @ODM\EmbedOne(targetDocument="App\Core\ValueObjects\Address")
     */
    protected $legalAddress;

   /**
    * @var
    *
    * @ODM\ReferenceOne(
    *     targetDocument="App\Domains\Company\Entities\CompanyType"
    * )
    */
   protected $type;

    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(
     *     targetDocument="App\Domains\Company\Entities\Department",
     *     mappedBy="company",
     *     cascade={"persist"}
     * )
     */
    protected $departments;

    public function __construct(
        string $legalName,
        Address $address,
        CompanyType $type
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->legalName = $legalName;
        $this->legalAddress = $address;
        $this->type = $type;
        $this->departments = new ArrayCollection([]);
        $this->addDepartment(new Department('initial'));
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLegalName() : string
    {
        return $this->legalName;
    }

    /**
     * @return Address
     */
    public function getLegalAddress() : Address
    {
        return $this->legalAddress;
    }

    /**
     * @return CompanyProfile
     */
    public function getProfile() : CompanyProfile
    {
        return $this->profile;
    }

    public function addEmployee(Employee $employee)
    {
        /** @var Department $department */
        $department = $this->departments->first();
        $department->addEmployee($employee);
    }

    public function addDepartment(Department $department)
    {
        $this->departments[] = $department;
        $department->associateCompany($this);
    }

    public function getRootDepartment() : Department
    {
        return $this->departments->first();
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @return ArrayCollection
     */
    public function getEmployees() : ArrayCollection
    {
        return $this->getRootDepartment()->getEmployees();
    }
}
