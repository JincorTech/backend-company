<?php
use App\Domains\Company\Entities\Department;

class DepartmentCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function canCreateAndGetProps(UnitTester $I)
    {
        $name = 'Root';
        $department = new Department($name, []);

        $I->assertEquals($name, $department->getName());
        $I->assertNotEmpty($department->getId());

        $company = CompanyFactory::make();
        $department->associateCompany($company);
        $I->assertEquals($company, $department->getCompany());

        $employee = EmployeeFactory::make();
        $department->addEmployee($employee);
        $I->assertEquals($employee, $department->getEmployees()->get(0));
    }
}
