<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 3:24 PM
 */

namespace App\Core\Repositories;

use App\Domains\Company\Entities\Company;
use App\Domains\Employee\Entities\Employee;
use Doctrine\ODM\MongoDB\DocumentRepository;
use App\Domains\Employee\Interfaces\EmployeeVerificationRepositoryInterface;
use App\Domains\Employee\Entities\EmployeeVerification;

class EmployeeVerificationRepository extends DocumentRepository implements EmployeeVerificationRepositoryInterface
{
    public function getOpenVerificationsCountByCompanyAndEmail(Company $company, string $email)
    {
        return $this->createQueryBuilder()
                    ->field('company')->references($company)
                    ->field('email')->equals($email)
                    ->field('emailVerified')->equals(false)
                    ->field('reason')->equals(EmployeeVerification::REASON_INVITED_BY_EMPLOYEE)
                    ->count()
                    ->getQuery()
                    ->execute();
    }

    public function getVerificationsByEmployee(Employee $employee)
    {
        return $this->createQueryBuilder()
                    ->field('reason')->equals(EmployeeVerification::REASON_INVITED_BY_EMPLOYEE)
                    ->field('emailVerified')->equals(false)
                    ->field('company')->references($employee->getCompany())
                    ->getQuery()
                    ->execute();
    }
}
