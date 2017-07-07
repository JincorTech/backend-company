<?php
use App\Domains\Employee\Events\EmployeeRegistered;

class EmployeeRegisteredCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function testGetData(UnitTester $I)
    {
        $employee = EmployeeFactory::makeFromDb();
        $event = new EmployeeRegistered($employee->getCompany(), $employee, $employee->getScope());

        $expected = [
            'employeeId' => $employee->getId(),
            'email' => $employee->getContacts()->getEmail(),
            'password' => $employee->getPassword(),
            'tenant' => $employee->getCompany()->getId(),
            'companyName' => $employee->getCompany()->getProfile()->getName(),
            'name' => $employee->getProfile()->getName(),
            'position' => $employee->getProfile()->getPosition(),
            'scope' => $employee->getProfile()->scope,
            'sub' => $employee->getMatrixId(),
        ];

        $actual = $event->getData();
        $I->assertEquals($expected, $actual);
    }
}
