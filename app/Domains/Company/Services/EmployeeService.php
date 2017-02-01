<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/31/17
 * Time: 1:46 PM
 */

namespace App\Domains\Company\Services;

use App\Domains\Company\Entities\Employee;
use App\Domains\Company\Entities\Company;
use Doctrine\ODM\MongoDB\DocumentManager;
use Illuminate\Support\Collection;
use App;

class EmployeeService
{
    /**
     * @var DocumentManager|mixed
     */
    private $dm;

    /**
     * @var App\Domains\Company\Repositories\EmployeeRepository
     */
    private $repository;

    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->repository = $this->dm->getRepository(Employee::class);
    }

    /**
     * @param string $email
     * @return Collection
     */
    public function findByEmail(string $email) : Collection
    {
        $employees = $this->repository->findBy([
            'contacts.email' => $email,
        ]);

        return new Collection($employees);
    }

    public function findByLogin(string $login)
    {
        $employee = new Collection($this->repository->findBy([
            'profile.login' => $login,
        ]));

        return $employee->first();
    }

    /**
     * @param string $id
     * @param string $email
     * @return Employee|null
     */
    public function findByCompanyIdAndEmail(string $id, string $email)
    {
        /** @var Company $company */
        $company = $this->dm->getRepository(Company::class)->find($id);
        /** @var Employee $employee */
        foreach ($company->getEmployees() as $employee) {
            if ($employee->getContacts()->getEmail() === $email) {
                return $employee;
            }
        }

        return;
    }

    /**
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function findByEmailAndPassword(string $email, string $password) : Collection
    {
        $employees = $this->findByEmail($email);
        $matched = new Collection();
        /** @var Employee $employee */
        foreach ($employees as $employee) {
            if ($employee->checkPassword($password)) {
                $matched->push($employee);
            }
        }

        return $matched;
    }

    /**
     * @param Collection $employees
     * @return Collection
     */
    public function getEmployeesCompanies(Collection $employees) : Collection
    {
        $companies = new Collection();
        $employees->each(function (Employee $employee) use ($companies) {
            $company = $employee->getCompany();
            $companies->put($company->getId(), $company);
        });

        return $companies;
    }
}
