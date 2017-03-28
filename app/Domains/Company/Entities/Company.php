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
     * @var CompanyProfile
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Domains\Company\ValueObjects\CompanyProfile",
     * )
     */
    protected $profile;



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
        $this->profile = new CompanyProfile($legalName, $address, $type);
        $this->initializeDepartment();
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return CompanyProfile|null
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Initialize empty department with name 'intial'
     */
    private function initializeDepartment()
    {
        $this->departments = new ArrayCollection([]);
        $department = new Department('initial');
        $this->departments[] = $department;
        $department->associateCompany($this);
    }

    /**
     * @return Department
     */
    public function getRootDepartment() : Department
    {
        return $this->departments->first();
    }

    /**
     * @return ArrayCollection
     */
    public function getEmployees() : ArrayCollection
    {
        return $this->getRootDepartment()->getEmployees();
    }
}
