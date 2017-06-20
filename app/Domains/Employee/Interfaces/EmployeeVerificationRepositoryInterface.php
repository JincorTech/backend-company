<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 16:42
 */

namespace App\Domains\Employee\Interfaces;

use App\Domains\Company\Entities\Company;
use App\Domains\Employee\Entities\Employee;

interface EmployeeVerificationRepositoryInterface
{
    public function getOpenVerificationsCountByCompanyAndEmail(Company $company, string $email);

    public function getVerificationsByEmployee(Employee $employee);
}
