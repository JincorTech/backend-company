<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 14:16
 */

namespace App\Domains\Employee\Interfaces;

use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\Department;

interface EmployeeRepositoryInterface
{
    public function findByCompanyAndEmail(Company $company, string $email);

    public function findByDepartmentAndEmail(Department $department, string $email);
}
