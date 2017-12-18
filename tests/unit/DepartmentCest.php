<?php
use App\Domains\Company\Entities\Department;
use App\Core\Interfaces\MessengerServiceInterface;
use App\Core\Interfaces\IdentityInterface;

class DepartmentCest
{
    public function _before(UnitTester $I)
    {
        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(MessengerServiceInterface::class, $messengerMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(IdentityInterface::class, $identityMock);

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->once()->andReturn('token');
        $jwtServiceMock->shouldReceive('getCompanyId')->once()->andReturn('9fcad7c5-f84e-4d43-b35c-05e69d0e0362');
        App::instance(JWTService::class, $jwtServiceMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
        App::instance(IdentityInterface::class, $identityMock);
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
        $I->assertEquals($company->getId(), $department->getCompanyId());

        $employee = EmployeeFactory::make();
        $department->addEmployee($employee);
        $I->assertEquals($employee, $department->getEmployees()->get(0));
    }
}
