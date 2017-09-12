<?php

use Illuminate\Database\Seeder;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\Department;
use App\Domains\Employee\Entities\Employee;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $companies = $this->getDm()->getRepository(Company::class)->findAll();
        foreach ($companies as $company) {
            /**
             * @var $company Company
             */
            $company->getProfile()->setCompanyType($company->getProfile()->getType());
            $company->getProfile()->setEconomicalActivities($company->getProfile()->getEconomicalActivities()->toArray());
            $company->getProfile()->getAddress()->updateCountryReference();
            $this->getDm()->persist($company);
        }

        $departments = $this->getDm()->getRepository(Department::class)->findAll();
        foreach ($departments as $department) {
            /**
             * @var $department Department
             */
            $department->associateCompany($department->getCompany());
            $this->getDm()->persist($department);
        }

        $employees = $this->getDm()->getRepository(Employee::class)->findAll();
        foreach ($employees as $employee) {
            /**
             * @var $employee Employee
             */
            $employee->updateDepartmentReference();
            $this->getDm()->persist($employee);
        }

        $this->getDm()->flush();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm()
    {
        return $this->container->make(DocumentManager::class);
    }
}
