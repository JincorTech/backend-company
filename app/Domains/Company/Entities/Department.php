<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/28/16
 * Time: 5:53 PM
 */

namespace App\Domains\Company\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Ramsey\Uuid\Uuid;

/**
 * Class Department.
 *
 * @ODM\Document(
 *     collection="departments",
 *     repositoryClass="App\Domains\Company\Repositories\DepartmentRepository"
 * )
 */
class Department
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
    protected $name;

    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(
     *     targetDocument="App\Domains\Company\Entities\Employee",
     *     mappedBy="department"
     * )
     */
    protected $employees;

    /**
     * @var Company
     * @ODM\ReferenceOne(
     *     targetDocument="App\Domains\Company\Entities\Company",
     *     inversedBy="companies"
     * )
     */
    protected $company;

    /**
     * Department constructor.
     *
     * @param string $name
     * @param array $employees
     */
    public function __construct(string $name, array $employees = [])
    {
        $this->id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->employees = new ArrayCollection($employees);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add an employee to this department.
     *
     * @param Employee $employee
     */
    public function addEmployee(Employee $employee)
    {
        $this->employees->add($employee);
        $employee->attachToDepartment($this);
    }

    public function associateCompany(Company $company)
    {
        $this->company = $company;
    }

    public function getCompany() : Company
    {
        return $this->company;
    }

    /**
     * @return ArrayCollection
     */
    public function getEmployees(): ArrayCollection
    {
        return new ArrayCollection($this->employees->toArray());
    }
}
