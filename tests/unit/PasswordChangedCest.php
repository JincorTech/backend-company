<?php
use App\Domains\Employee\Events\PasswordChanged;

class PasswordChangedCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function testGetData(UnitTester $I)
    {
        $employee = EmployeeFactory::makeFromDb();
        $event = new PasswordChanged($employee, '123');

        $expected = [
            'employeeId' => $employee->getId(),
            'email' => $employee->getContacts()->getEmail(),
            'password' => $employee->getPassword(),
            'login' => $employee->getLogin(),
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
