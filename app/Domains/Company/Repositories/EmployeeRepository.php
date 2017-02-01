<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 7:34 PM
 */

namespace App\Domains\Company\Repositories;

use App\Domains\Company\Entities\Company;
use Doctrine\ODM\MongoDB\DocumentRepository;

class EmployeeRepository extends DocumentRepository
{
    public function findByCompanyAndEmail(Company $company, string $email)
    {
        return $this->createQueryBuilder()
            ->field('department')
            ->references($company->getRootDepartment())
            ->getQuery()->execute();
    }
}
