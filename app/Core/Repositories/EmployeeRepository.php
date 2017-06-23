<?php

namespace App\Core\Repositories;


use App\Domains\Employee\Interfaces\EmployeeRepositoryInterface;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\Department;
use Doctrine\ODM\MongoDB\DocumentRepository;

class EmployeeRepository extends DocumentRepository implements EmployeeRepositoryInterface
{
    public function findByCompanyAndEmail(Company $company, string $email)
    {
        return $this->createQueryBuilder()
            ->field('department')
            ->references($company->getRootDepartment())
            ->field('contacts.email')->equals($email)
            ->getQuery()->execute()
            ->toArray();
    }

    public function findByDepartmentAndEmail(Department $department, string $email)
    {
        return $this->createQueryBuilder()
            ->field('department')
            ->references($department)
            ->field('contacts.email')
            ->equals($email)->getQuery()->execute();
    }

    public function findAllByMatrixIds(array $matrixIds)
    {
        return $this->createQueryBuilder()
            ->field('matrixId')
            ->all($matrixIds)
            ->getQuery()->execute();
    }
}
