<?php
use App\Domains\Employee\ValueObjects\EmployeeRole;
use App\Core\Interfaces\IdentityInterface;

class EmployeeRoleCest
{
    public function _before(UnitTester $I)
    {
        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(IdentityInterface::class, $identityMock);
    }

    public function _after(UnitTester $I)
    {
    }

    public function canCheckEmployeeIsAdminOrNot(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $company = CompanyFactory::make();

        $employee->setScope($company, EmployeeRole::ADMIN);

        $I->assertTrue(EmployeeRole::isAdmin($employee));
        $I->assertFalse(EmployeeRole::isEmployee($employee));

        $employee->setScope($company, EmployeeRole::EMPLOYEE);

        $I->assertFalse(EmployeeRole::isAdmin($employee));
        $I->assertTrue(EmployeeRole::isEmployee($employee));
    }
}
