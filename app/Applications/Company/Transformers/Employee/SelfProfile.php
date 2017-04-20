<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:31
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Applications\Company\Transformers\Company\CompanyTransformer;
use App\Applications\Company\Transformers\Company\MyCompany;
use App\Domains\Company\Entities\Company;
use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\ValueObjects\EmployeeRole;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class SelfProfile
 * @package App\Applications\Company\Transformers\Employee
 *
 * Profile of authenticated user
 *
 */
class SelfProfile extends TransformerAbstract
{

    public function transform(Employee $employee)
    {
        return [
            'id' => $employee->getId(),
            'profile' => $this->getProfile($employee),
            'admin' => $employee->isAdmin(),
            'contacts' => $this->getContacts($employee),
            'company' => $this->getCompany($employee->getCompany()),
        ];
    }


    /**
     * @param Employee $employee
     * @return array
     */
    protected function getContacts(Employee $employee)
    {
        return [
            'email' => $employee->getContacts()->getEmail(),
            'phone' => $employee->getContacts()->getPhone(),
        ];
    }

    /**
     * @param Employee $employee
     * @return array
     */
    protected function getProfile(Employee $employee)
    {
        return [
            'name' => $employee->getProfile()->getName(),
            'position' => $employee->getProfile()->getPosition(),
            'role' => $employee->isAdmin() ? EmployeeRole::ADMIN : EmployeeRole::EMPLOYEE,
            'avatar' => $employee->getProfile()->getAvatar(),
        ];
    }

    protected function getCompany(Company $company)
    {
        return (new MyCompany())->transform($company);
    }

}